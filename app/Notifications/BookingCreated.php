<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCreated extends Notification
{
    use Queueable;

    protected $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $bookingDate = $this->booking->booking_date ? $this->booking->booking_date->format('Y-m-d') : 'غير محدد';

        return (new MailMessage)
            ->subject('حجز جديد - ' . $this->booking->product->name)
            ->greeting('مرحباً ' . $notifiable->name)
            ->line('تم إنشاء حجز جديد للعقار: ' . $this->booking->product->name)
            ->line('تاريخ الحجز: ' . $bookingDate)
                            ->line('المبلغ: ' . $this->booking->total_amount . ' ' . \App\Helpers\LanguageHelper::getSaudiRiyalSymbol())
            ->action('عرض التفاصيل', url('/bookings/' . $this->booking->id))
            ->line('شكراً لاستخدام منصة عقار!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $bookingDate = $this->booking->preferred_date ? $this->booking->preferred_date->format('Y-m-d') : 'غير محدد';

        return [
            'booking_id' => $this->booking->id,
            'product_name' => $this->booking->product->title ?? 'عقار',
            'booking_date' => $bookingDate,
            'total_amount' => $this->booking->total_amount ?? 0,
            'type' => 'booking_created',
            'message' => 'تم إنشاء حجز جديد للعقار: ' . ($this->booking->product->title ?? 'عقار'),
            'action_url' => route('client.bookings.show', $this->booking->id),
        ];
    }
}
