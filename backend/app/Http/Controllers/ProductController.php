<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of products with filtering and sorting
     * GET /api/products
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::query();

        // Search filter
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        // Category filter
        if ($request->has('category') && $request->category) {
            $query->byCategory($request->category);
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->sortByPrice('asc');
                    break;
                case 'price_desc':
                    $query->sortByPrice('desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $products = $query->paginate($request->get('per_page', 12));

        return response()->json($products);
    }

    /**
     * Display the specified product
     * GET /api/products/{id}
     */
    public function show($id): JsonResponse
    {
        $product = Product::findOrFail($id);
        
        // Get related products from same category
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return response()->json([
            'product' => $product,
            'related_products' => $relatedProducts
        ]);
    }

    /**
     * Store a newly created product (Admin only)
     * POST /api/products
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string|max:255',
            'images' => 'array',
            'images.*' => 'url'
        ]);

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category' => $request->category,
            'images' => $request->images ?? []
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

    /**
     * Update the specified product (Admin only)
     * PUT /api/products/{id}
     */
    public function update(Request $request, $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'category' => 'sometimes|string|max:255',
            'images' => 'sometimes|array',
            'images.*' => 'url'
        ]);

        $product->update($request->only([
            'name', 'description', 'price', 'stock', 'category', 'images'
        ]));

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ]);
    }

    /**
     * Remove the specified product (Admin only)
     * DELETE /api/products/{id}
     */
    public function destroy($id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }

    /**
     * Get product categories
     * GET /api/categories
     */
    public function categories(): JsonResponse
    {
        $categories = Product::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->pluck('category');

        return response()->json($categories);
    }
}