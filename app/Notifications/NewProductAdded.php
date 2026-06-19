<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewProductAdded extends Notification
{
    use Queueable;

    protected $product;

    /**
     * Create a new notification instance.
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
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
            ->subject('مشروع جديد متاح - ' . $this->product->name)
            ->greeting('مرحباً ' . $notifiable->name)
            ->line('تم إضافة مشروع جديد: ' . $this->product->name)
            ->line('الموقع: ' . $this->product->location)
                            ->line('السعر: ' . $this->product->price . ' ' . \App\Helpers\LanguageHelper::getSaudiRiyalSymbol())
            ->action('عرض المشروع', url('/products/' . $this->product->id))
            ->line('اكتشف المشاريع الجديدة على منصة مشاريع!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->title ?? 'مشروع جديد',
            'location' => $this->product->address ?? 'غير محدد',
            'price' => $this->product->price ?? 0,
            'type' => 'new_product_added',
            'message' => 'تم إضافة مشروع جديد: ' . ($this->product->title ?? 'مشروع جديد'),
            'action_url' => route('public.products.show', $this->product->id),
        ];
    }
}
