<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Create a new order
     * POST /api/order
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'shipping_address' => 'sometimes|string',
            'payment_method' => 'sometimes|string'
        ]);

        try {
            DB::beginTransaction();

            // Verify stock availability and calculate total
            $calculatedTotal = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['id']);
                
                if ($product->stock < $item['quantity']) {
                    return response()->json([
                        'message' => "Insufficient stock for product: {$product->name}"
                    ], 400);
                }

                $itemTotal = $product->price * $item['quantity'];
                $calculatedTotal += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal
                ];

                // Update product stock
                $product->decrement('stock', $item['quantity']);
            }

            // Verify the total price
            if (abs($calculatedTotal - $request->total_price) > 0.01) {
                return response()->json([
                    'message' => 'Total price mismatch'
                ], 400);
            }

            // Create the order
            $order = Order::create([
                'user_id' => Auth::id(),
                'items' => $orderItems,
                'total_price' => $calculatedTotal,
                'status' => Order::STATUS_PENDING,
                'shipping_address' => $request->shipping_address,
                'payment_method' => $request->payment_method ?? 'cash_on_delivery'
            ]);

            // Clear cart after successful order
            session()->forget('cart');

            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully',
                'order' => $order
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'message' => 'Failed to place order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's order history
     * GET /api/orders
     */
    public function history(): JsonResponse
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($orders);
    }

    /**
     * Get specific order details
     * GET /api/orders/{id}
     */
    public function show($id): JsonResponse
    {
        $order = Order::where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json($order);
    }

    /**
     * Update order status (Admin only)
     * PUT /api/orders/{id}/status
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order = Order::findOrFail($id);
        
        // Prevent status changes for cancelled or delivered orders
        if (in_array($order->status, [Order::STATUS_CANCELLED, Order::STATUS_DELIVERED])) {
            return response()->json([
                'message' => 'Cannot update status of cancelled or delivered orders'
            ], 400);
        }

        $order->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Order status updated successfully',
            'order' => $order
        ]);
    }

    /**
     * Cancel order
     * PUT /api/orders/{id}/cancel
     */
    public function cancel($id): JsonResponse
    {
        $order = Order::where('user_id', Auth::id())
            ->findOrFail($id);

        // Only allow cancellation for pending or processing orders
        if (!in_array($order->status, [Order::STATUS_PENDING, Order::STATUS_PROCESSING])) {
            return response()->json([
                'message' => 'Order cannot be cancelled at this stage'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Restore product stock
            foreach ($order->items as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->increment('stock', $item['quantity']);
                }
            }

            $order->update(['status' => Order::STATUS_CANCELLED]);

            DB::commit();

            return response()->json([
                'message' => 'Order cancelled successfully',
                'order' => $order
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'message' => 'Failed to cancel order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all orders (Admin only)
     * GET /api/admin/orders
     */
    public function adminIndex(Request $request): JsonResponse
    {
        $query = Order::with('user');

        if ($request->has('status') && $request->status) {
            $query->byStatus($request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json($orders);
    }
}