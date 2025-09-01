<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Products table
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description');
                $table->decimal('price', 10, 2);
                $table->json('images')->nullable();
                $table->integer('stock')->default(0);
                $table->string('category')->nullable();
                $table->timestamps();
                
                // Indexes for better performance
                $table->index(['category']);
                $table->index(['price']);
                $table->index(['stock']);
            });
        }

        // Orders table
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->json('items');
                $table->decimal('total_price', 10, 2);
                $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])
                      ->default('pending');
                $table->text('shipping_address')->nullable();
                $table->string('payment_method')->default('cash_on_delivery');
                $table->timestamps();
                
                // Indexes
                $table->index(['user_id', 'status']);
                $table->index(['status']);
                $table->index(['created_at']);
            });
        }

        // Wishlists table
        if (!Schema::hasTable('wishlists')) {
            Schema::create('wishlists', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->json('products')->nullable();
                $table->timestamps();
                
                // Unique constraint
                $table->unique('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
    }
};