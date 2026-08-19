<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasTranslations;

    protected $guarded = [];

    public array $translatable = ['title', 'excerpt', 'body', 'meta_title', 'meta_description'];

    protected function casts(): array
    {
        return [
            'blocks'        => 'array',
            'published_at'  => 'datetime',
            'is_indexable'  => 'boolean',
        ];
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
