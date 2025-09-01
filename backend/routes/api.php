<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    ProductController,
    CartController,
    OrderController,
    WishlistController,
    AdminController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Product routes (public)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/categories', [ProductController::class, 'categories']);

// Cart routes (session-based)
Route::prefix('cart')->group(function () {
    Route::post('/add', [CartController::class, 'add']);
    Route::get('/', [CartController::class, 'index']);
    Route::put('/update', [CartController::class, 'update']);
    Route::delete('/remove', [CartController::class, 'remove']);
    Route::delete('/clear', [CartController::class, 'clear']);
});

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    
    // Authentication routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::delete('/account', [AuthController::class, 'deleteAccount']);
    
    // Order routes
    Route::prefix('orders')->group(function () {
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/', [OrderController::class, 'history']);
        Route::get('/{id}', [OrderController::class, 'show']);
        Route::put('/{id}/cancel', [OrderController::class, 'cancel']);
    });
    
    // Wishlist routes
    Route::prefix('wishlist')->group(function () {
        Route::post('/add', [WishlistController::class, 'add']);
        Route::get('/', [WishlistController::class, 'index']);
        Route::delete('/remove', [WishlistController::class, 'remove']);
        Route::delete('/clear', [WishlistController::class, 'clear']);
        Route::get('/check/{productId}', [WishlistController::class, 'check']);
    });
    
    // Admin routes (require admin privileges)
    Route::middleware('admin')->prefix('admin')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        
        // Product management
        Route::prefix('products')->group(function () {
            Route::post('/', [ProductController::class, 'store']);
            Route::put('/{id}', [ProductController::class, 'update']);
            Route::delete('/{id}', [ProductController::class, 'destroy']);
            Route::get('/low-stock', [AdminController::class, 'lowStockProducts']);
            Route::put('/stock', [AdminController::class, 'bulkUpdateStock']);
        });
        
        // Order management
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'adminIndex']);
            Route::put('/{id}/status', [OrderController::class, 'updateStatus']);
        });
        
        // User management
        Route::prefix('users')->group(function () {
            Route::get('/', [AdminController::class, 'users']);
            Route::put('/{id}/status', [AdminController::class, 'updateUserStatus']);
            Route::delete('/{id}', [AdminController::class, 'deleteUser']);
        });
    });
});

// Middleware for admin check
Route::middleware(['auth:sanctum'])->group(function() {
    // Add custom middleware if needed
});

/*
|--------------------------------------------------------------------------
| API Documentation
|--------------------------------------------------------------------------
|
| Authentication Endpoints:
| POST   /api/register        - Register new user
| POST   /api/login           - Login user
| POST   /api/logout          - Logout user (Auth required)
| GET    /api/user            - Get authenticated user (Auth required)
| PUT    /api/profile         - Update user profile (Auth required)
| DELETE /api/account         - Delete user account (Auth required)
|
| Product Endpoints:
| GET    /api/products         - List products with filters
| GET    /api/products/{id}    - Get product details
| GET    /api/categories       - Get product categories
| POST   /api/admin/products  - Create product (Admin required)
| PUT    /api/admin/products/{id} - Update product (Admin required)
| DELETE /api/admin/products/{id} - Delete product (Admin required)
|
| Cart Endpoints:
| POST   /api/cart/add        - Add product to cart
| GET    /api/cart            - Get cart contents
| PUT    /api/cart/update     - Update cart item quantity
| DELETE /api/cart/remove     - Remove item from cart
| DELETE /api/cart/clear      - Clear entire cart
|
| Order Endpoints:
| POST   /api/orders          - Create new order (Auth required)
| GET    /api/orders          - Get user order history (Auth required)
| GET    /api/orders/{id}     - Get specific order (Auth required)
| PUT    /api/orders/{id}/cancel - Cancel order (Auth required)
| GET    /api/admin/orders    - Get all orders (Admin required)
| PUT    /api/admin/orders/{id}/status - Update order status (Admin required)
|
| Wishlist Endpoints:
| POST   /api/wishlist/add    - Add product to wishlist (Auth required)
| GET    /api/wishlist        - Get user wishlist (Auth required)
| DELETE /api/wishlist/remove - Remove product from wishlist (Auth required)
| DELETE /api/wishlist/clear  - Clear wishlist (Auth required)
| GET    /api/wishlist/check/{id} - Check if product in wishlist (Auth required)
|
| Admin Endpoints:
| GET    /api/admin/dashboard - Get admin dashboard stats (Admin required)
| GET    /api/admin/users     - Get all users (Admin required)
| PUT    /api/admin/users/{id}/status - Update user admin status (Admin required)
| DELETE /api/admin/users/{id} - Delete user (Admin required)
| GET    /api/admin/products/low-stock - Get low stock products (Admin required)
| PUT    /api/admin/products/stock - Bulk update product stock (Admin required)
|
*/