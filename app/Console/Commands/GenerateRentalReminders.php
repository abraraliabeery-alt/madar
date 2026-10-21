<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Contract;
use App\Models\LeadActivity;
use App\Models\Facility;
use App\Notifications\GenericNotification;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;

class GenerateRentalReminders extends Command
{
    protected $signature = 'reminders:rentals';
    protected $description = 'توليد إشعارات تلقائية: عقود تنتهي، دفعات متأخرة، ومعاينات اليوم';

    public function handle(): int
    {
        $whatsapp = app(WhatsAppService::class);
        $facilities = Facility::with('facilityCategory')
            ->whereHas('facilityCategory', function ($q) {
                $q->whereIn('platform_mode', ['real_estate', 'lifecycle']);
            })
            ->get();

        $sent = 0;

        foreach ($facilities as $facility) {
            $users = $facility->users;
            if ($users->isEmpty()) {
                continue;
            }

            // عقود تنتهي خلال 30 يوم
            $expiring = Contract::where('facility_id', $facility->id)
                ->where('status', 'active')
                ->whereNotNull('end_date')
                ->whereBetween('end_date', [now()->startOfDay(), now()->addDays(30)->endOfDay()])
                ->with(['product.translations', 'tenant'])
                ->get();

            foreach ($expiring as $contract) {
                $sent += $this->notifyOnce($users, 'contract_expiring', $contract->id,
                    "عقد رقم " . ($contract->contract_number ?? $contract->id) . " ينتهي في " . $contract->end_date->format('Y-m-d'));

                // واتساب للمستأجر: عقدك يقترب من الانتهاء
                if ($whatsapp->isConfigured() && $contract->tenant?->phone) {
                    $whatsapp->send($contract->tenant->phone,
                        "عزيزنا المستأجر، عقد إيجارك رقم " . ($contract->contract_number ?? $contract->id) .
                        " ينتهي في " . $contract->end_date->format('Y-m-d') . ". تواصل معنا للتجديد.");
                }
            }

            // دفعات متأخرة
            $overdue = Contract::where('facility_id', $facility->id)
                ->where('status', 'active')
                ->whereNotNull('next_payment_date')
                ->where('next_payment_date', '<', now()->startOfDay())
                ->with('tenant')
                ->get();

            foreach ($overdue as $contract) {
                $sent += $this->notifyOnce($users, 'payment_overdue', $contract->id,
                    "دفعة متأخرة على العقد " . ($contract->contract_number ?? $contract->id) . " — كان مستحقاً " . $contract->next_payment_date->format('Y-m-d'));

                // واتساب للمستأجر: تذكير سداد + رابط البوابة
                if ($whatsapp->isConfigured() && $contract->tenant?->phone) {
                    $portalUrl = \Illuminate\Support\Facades\URL::signedRoute('tenant-portal.contract', ['contract' => $contract->id]);
                    $whatsapp->send($contract->tenant->phone,
                        "تذكير ودي: دفعة عقدك رقم " . ($contract->contract_number ?? $contract->id) .
                        " متأخرة منذ " . $contract->next_payment_date->format('Y-m-d') .
                        ".\nيمكنك السداد ومتابعة فواتيرك من هنا:\n" . $portalUrl);
                }
            }

            // معاينات اليوم
            $todayViewings = Booking::whereDate('preferred_date', today())
                ->where(function ($q) use ($facility) {
                    $q->where('facility_id', $facility->id)
                      ->orWhereHas('product', fn($p) => $p->where('facility_id', $facility->id));
                })
                ->with('user')
                ->get();

            foreach ($todayViewings as $booking) {
                $sent += $this->notifyOnce($users, 'viewing_today', $booking->id,
                    "معاينة اليوم: " . ($booking->user->name ?? $booking->name ?? 'زائر') . " الساعة " . ($booking->preferred_time ?? '—'));
            }

            // متابعات عملاء محتملين مستحقة
            $dueFollowUps = LeadActivity::whereHas('lead', fn($q) => $q
                    ->where('facility_id', $facility->id)
                    ->whereNotIn('stage', ['won', 'lost']))
                ->whereNotNull('follow_up_at')
                ->where('follow_up_at', '<=', now())
                ->with('lead')
                ->get();

            foreach ($dueFollowUps as $activity) {
                $targets = $activity->lead->assigned_to
                    ? $users->where('id', $activity->lead->assigned_to)
                    : $users;
                $sent += $this->notifyOnce($targets, 'lead_follow_up', $activity->id,
                    "متابعة مستحقة: " . ($activity->lead->name ?? 'عميل محتمل') . " — " . ($activity->body ?? 'بدون ملاحظة'));
            }

            // تنبؤ الشغور: وحدة عقدها ينتهي خلال 30 يوم وما لها عقد لاحق
            foreach ($expiring->whereNotNull('product_id') as $contract) {
                $hasNext = Contract::where('facility_id', $facility->id)
                    ->where('product_id', $contract->product_id)
                    ->where('id', '!=', $contract->id)
                    ->where('status', 'active')
                    ->where('start_date', '<=', $contract->end_date)
                    ->exists();

                if (!$hasNext) {
                    $unit = $contract->product;
                    $sent += $this->notifyOnce($users, 'vacancy_forecast', $contract->id,
                        "وحدة ستشغر: " . ($unit?->getTranslatedTitle() ?? '#'.$contract->product_id) . " — عقدها ينتهي " . $contract->end_date->format('Y-m-d') . " بدون عقد لاحق");
                }
            }
        }

        $this->info("تم إرسال {$sent} إشعار");
        return self::SUCCESS;
    }

    protected function notifyOnce($users, string $type, int $refId, string $message): int
    {
        $sent = 0;
        foreach ($users as $user) {
            $exists = $user->notifications()
                ->whereNull('read_at')
                ->where('data->type', $type)
                ->where('data->ref_id', $refId)
                ->exists();

            if (!$exists) {
                $user->notify(new GenericNotification($type, $message, ['ref_id' => $refId]));
                $sent++;
            }
        }
        return $sent;
    }
}
