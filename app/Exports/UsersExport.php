<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UsersExport implements WithMultipleSheets
{
    protected $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function sheets(): array
    {
        $sheets = [
            new UsersInfoSheet($this->options),
        ];

        if ($this->options['include_activities'] ?? false) {
            $sheets[] = new UsersActivitiesSheet($this->options);
        }

        if ($this->options['include_bookings'] ?? false) {
            $sheets[] = new UsersBookingsSheet($this->options);
        }

        if ($this->options['include_contracts'] ?? false) {
            $sheets[] = new UsersContractsSheet($this->options);
        }

        if ($this->options['include_invoices'] ?? false) {
            $sheets[] = new UsersInvoicesSheet($this->options);
        }

        if ($this->options['include_payments'] ?? false) {
            $sheets[] = new UsersPaymentsSheet($this->options);
        }

        if ($this->options['include_comments'] ?? false) {
            $sheets[] = new UsersCommentsSheet($this->options);
        }

        return $sheets;
    }
}

class UsersInfoSheet implements FromCollection, WithHeadings, WithMapping
{
    protected $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function collection()
    {
        $query = User::query();

        if (isset($this->options['date_from'])) {
            $query->where('created_at', '>=', $this->options['date_from']);
        }

        if (isset($this->options['date_to'])) {
            $query->where('created_at', '<=', $this->options['date_to']);
        }

        if (isset($this->options['role_filter'])) {
            $query->where('role_id', $this->options['role_filter']);
        }

        if (isset($this->options['status_filter'])) {
            switch ($this->options['status_filter']) {
                case 'active':
                    $query->whereNotNull('last_login_at');
                    break;
                case 'inactive':
                    $query->whereNull('last_login_at');
                    break;
                case 'verified':
                    $query->whereNotNull('email_verified_at');
                    break;
                case 'unverified':
                    $query->whereNull('email_verified_at');
                    break;
            }
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'الاسم',
            'البريد الإلكتروني',
            'رقم الهاتف',
            'صورة الملف الشخصي',
            'النبذة الشخصية',
            'الموقع',
            'تاريخ الميلاد',
            'الجنس',
            'الدور الأساسي',
            'تاريخ الإنشاء',
            'تاريخ آخر تحديث',
            'آخر تسجيل دخول',
            'تاريخ تأكيد البريد',
            'تاريخ تأكيد الهاتف',
            'المصادقة الثنائية',
            'حالة الحساب',
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->phone ?? '',
            $user->profile_picture ?? '',
            $user->bio ?? '',
            $user->location ?? '',
            $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '',
            $user->gender ?? '',
            $user->primary_role ?? '',
            $user->created_at->format('Y-m-d H:i:s'),
            $user->updated_at->format('Y-m-d H:i:s'),
            $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : '',
            $user->email_verified_at ? $user->email_verified_at->format('Y-m-d H:i:s') : '',
            $user->phone_verified_at ? $user->phone_verified_at->format('Y-m-d H:i:s') : '',
            $user->two_factor_enabled ? 'مفعل' : 'غير مفعل',
            $user->last_login_at ? 'نشط' : 'غير نشط',
        ];
    }
}

