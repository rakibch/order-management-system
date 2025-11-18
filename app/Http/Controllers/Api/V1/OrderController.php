<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $orders = Order::with('items.productVariant')->paginate(20);
        return response()->json($orders);
    }

    public function show(Order $order)
    {
        $order->load('items.productVariant');
        return response()->json($order);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'vendor_id' => 'required|exists:vendors,id',
            'shipping' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'billing_address' => 'nullable|array',
            'shipping_address' => 'nullable|array',
            'items' => 'required|array|min:1',
            'items.*.variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }

        $order = $this->orderService->createOrder($request->all());

        return response()->json($order, 201);
    }

    public function cancel(Order $order)
    {
        $order = $this->orderService->cancelOrder($order);

        return response()->json(['message' => 'Order cancelled', 'order' => $order]);
    }
}
