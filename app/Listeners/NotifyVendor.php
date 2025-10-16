<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class NotifyVendor extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification’s delivery channels.
     */
    public function via($notifiable)
    {
        // You can also include 'database' or 'broadcast' if needed
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('🛒 New Order Received - Order #' . $this->order->id)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You have received a new order from ' . $this->order->user->name . '.')
            ->line('Total Amount: ₹' . number_format($this->order->total, 2))
            ->line('Payment Status: ' . ucfirst($this->order->payment->status))
            ->action('View Order Details', url('/admin/orders/' . $this->order->id))
            ->line('Thank you for selling with us!');
    }

    /**
     * Optional — store in database if using notifications table.
     */
    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'total' => $this->order->total,
            'user' => $this->order->user->name,
        ];
    }
}
