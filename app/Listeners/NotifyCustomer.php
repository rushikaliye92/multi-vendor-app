<?php

namespace App\Listeners;

use App\Events\PaymentSucceeded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyCustomer implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(PaymentSucceeded $event)
    {
        $payment = $event->payment;
        $order = $payment->order;
        $user = $order->user;

        Log::info("Payment succeeded for Order #{$order->id} (User: {$user->email})");

        Mail::raw(
            "Dear {$user->name}, your payment for Order #{$order->id} has been received successfully.",
            function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Payment Confirmation');
            }
        );
    }
}
