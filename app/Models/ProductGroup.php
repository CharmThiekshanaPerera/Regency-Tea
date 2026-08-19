<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class ProductGroup extends Model
{
    use HasTranslations;

    protected $guarded = [];

    public array $translatable = ['name'];

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class)->orderBy('sort')->orderBy('name');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
