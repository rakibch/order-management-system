<?php

namespace App\Listeners;

use App\Events\StockAdjusted;
use App\Jobs\LowStockAlertJob;

class SendLowStockNotification
{
    public function handle(StockAdjusted $event): void
    {
        $variant = $event->variant;

        if ($variant->inventory->stock <= $variant->inventory->low_stock_threshold) {
            LowStockAlertJob::dispatch($variant);
        }
    }
}
