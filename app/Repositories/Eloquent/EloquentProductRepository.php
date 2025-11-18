<?php
namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return Product::with('variants')->paginate($perPage);
    }

    public function find(int $id): ?Product
    {
        return Product::with('variants')->find($id);
    }

    public function findBySku(string $sku): ?Product
    {
        return Product::where('sku', $sku)->first();
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product): bool
    {
        return (bool)$product->delete();
    }
}
