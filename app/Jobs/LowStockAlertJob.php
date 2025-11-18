<?php

namespace App\Jobs;

use App\Models\ProductVariant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class LowStockAlertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public ProductVariant $variant;

    public function __construct(ProductVariant $variant)
    {
        $this->variant = $variant;
    }

    public function handle(): void
    {
        // You can send mail, Slack message, or store logs here.
        Mail::raw(
            "Low stock alert!\nVariant SKU: {$this->variant->sku}\nRemaining stock: {$this->variant->inventory->stock}",
            function ($msg) {
                $msg->to('admin@example.com')->subject('Low Stock Alert');
            }
        );
    }
}
