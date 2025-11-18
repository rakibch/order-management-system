<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Services\ProductService;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    protected $orders;
    protected $productService;

    public function __construct(
        OrderRepositoryInterface $orders,
        ProductService $productService
    ) {
        $this->orders = $orders;
        $this->productService = $productService;
    }

    /**
     * Create an order with items
     */
    public function createOrder(array $data)
    {
        return DB::transaction(function () use ($data) {

            $order = $this->orders->create([
                'user_id'         => $data['user_id'],
                'vendor_id'       => $data['vendor_id'],
                'subtotal'        => 0,
                'shipping'        => $data['shipping'] ?? 0,
                'tax'             => $data['tax'] ?? 0,
                'total'           => 0,
                'status'          => 'pending',
                'billing_address' => $data['billing_address'] ?? null,
                'shipping_address'=> $data['shipping_address'] ?? null,
            ]);

            $subtotal = 0;

            foreach ($data['items'] as $itemData) {

                $variant  = ProductVariant::findOrFail($itemData['variant_id']);
                $quantity = $itemData['quantity'] ?? 1;

                // Deduct stock
                $this->productService->deductStock($variant, $quantity);

                $lineTotal = $variant->price * $quantity;
                $subtotal += $lineTotal;

                OrderItem::create([
                    'order_id'            => $order->id,
                    'product_variant_id'  => $variant->id,
                    'product_name'        => $variant->product->name,
                    'unit_price'          => $variant->price,
                    'quantity'            => $quantity,
                    'total_price'         => $lineTotal,
                ]);
            }

            // Update totals
            $order = $this->orders->update($order, [
                'subtotal' => $subtotal,
                'total'    => $subtotal + ($data['shipping'] ?? 0) + ($data['tax'] ?? 0),
                'status'   => 'processing',
            ]);

            return $this->orders->loadItems($order);
        });
    }

    /**
     * Cancel an order and restore stock
     */
    public function cancelOrder($order)
    {
        return DB::transaction(function () use ($order) {

            foreach ($order->items as $item) {
                $this->productService->restoreStock(
                    $item->productVariant,
                    $item->quantity
                );
            }

            $order = $this->orders->update($order, [
                'status' => 'cancelled'
            ]);

            return $this->orders->loadItems($order);
        });
    }

    /**
     * List orders
     */
    public function listOrders(int $limit = 20)
    {
        return $this->orders->paginate($limit);
    }

    /**
     * View single order
     */
    public function getOrder(int $id)
    {
        return $this->orders->findById($id);
    }
}
