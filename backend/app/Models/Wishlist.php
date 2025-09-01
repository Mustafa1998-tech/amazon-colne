<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'products'
    ];

    protected $casts = [
        'products' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function addProduct($productId)
    {
        $products = $this->products ?? [];
        if (!in_array($productId, $products)) {
            $products[] = $productId;
            $this->products = $products;
            $this->save();
        }
        return $this;
    }

    public function removeProduct($productId)
    {
        $products = $this->products ?? [];
        $this->products = array_values(array_filter($products, function($id) use ($productId) {
            return $id != $productId;
        }));
        $this->save();
        return $this;
    }
}