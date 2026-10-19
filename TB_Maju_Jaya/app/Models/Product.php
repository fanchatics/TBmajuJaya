<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    public const LOW_STOCK_THRESHOLD = 10;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'price', 'stock', 'unit', 'image',
    ];

    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /** Public URL of the uploaded image, or the bundled placeholder when there is none. */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }

        // Dynamic SVG or category-specific fallback icon if file is missing
        return asset('images/default-product.svg');
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock > 0 && $this->stock <= self::LOW_STOCK_THRESHOLD;
    }

    public function getInStockAttribute(): bool
    {
        return $this->stock > 0;
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $term
            ? $query->where(function (Builder $q) use ($term) {
                $q->where('name', 'like', "%{$term}%")->orWhere('description', 'like', "%{$term}%");
            })
            : $query;
    }

    public function scopeInCategory(Builder $query, $categoryId): Builder
    {
        return $categoryId ? $query->where('category_id', $categoryId) : $query;
    }
}
