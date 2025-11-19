<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Exception;

class ProductService
{
    protected ProductRepositoryInterface $products;
    protected ProductSearchService $searchService;
    public function __construct(ProductRepositoryInterface $products, ProductSearchService $searchService)
    {
        $this->products = $products;
        $this->searchService = $searchService;
    }

    /**
     * List products (delegated to repository)
     */
    public function listProducts(int $perPage = 20)
    {
        return $this->products->paginate($perPage);
    }

    /**
     * Create product with variants
     */
    public function createProduct(array $data)
    {
        return DB::transaction(function () use ($data) {

            // Create product through repository
            $product = $this->products->create([
                'sku'         => $data['sku'],
                'name'        => $data['name'],
                'description' => $data['description'] ?? null,
                'vendor_id'   => $data['vendor_id'] ?? null,
                'price'       => $data['price'] ?? 0,
                'attributes'  => $data['attributes'] ?? null,
                'active'      => $data['active'] ?? true,
            ]);

            // Create variants (direct model usage is OK — variants belong to product)
            if (!empty($data['variants'])) {
                foreach ($data['variants'] as $variant) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'sku'        => $variant['sku'],
                        'price'      => $variant['price'],
                        'attributes' => $variant['attributes'] ?? null,
                        'active'     => $variant['active'] ?? true,
                    ]);
                }
            }

            return $product->load('variants');
        });
    }

    /**
     * Update product with variants
     */
    public function updateProduct(Product $product, array $data)
    {
        return DB::transaction(function () use ($product, $data) {

            // Update product through repository
            $this->products->update($product, [
                'name'        => $data['name'] ?? $product->name,
                'description' => $data['description'] ?? $product->description,
                'price'       => $data['price'] ?? $product->price,
                'attributes'  => $data['attributes'] ?? $product->attributes,
                'active'      => $data['active'] ?? $product->active,
            ]);

            // Update or create variants
            if (!empty($data['variants'])) {
                foreach ($data['variants'] as $variantData) {

                    // If ID provided → update
                    if (!empty($variantData['id'])) {
                        $variant = ProductVariant::find($variantData['id']);
                        if ($variant) {
                            $variant->update($variantData);
                            continue;
                        }
                    }

                    // Otherwise create new variant
                    ProductVariant::create(array_merge(
                        $variantData,
                        ['product_id' => $product->id]
                    ));
                }
            }

            return $product->load('variants');
        });
    }

    /**
     * Deduct stock when order is placed
     */
    public function deductStock(ProductVariant $variant, int $quantity)
    {
        if ($variant->inventory->stock < $quantity) {
            throw new Exception("Insufficient stock for variant {$variant->sku}");
        }

        $variant->inventory->decrement('stock', $quantity);
        $variant->inventory->increment('reserved', $quantity);

        return $variant->inventory;
    }

    /**
     * Restore stock when order is cancelled
     */
    public function restoreStock(ProductVariant $variant, int $quantity)
    {
        $variant->inventory->increment('stock', $quantity);
        $variant->inventory->decrement('reserved', $quantity);

        return $variant->inventory;
    }

    public function import(string $filePath)
    {
        $csv = Reader::createFromPath(storage_path('app/' . $filePath), 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $row) {
            // Skip empty lines
            if (!isset($row['sku']) || empty(trim($row['sku']))) {
                continue;
            }

            // Skip duplicates
            if ($this->products->findBySku($row['sku'])) {
                continue;
            }

            $this->products->create([
                'sku'        => $row['sku'],
                'name'       => $row['name'],
                'price'      => $row['price'],
                'vendor_id'  => $row['vendor_id'] ?? null,
                'description'=> $row['description'] ?? null,
                'attributes' => json_decode($row['attributes'] ?? '{}', true),
            ]);
        }
    }

    public function search(string $keyword)
    {
        return $this->searchService->search($keyword);
    }


}
