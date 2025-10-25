<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class UserDataExport implements WithMultipleSheets
{
    protected $user;
    protected $options;

    public function __construct(User $user, array $options = [])
    {
        $this->user = $user;
        $this->options = $options;
    }

    public function sheets(): array
    {
        $sheets = [
            new UserInfoSheet($this->user),
        ];

        if ($this->options['include_activities'] ?? false) {
            $sheets[] = new UserActivitiesSheet($this->user, $this->options);
        }

        if ($this->options['include_notifications'] ?? false) {
            $sheets[] = new UserNotificationsSheet($this->user, $this->options);
        }

        if ($this->options['include_bookings'] ?? false) {
            $sheets[] = new UserBookingsSheet($this->user, $this->options);
        }

        if ($this->options['include_contracts'] ?? false) {
            $sheets[] = new UserContractsSheet($this->user, $this->options);
        }

        if ($this->options['include_invoices'] ?? false) {
            $sheets[] = new UserInvoicesSheet($this->user, $this->options);
        }

        if ($this->options['include_payments'] ?? false) {
            $sheets[] = new UserPaymentsSheet($this->user, $this->options);
        }

        if ($this->options['include_comments'] ?? false) {
            $sheets[] = new UserCommentsSheet($this->user, $this->options);
        }

        if ($this->options['include_favorites'] ?? false) {
            $sheets[] = new UserFavoritesSheet($this->user, $this->options);
        }

        return $sheets;
    }
}

class UserInfoSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function collection()
    {
        return collect([$this->user]);
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
            'تاريخ الإنشاء',
            'تاريخ آخر تحديث',
            'آخر تسجيل دخول',
            'تاريخ تأكيد البريد',
            'تاريخ تأكيد الهاتف',
            'المصادقة الثنائية',
            'إعدادات الإشعارات',
            'إعدادات الخصوصية',
            'التفضيلات',
            'إعدادات الأمان',
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->phone,
            $user->profile_picture,
            $user->bio,
            $user->location,
            $user->date_of_birth,
            $user->gender,
            $user->created_at->format('Y-m-d H:i:s'),
            $user->updated_at->format('Y-m-d H:i:s'),
            $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : '',
            $user->email_verified_at ? $user->email_verified_at->format('Y-m-d H:i:s') : '',
            $user->phone_verified_at ? $user->phone_verified_at->format('Y-m-d H:i:s') : '',
            $user->two_factor_enabled ? 'مفعل' : 'غير مفعل',
            json_encode($user->notification_settings ?? []),
            json_encode($user->privacy_settings ?? []),
            json_encode($user->preferences ?? []),
            json_encode($user->security_settings ?? []),
        ];
    }

    public function title(): string
    {
        return 'معلومات المستخدم';
    }
}

class UserActivitiesSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $user;
    protected $options;

    public function __construct(User $user, array $options = [])
    {
        $this->user = $user;
        $this->options = $options;
    }

    public function collection()
    {
        $query = $this->user->activityLogs();
        
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
            $activity->action,
            $activity->description,
            $activity->ip_address,
            $activity->browser,
            $activity->platform,
            $activity->device_type,
            $activity->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function title(): string
    {
        return 'سجل النشاط';
    }
}

class UserNotificationsSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $user;
    protected $options;

    public function __construct(User $user, array $options = [])
    {
        $this->user = $user;
        $this->options = $options;
    }

    public function collection()
    {
        $query = $this->user->notifications();
        
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
            'النوع',
            'العنوان',
            'الرسالة',
            'تاريخ القراءة',
            'تاريخ الإنشاء',
        ];
    }

    public function map($notification): array
    {
        return [
            $notification->id,
            $notification->type,
            $notification->title,
            $notification->message,
            $notification->read_at ? $notification->read_at->format('Y-m-d H:i:s') : '',
            $notification->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function title(): string
    {
        return 'الإشعارات';
    }
}

class UserBookingsSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $user;
    protected $options;

    public function __construct(User $user, array $options = [])
    {
        $this->user = $user;
        $this->options = $options;
    }

    public function collection()
    {
        $query = \App\Models\Booking::where('user_id', $this->user->id);
        
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
            $booking->product_id,
            $booking->facility_id,
            $booking->status,
            $booking->start_date,
            $booking->end_date,
            $booking->total_amount,
            $booking->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function title(): string
    {
        return 'الحجوزات';
    }
}

class UserContractsSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $user;
    protected $options;

    public function __construct(User $user, array $options = [])
    {
        $this->user = $user;
        $this->options = $options;
    }

    public function collection()
    {
        $query = \App\Models\Contract::where('user_id', $this->user->id);
        
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
            $contract->product_id,
            $contract->facility_id,
            $contract->status,
            $contract->start_date,
            $contract->end_date,
            $contract->total_amount,
            $contract->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function title(): string
    {
        return 'العقود';
    }
}

class UserInvoicesSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $user;
    protected $options;

    public function __construct(User $user, array $options = [])
    {
        $this->user = $user;
        $this->options = $options;
    }

    public function collection()
    {
        $query = \App\Models\Invoice::where('user_id', $this->user->id);
        
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
            $invoice->contract_id,
            $invoice->status,
            $invoice->amount,
            $invoice->due_date,
            $invoice->paid_at ? $invoice->paid_at->format('Y-m-d H:i:s') : '',
            $invoice->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function title(): string
    {
        return 'الفواتير';
    }
}

class UserPaymentsSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $user;
    protected $options;

    public function __construct(User $user, array $options = [])
    {
        $this->user = $user;
        $this->options = $options;
    }

    public function collection()
    {
        $query = \App\Models\Payment::where('user_id', $this->user->id);
        
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
            $payment->invoice_id,
            $payment->amount,
            $payment->method,
            $payment->status,
            $payment->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function title(): string
    {
        return 'المدفوعات';
    }
}

class UserCommentsSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $user;
    protected $options;

    public function __construct(User $user, array $options = [])
    {
        $this->user = $user;
        $this->options = $options;
    }

    public function collection()
    {
        $query = \App\Models\Comment::where('user_id', $this->user->id);
        
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
            $comment->product_id,
            $comment->content,
            $comment->rating,
            $comment->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function title(): string
    {
        return 'التعليقات';
    }
}

class UserFavoritesSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $user;
    protected $options;

    public function __construct(User $user, array $options = [])
    {
        $this->user = $user;
        $this->options = $options;
    }

    public function collection()
    {
        $favorites = collect();
        
        // Add favorite products
        $favoriteProducts = $this->user->favoriteProducts()->get()->map(function ($product) {
            return [
                'type' => 'عقار',
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'location' => $product->location,
                'created_at' => $product->created_at,
            ];
        });
        
        // Add favorite facilities
        $favoriteFacilities = $this->user->favoriteFacilities()->get()->map(function ($facility) {
            return [
                'type' => 'منشأة',
                'id' => $facility->id,
                'name' => $facility->name,
                'price' => null,
                'location' => $facility->location,
                'created_at' => $facility->created_at,
            ];
        });
        
        return $favorites->merge($favoriteProducts)->merge($favoriteFacilities);
    }

    public function headings(): array
    {
        return [
            'النوع',
            'ID',
            'الاسم',
            'السعر',
            'الموقع',
            'تاريخ الإضافة',
        ];
    }

    public function map($favorite): array
    {
        return [
            $favorite['type'],
            $favorite['id'],
            $favorite['name'],
            $favorite['price'],
            $favorite['location'],
            $favorite['created_at']->format('Y-m-d H:i:s'),
        ];
    }

    public function title(): string
    {
        return 'المفضلة';
    }
}
