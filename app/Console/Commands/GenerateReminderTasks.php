<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Facility;
use App\Models\Task;
use Carbon\Carbon;

class GenerateReminderTasks extends Command
{
    protected $signature = 'reminders:generate-tasks';
    protected $description = 'Generate reminder tasks for overdue/due invoices and today\'s bookings for all facilities (no DB schema changes)';

    public function handle(): int
    {
        $today = Carbon::today();
        $totalCreated = 0;

        Facility::query()->chunk(50, function ($facilities) use ($today, &$totalCreated) {
            foreach ($facilities as $facility) {
                $creatorId = optional($facility->users()->first())->id; // fallback to first facility user
                if (!$creatorId) {
                    continue; // skip facilities without users
                }

                // Overdue invoices
                if (method_exists($facility, 'invoices')) {
                    $overdue = $facility->invoices()
                        ->whereDate('due_date', '<', $today)
                        ->where(function($q){ $q->whereNull('paid_at')->orWhere('status','!=','paid'); })
                        ->limit(500)
                        ->get();

                    foreach ($overdue as $inv) {
                        $type = 'تذكير فاتورة متأخرة #' . $inv->id;
                        if (!Task::where('type', $type)->whereDate('created_at', $today)->exists()) {
                            Task::create([
                                'type' => $type,
                                'status' => 'open',
                                'priority' => 'high',
                                'deadline' => $today,
                                'created_by' => $creatorId,
                            ]);
                            $totalCreated++;
                        }
                    }

                    // Due today invoices
                    $dueToday = $facility->invoices()
                        ->whereDate('due_date', '=', $today)
                        ->where(function($q){ $q->whereNull('paid_at')->orWhere('status','!=','paid'); })
                        ->limit(500)
                        ->get();

                    foreach ($dueToday as $inv) {
                        $type = 'تذكير فاتورة مستحقة اليوم #' . $inv->id;
                        if (!Task::where('type', $type)->whereDate('created_at', $today)->exists()) {
                            Task::create([
                                'type' => $type,
                                'status' => 'open',
                                'priority' => 'medium',
                                'deadline' => $today,
                                'created_by' => $creatorId,
                            ]);
                            $totalCreated++;
                        }
                    }
                }

                // Today bookings
                if (method_exists($facility, 'bookings')) {
                    $bookingsToday = $facility->bookings()
                        ->whereDate('date', $today)
                        ->limit(500)
                        ->get();

                    foreach ($bookingsToday as $bk) {
                        $type = 'متابعة حجز اليوم #' . $bk->id;
                        if (!Task::where('type', $type)->whereDate('created_at', $today)->exists()) {
                            Task::create([
                                'type' => $type,
                                'status' => 'open',
                                'priority' => 'medium',
                                'deadline' => $today,
                                'created_by' => $creatorId,
                            ]);
                            $totalCreated++;
                        }
                    }
                }
            }
        });

        $this->info('Generated '.$totalCreated.' reminder tasks.');
        return self::SUCCESS;
    }
}
