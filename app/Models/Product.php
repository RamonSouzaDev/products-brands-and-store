<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'brand_id',
        'category_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    /**
     * Get the brand that owns the product.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope a query to search products by name.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where('name', 'like', "%{$search}%");
    }

    /**
     * Scope a query to filter products by categories.
     */
    public function scopeFilterByCategories(Builder $query, array $categoryIds): Builder
    {
        if (empty($categoryIds)) {
            return $query;
        }

        return $query->whereIn('category_id', $categoryIds);
    }

    /**
     * Scope a query to filter products by brands.
     */
    public function scopeFilterByBrands(Builder $query, array $brandIds): Builder
    {
        if (empty($brandIds)) {
            return $query;
        }

        return $query->whereIn('brand_id', $brandIds);
    }
}
