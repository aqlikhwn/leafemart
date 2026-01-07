<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'description_image',
        'price',
        'image',
        'images',
        'variation',
        'stock',
        'featured',
        'active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'featured' => 'boolean',
        'active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function hasVariations(): bool
    {
        return $this->variations()->active()->count() > 0;
    }

    /**
     * Get total stock - from variations if has variations, otherwise from product stock
     */
    public function getTotalStockAttribute(): int
    {
        if ($this->hasVariations()) {
            return $this->variations()->active()->sum('stock');
        }
        return $this->stock;
    }

    /**
     * Check if product is in stock (considers variations)
     */
    public function isInStock(): bool
    {
        return $this->total_stock > 0;
    }

    /**
     * Get price range for products with variations
     */
    public function getPriceRangeAttribute(): ?string
    {
        if (!$this->hasVariations()) {
            return null;
        }
        
        $prices = $this->variations()->active()->whereNotNull('price')->pluck('price')->filter();
        
        if ($prices->isEmpty()) {
            return null;
        }
        
        $min = $prices->min();
        $max = $prices->max();
        
        if ($min == $max) {
            return 'RM ' . number_format($min, 2);
        }
        
        return 'RM ' . number_format($min, 2) . ' - RM ' . number_format($max, 2);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope to get products that are in stock (including those with variation stock)
     */
    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            // Products without variations that have stock > 0
            $q->where('stock', '>', 0)
              ->orWhereHas('variations', function ($vq) {
                  $vq->where('active', true)->where('stock', '>', 0);
              });
        });
    }
}


