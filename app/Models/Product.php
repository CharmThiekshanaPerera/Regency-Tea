<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'gallery'      => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class);
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    /** Products added in the last 90 days — replaces the "New Arrivals" category. */
    public function scopeNewArrivals(Builder $q, int $days = 90): Builder
    {
        return $q->published()->where('published_at', '>=', now()->subDays($days));
    }

    public function scopeForBrand(Builder $q, string $slug): Builder
    {
        return $q->whereHas('brand', fn ($b) => $b->where('slug', $slug));
    }

    /** True once a price list has been imported (see Phase C). */
    public function getIsPurchasableAttribute(): bool
    {
        return $this->variants()->where('is_purchasable', true)->exists();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getUrlAttribute(): string
    {
        return route('product.show', $this->slug);
    }
}
