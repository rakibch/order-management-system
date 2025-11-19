<?php
namespace App\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Product;

interface ProductRepositoryInterface
{
    public function paginate(int $perPage = 20): LengthAwarePaginator;
    public function find(int $id): ?Product;
    public function findBySku(string $sku): ?Product;
    public function create(array $data): Product;
    public function update(Product $product, array $data): Product;
    public function delete(Product $product): bool;
    public function search(string $keyword, int $limit = 20);

    // public function create(array $data);
    // public function findBySku(string $sku);
}