class UsersActivitiesSheet implements FromCollection, WithHeadings, WithMapping
{
    protected $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function collection()
    {
        $query = \App\Models\ActivityLog::with('user');

        if (isset($this->options['date_from'])) {
            $query->where('created_at', '>=', $this->options['date_from']);
        }

        if (isset($this->options['date_to'])) {
            $query->where('created_at', '<=', $this->options['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'المستخدم',
            'البريد الإلكتروني',
            'الإجراء',
            'الوصف',
            'عنوان IP',
            'المتصفح',
            'نظام التشغيل',
            'نوع الجهاز',
            'التاريخ',
        ];
    }

    public function map($activity): array
    {
        return [
            $activity->id,
            $activity->user->name ?? 'غير محدد',
            $activity->user->email ?? 'غير محدد',
            $activity->action,
            $activity->description,
            $activity->ip_address ?? '',
            $activity->browser ?? '',
            $activity->platform ?? '',
            $activity->device_type ?? '',
            $activity->created_at->format('Y-m-d H:i:s'),
        ];
    }
}

class UsersBookingsSheet implements FromCollection, WithHeadings, WithMapping
{
    protected $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function collection()
    {
        $query = \App\Models\Booking::with(['user', 'product', 'facility']);

        if (isset($this->options['date_from'])) {
            $query->where('created_at', '>=', $this->options['date_from']);
        }

        if (isset($this->options['date_to'])) {
            $query->where('created_at', '<=', $this->options['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'المستخدم',
            'البريد الإلكتروني',
            'العقار',
            'المنشأة',
            'الحالة',
            'تاريخ البداية',
            'تاريخ النهاية',
            'المبلغ الإجمالي',
            'تاريخ الإنشاء',
        ];
    }

    public function map($booking): array
    {
        return [
            $booking->id,
            $booking->user->name ?? 'غير محدد',
            $booking->user->email ?? 'غير محدد',
            $booking->product->name ?? 'غير محدد',
            $booking->facility->name ?? 'غير محدد',
            $booking->status ?? 'غير محدد',
            $booking->start_date ?? '',
            $booking->end_date ?? '',
            $booking->total_amount ?? 0,
            $booking->created_at->format('Y-m-d H:i:s'),
        ];
    }
}

class UsersContractsSheet implements FromCollection, WithHeadings, WithMapping
{
    protected $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function collection()
    {
        $query = \App\Models\Contract::with(['user', 'product', 'facility']);

        if (isset($this->options['date_from'])) {
            $query->where('created_at', '>=', $this->options['date_from']);
        }

        if (isset($this->options['date_to'])) {
            $query->where('created_at', '<=', $this->options['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'المستخدم',
            'البريد الإلكتروني',
            'العقار',
            'المنشأة',
            'الحالة',
            'تاريخ البداية',
            'تاريخ النهاية',
            'المبلغ الإجمالي',
            'تاريخ الإنشاء',
        ];
    }

    public function map($contract): array
    {
        return [
            $contract->id,
            $contract->user->name ?? 'غير محدد',
            $contract->user->email ?? 'غير محدد',
            $contract->product->name ?? 'غير محدد',
            $contract->facility->name ?? 'غير محدد',
            $contract->status ?? 'غير محدد',
            $contract->start_date ?? '',
            $contract->end_date ?? '',
            $contract->total_amount ?? 0,
            $contract->created_at->format('Y-m-d H:i:s'),
        ];
    }
}

class UsersInvoicesSheet implements FromCollection, WithHeadings, WithMapping
{
    protected $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function collection()
    {
        $query = \App\Models\Invoice::with(['user', 'contract']);

        if (isset($this->options['date_from'])) {
            $query->where('created_at', '>=', $this->options['date_from']);
        }

        if (isset($this->options['date_to'])) {
            $query->where('created_at', '<=', $this->options['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'المستخدم',
            'البريد الإلكتروني',
            'العقد',
            'الحالة',
            'المبلغ',
            'تاريخ الاستحقاق',
            'تاريخ الدفع',
            'تاريخ الإنشاء',
        ];
    }

    public function map($invoice): array
    {
        return [
            $invoice->id,
            $invoice->user->name ?? 'غير محدد',
            $invoice->user->email ?? 'غير محدد',
            $invoice->contract_id ?? 'غير محدد',
            $invoice->status ?? 'غير محدد',
            $invoice->amount ?? 0,
            $invoice->due_date ?? '',
            $invoice->paid_at ? $invoice->paid_at->format('Y-m-d H:i:s') : '',
            $invoice->created_at->format('Y-m-d H:i:s'),
        ];
    }
}

class UsersPaymentsSheet implements FromCollection, WithHeadings, WithMapping
{
    protected $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function collection()
    {
        $query = \App\Models\Payment::with(['user', 'invoice']);

        if (isset($this->options['date_from'])) {
            $query->where('created_at', '>=', $this->options['date_from']);
        }

        if (isset($this->options['date_to'])) {
            $query->where('created_at', '<=', $this->options['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'المستخدم',
            'البريد الإلكتروني',
            'الفاتورة',
            'المبلغ',
            'طريقة الدفع',
            'الحالة',
            'تاريخ الإنشاء',
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->id,
            $payment->user->name ?? 'غير محدد',
            $payment->user->email ?? 'غير محدد',
            $payment->invoice_id ?? 'غير محدد',
            $payment->amount ?? 0,
            $payment->method ?? 'غير محدد',
            $payment->status ?? 'غير محدد',
            $payment->created_at->format('Y-m-d H:i:s'),
        ];
    }
}

class UsersCommentsSheet implements FromCollection, WithHeadings, WithMapping
{
    protected $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function collection()
    {
        $query = \App\Models\Comment::with(['user', 'product']);

        if (isset($this->options['date_from'])) {
            $query->where('created_at', '>=', $this->options['date_from']);
        }

        if (isset($this->options['date_to'])) {
            $query->where('created_at', '<=', $this->options['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'المستخدم',
            'البريد الإلكتروني',
            'العقار',
            'المحتوى',
            'التقييم',
            'تاريخ الإنشاء',
        ];
    }

    public function map($comment): array
    {
        return [
            $comment->id,
            $comment->user->name ?? 'غير محدد',
            $comment->user->email ?? 'غير محدد',
            $comment->product->name ?? 'غير محدد',
            $comment->content ?? '',
            $comment->rating ?? 0,
            $comment->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
