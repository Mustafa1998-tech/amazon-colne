<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Add product to wishlist
     * POST /api/wishlist/add
     */
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $wishlist = Wishlist::firstOrCreate([
            'user_id' => Auth::id()
        ], [
            'products' => []
        ]);

        $productId = (int) $request->product_id;
        $products = $wishlist->products ?? [];

        if (in_array($productId, $products)) {
            return response()->json([
                'message' => 'Product already in wishlist'
            ], 400);
        }

        $wishlist->addProduct($productId);

        return response()->json([
            'message' => 'Product added to wishlist',
            'wishlist' => $wishlist
        ]);
    }

    /**
     * Get user's wishlist
     * GET /api/wishlist
     */
    public function index(): JsonResponse
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->first();
        
        if (!$wishlist || empty($wishlist->products)) {
            return response()->json([
                'products' => [],
                'count' => 0
            ]);
        }

        $products = Product::whereIn('id', $wishlist->products)->get();

        return response()->json([
            'products' => $products,
            'count' => count($products)
        ]);
    }

    /**
     * Remove product from wishlist
     * DELETE /api/wishlist/remove
     */
    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $wishlist = Wishlist::where('user_id', Auth::id())->first();

        if (!$wishlist) {
            return response()->json([
                'message' => 'Wishlist not found'
            ], 404);
        }

        $productId = (int) $request->product_id;
        $products = $wishlist->products ?? [];

        if (!in_array($productId, $products)) {
            return response()->json([
                'message' => 'Product not in wishlist'
            ], 400);
        }

        $wishlist->removeProduct($productId);

        return response()->json([
            'message' => 'Product removed from wishlist',
            'wishlist' => $wishlist
        ]);
    }

    /**
     * Clear entire wishlist
     * DELETE /api/wishlist/clear
     */
    public function clear(): JsonResponse
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->first();

        if ($wishlist) {
            $wishlist->update(['products' => []]);
        }

        return response()->json([
            'message' => 'Wishlist cleared'
        ]);
    }

    /**
     * Check if product is in wishlist
     * GET /api/wishlist/check/{productId}
     */
    public function check($productId): JsonResponse
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->first();
        
        $inWishlist = $wishlist && 
            in_array((int) $productId, $wishlist->products ?? []);

        return response()->json([
            'in_wishlist' => $inWishlist
        ]);
    }
}