<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'session_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getTotalAttribute()
    {
        return $this->quantity * $this->product->price;
    }

    public static function addItem($productId, $quantity = 1, $userId = null, $sessionId = null)
    {
        $cart = self::where('product_id', $productId)
                   ->when($userId, function($query) use ($userId) {
                       return $query->where('user_id', $userId);
                   })
                   ->when($sessionId && !$userId, function($query) use ($sessionId) {
                       return $query->where('session_id', $sessionId);
                   })
                   ->first();

        if ($cart) {
            $cart->quantity += $quantity;
            $cart->save();
        } else {
            $cart = self::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'session_id' => $sessionId
            ]);
        }

        return $cart;
    }

    public static function getCartItems($userId = null, $sessionId = null)
    {
        return self::with('product')
                  ->when($userId, function($query) use ($userId) {
                      return $query->where('user_id', $userId);
                  })
                  ->when($sessionId && !$userId, function($query) use ($sessionId) {
                      return $query->where('session_id', $sessionId);
                  })
                  ->get();
    }

    public static function clearCart($userId = null, $sessionId = null)
    {
        return self::when($userId, function($query) use ($userId) {
                      return $query->where('user_id', $userId);
                  })
                  ->when($sessionId && !$userId, function($query) use ($sessionId) {
                      return $query->where('session_id', $sessionId);
                  })
                  ->delete();
    }
}