<?php

namespace App\Services;

use App\Models\ProductVariant;
use App\Events\StockAdjusted;

class InventoryService
{
    public function checkLowStock(ProductVariant $variant)
    {
        return $variant->inventory->stock <= $variant->inventory->low_stock_threshold;
    }

    public function adjustStock(ProductVariant $variant, int $quantity, bool $reserve = true)
    {
        if ($reserve) {
            $variant->inventory->decrement('stock', $quantity);
            $variant->inventory->increment('reserved', $quantity);
        } else {
            $variant->inventory->increment('stock', $quantity);
            $variant->inventory->decrement('reserved', $quantity);
        }

        // Fire event (Listener will handle low-stock logic)
        StockAdjusted::dispatch($variant);
    }
}
