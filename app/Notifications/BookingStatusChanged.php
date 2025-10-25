<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingStatusChanged extends Notification
{
    use Queueable;

    protected $booking;
    protected $oldStatus;
    protected $newStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking, $oldStatus, $newStatus)
    {
        $this->booking = $booking;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
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
        return (new MailMessage)
            ->subject('تحديث حالة الحجز - ' . $this->booking->product->name)
            ->greeting('مرحباً ' . $notifiable->name)
            ->line('تم تحديث حالة حجزك للعقار: ' . $this->booking->product->name)
            ->line('الحالة السابقة: ' . $this->oldStatus)
            ->line('الحالة الجديدة: ' . $this->newStatus)
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
        return [
            'booking_id' => $this->booking->id,
            'product_name' => $this->booking->product->title ?? 'عقار',
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'type' => 'booking_status_changed',
            'message' => 'تم تحديث حالة حجزك للعقار: ' . ($this->booking->product->title ?? 'عقار') . ' من ' . $this->oldStatus . ' إلى ' . $this->newStatus,
            'action_url' => route('client.bookings.show', $this->booking->id),
        ];
    }
}
