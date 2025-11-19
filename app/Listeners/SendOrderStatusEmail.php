<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\OrderStatusChanged;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusMail;

class SendOrderStatusEmail
{
    public function handle(OrderStatusChanged $event)
    {
        Mail::to($event->order->user->email)
            ->queue(new OrderStatusMail($event->order, $event->newStatus));
    }
}
