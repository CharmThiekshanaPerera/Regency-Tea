<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['is_purchasable' => 'boolean'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getPriceAttribute(): ?float
    {
        return $this->price_cents === null ? null : $this->price_cents / 100;
    }

    public function getFormattedPriceAttribute(): string
    {
        if ($this->price_cents === null) {
            return __('Price on request');
        }

        return ($this->currency ?? 'USD').' '.number_format($this->price / 1, 2);
    }
}
