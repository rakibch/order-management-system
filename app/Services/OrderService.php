<?php
namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use App\Services\ProductService;
use Exception;

class OrderService
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Create an order with items
     */
    public function createOrder(array $data)
    {
        return DB::transaction(function () use ($data) {
            $order = Order::create([
                'user_id' => $data['user_id'],
                'vendor_id' => $data['vendor_id'],
                'status' => 'pending',
                'subtotal' => 0,
                'shipping' => $data['shipping'] ?? 0,
                'tax' => $data['tax'] ?? 0,
                'total' => 0,
                'billing_address' => $data['billing_address'] ?? null,
                'shipping_address' => $data['shipping_address'] ?? null,
            ]);

            $subtotal = 0;

            foreach ($data['items'] as $itemData) {
                $variant = ProductVariant::findOrFail($itemData['variant_id']);
                $quantity = $itemData['quantity'] ?? 1;

                // Deduct stock
                $this->productService->deductStock($variant, $quantity);

                $totalPrice = $variant->price * $quantity;
                $subtotal += $totalPrice;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $variant->id,
                    'product_name' => $variant->product->name,
                    'unit_price' => $variant->price,
                    'quantity' => $quantity,
                    'total_price' => $totalPrice,
                ]);
            }

            $order->update([
                'subtotal' => $subtotal,
                'total' => $subtotal + ($data['shipping'] ?? 0) + ($data['tax'] ?? 0),
                'status' => 'processing',
            ]);

            return $order->load('items');
        });
    }

    public function cancelOrder(Order $order)
    {
        return DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $this->productService->restoreStock($item->productVariant, $item->quantity);
            }

            $order->update(['status' => 'cancelled']);
            return $order;
        });
    }
}
