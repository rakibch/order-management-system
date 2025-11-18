<?php

namespace App\Repositories\Contracts;

use App\Models\Order;

interface OrderRepositoryInterface
{
    public function create(array $data): Order;

    public function update(Order $order, array $data): Order;

    public function findById(int $id): ?Order;

    public function paginate(int $limit = 20);

    public function loadItems(Order $order): Order;
}
