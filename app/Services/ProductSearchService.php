<?php
namespace App\Services;

use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductSearchService
{
    protected $products;

    public function __construct(ProductRepositoryInterface $products)
    {
        $this->products = $products;
    }

    public function search(string $keyword)
    {
        return $this->products->search($keyword);
    }
}
