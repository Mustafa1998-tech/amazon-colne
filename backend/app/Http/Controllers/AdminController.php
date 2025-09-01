<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Product, User, Order};
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    /**
     * Get admin dashboard statistics
     * GET /api/admin/dashboard
     */
    public function dashboard(): JsonResponse
    {
        $stats = [
            'total_products' => Product::count(),
            'total_users' => User::where('is_admin', false)->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', Order::STATUS_PENDING)->count(),
            'total_revenue' => Order::where('status', Order::STATUS_DELIVERED)->sum('total_price'),
            'low_stock_products' => Product::where('stock', '<', 5)->count()
        ];

        // Recent orders
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Top selling products (based on completed orders)
        $topProducts = Product::select('products.*')
            ->selectRaw('COUNT(orders.id) as order_count')
            ->leftJoin('orders', function($join) {
                $join->whereRaw("JSON_SEARCH(orders.items, 'one', products.id, NULL, '$[*].product_id') IS NOT NULL")
                     ->where('orders.status', Order::STATUS_DELIVERED);
            })
            ->groupBy('products.id')
            ->orderBy('order_count', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'stats' => $stats,
            'recent_orders' => $recentOrders,
            'top_products' => $topProducts
        ]);
    }

    /**
     * Get all users (Admin only)
     * GET /api/admin/users
     */
    public function users(Request $request): JsonResponse
    {
        $query = User::query();

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json($users);
    }

    /**
     * Update user status (Admin only)
     * PUT /api/admin/users/{id}/status
     */
    public function updateUserStatus(Request $request, $id): JsonResponse
    {
        $request->validate([
            'is_admin' => 'required|boolean'
        ]);

        $user = User::findOrFail($id);
        
        // Prevent making yourself non-admin if you're the only admin
        if (!$request->is_admin && $user->is_admin && auth()->id() === $user->id) {
            $adminCount = User::where('is_admin', true)->count();
            if ($adminCount <= 1) {
                return response()->json([
                    'message' => 'Cannot remove admin privileges from the only admin user'
                ], 400);
            }
        }

        $user->update(['is_admin' => $request->is_admin]);

        return response()->json([
            'message' => 'User status updated successfully',
            'user' => $user
        ]);
    }

    /**
     * Delete user (Admin only)
     * DELETE /api/admin/users/{id}
     */
    public function deleteUser($id): JsonResponse
    {
        $user = User::findOrFail($id);

        // Prevent deleting yourself
        if (auth()->id() === $user->id) {
            return response()->json([
                'message' => 'Cannot delete your own account'
            ], 400);
        }

        // Prevent deleting the only admin
        if ($user->is_admin) {
            $adminCount = User::where('is_admin', true)->count();
            if ($adminCount <= 1) {
                return response()->json([
                    'message' => 'Cannot delete the only admin user'
                ], 400);
            }
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }

    /**
     * Get low stock products
     * GET /api/admin/low-stock
     */
    public function lowStockProducts(Request $request): JsonResponse
    {
        $threshold = $request->get('threshold', 5);
        
        $products = Product::where('stock', '<', $threshold)
            ->orderBy('stock', 'asc')
            ->get();

        return response()->json($products);
    }

    /**
     * Bulk update product stock
     * PUT /api/admin/products/stock
     */
    public function bulkUpdateStock(Request $request): JsonResponse
    {
        $request->validate([
            'updates' => 'required|array',
            'updates.*.id' => 'required|exists:products,id',
            'updates.*.stock' => 'required|integer|min:0'
        ]);

        foreach ($request->updates as $update) {
            Product::where('id', $update['id'])
                ->update(['stock' => $update['stock']]);
        }

        return response()->json([
            'message' => 'Stock updated successfully'
        ]);
    }
}