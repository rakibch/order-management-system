<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;

class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function update(Order $order, array $data): Order
    {
        $order->update($data);
        return $order;
    }

    public function findById(int $id): ?Order
    {
        return Order::with('items.productVariant')->find($id);
    }

    public function paginate(int $limit = 20)
    {
        return Order::with('items.productVariant')->paginate($limit);
    }

    public function loadItems(Order $order): Order
    {
        return $order->load('items.productVariant');
    }
}
