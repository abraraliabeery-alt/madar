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
            ->subject('عقار جديد متاح - ' . $this->product->name)
            ->greeting('مرحباً ' . $notifiable->name)
            ->line('تم إضافة عقار جديد: ' . $this->product->name)
            ->line('الموقع: ' . $this->product->location)
                            ->line('السعر: ' . $this->product->price . ' ' . \App\Helpers\LanguageHelper::getSaudiRiyalSymbol())
            ->action('عرض العقار', url('/products/' . $this->product->id))
            ->line('اكتشف العقارات الجديدة على منصة عقار!');
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
            'product_name' => $this->product->name,
            'location' => $this->product->location,
            'price' => $this->product->price,
            'type' => 'new_product_added',
            'message' => 'تم إضافة عقار جديد: ' . $this->product->name,
        ];
    }
}
