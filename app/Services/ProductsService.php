<?php
namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductService
{
    /**
     * Create product with variants
     */
    public function createProduct(array $data)
    {
        return DB::transaction(function () use ($data) {
            $product = Product::create([
                'sku' => $data['sku'],
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'vendor_id' => $data['vendor_id'] ?? null,
                'price' => $data['price'] ?? 0,
                'attributes' => $data['attributes'] ?? null,
                'active' => $data['active'] ?? true,
            ]);

            if (!empty($data['variants'])) {
                foreach ($data['variants'] as $variant) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $variant['sku'],
                        'price' => $variant['price'],
                        'attributes' => $variant['attributes'] ?? null,
                        'active' => $variant['active'] ?? true,
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
            $product->update([
                'name' => $data['name'] ?? $product->name,
                'description' => $data['description'] ?? $product->description,
                'price' => $data['price'] ?? $product->price,
                'attributes' => $data['attributes'] ?? $product->attributes,    
                'active' => $data['active'] ?? $product->active,
            ]);

            if (!empty($data['variants'])) {
                foreach ($data['variants'] as $variantData) {
                    $variant = ProductVariant::find($variantData['id'] ?? 0);
                    if ($variant) {
                        $variant->update($variantData);
                    } else {
                        ProductVariant::create(array_merge($variantData, ['product_id' => $product->id]));
                    }
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
            throw new \Exception("Insufficient stock for variant {$variant->sku}");
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
}
