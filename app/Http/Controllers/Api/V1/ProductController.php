<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = Product::with('variants')->paginate(20);
        return response()->json($products);
    }

    public function show(Product $product)
    {
        $product->load('variants');
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'price' => 'nullable|numeric',
            'vendor_id' => 'nullable|exists:vendors,id',
            'variants' => 'nullable|array',
            'variants.*.sku' => 'required|string|unique:product_variants,sku',
            'variants.*.price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }

        $product = $this->productService->createProduct($request->all());

        return response()->json($product, 201);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->all();
        $product = $this->productService->updateProduct($product, $data);

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted']);
    }

    public function importCsv(Request $request)
    {
       $request->validate([
        'file' => 'required|mimes:csv,txt|max:2048'
    ]);

        $path = $request->file('file')->store('imports');

        // Dispatch job (async)
        \App\Jobs\ImportProductsJob::dispatch($path, auth()->id());

        return response()->json([
            'message' => 'CSV uploaded. Import is processing in background.'
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2'
        ]);

        return response()->json(
            $this->productService->search($request->q)
        );
    }

}
