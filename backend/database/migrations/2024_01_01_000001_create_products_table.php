<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->json('images');
            $table->integer('stock')->default(0);
            $table->string('category');
            $table->boolean('featured')->default(false);
            $table->decimal('rating', 2, 1)->default(0);
            $table->integer('reviews_count')->default(0);
            $table->timestamps();

            $table->index(['category']);
            $table->index(['featured']);
            $table->index(['price']);
            $table->index(['stock']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};