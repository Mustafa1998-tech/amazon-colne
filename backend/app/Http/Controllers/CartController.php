<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    /**
     * Add product to cart
     * POST /api/cart/add
     */
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check stock availability
        if ($product->stock < $request->quantity) {
            return response()->json([
                'message' => 'Insufficient stock available'
            ], 400);
        }

        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $request->quantity;
            
            // Check if total quantity exceeds stock
            if ($cart[$productId]['quantity'] > $product->stock) {
                $cart[$productId]['quantity'] = $product->stock;
            }
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->images[0] ?? null,
                'quantity' => $request->quantity
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'message' => 'Product added to cart',
            'cart' => $cart,
            'cart_count' => count($cart)
        ]);
    }

    /**
     * Get cart contents
     * GET /api/cart
     */
    public function index(): JsonResponse
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return response()->json([
            'cart' => $cart,
            'total' => $total,
            'cart_count' => count($cart)
        ]);
    }

    /**
     * Update cart item quantity
     * PUT /api/cart/update
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if (!isset($cart[$productId])) {
            return response()->json([
                'message' => 'Product not found in cart'
            ], 404);
        }

        $product = Product::findOrFail($productId);

        // Check stock availability
        if ($product->stock < $request->quantity) {
            return response()->json([
                'message' => 'Insufficient stock available'
            ], 400);
        }

        $cart[$productId]['quantity'] = $request->quantity;
        session()->put('cart', $cart);

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return response()->json([
            'message' => 'Cart updated',
            'cart' => $cart,
            'total' => $total,
            'cart_count' => count($cart)
        ]);
    }

    /**
     * Remove product from cart
     * DELETE /api/cart/remove
     */
    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer'
        ]);

        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return response()->json([
            'message' => 'Product removed from cart',
            'cart' => $cart,
            'total' => $total,
            'cart_count' => count($cart)
        ]);
    }

    /**
     * Clear entire cart
     * DELETE /api/cart/clear
     */
    public function clear(): JsonResponse
    {
        session()->forget('cart');

        return response()->json([
            'message' => 'Cart cleared',
            'cart' => [],
            'total' => 0,
            'cart_count' => 0
        ]);
    }
}