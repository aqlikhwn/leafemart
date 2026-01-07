<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'variation',
        'quantity',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getSubtotalAttribute(): float
    {
        $price = $this->product->price;
        
        // Use variation price if variation exists
        if ($this->variation) {
            $variation = $this->product->variations()->where('name', $this->variation)->first();
            if ($variation && $variation->price) {
                $price = $variation->price;
            }
        }
        
        return $this->quantity * $price;
    }
}

