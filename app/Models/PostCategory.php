<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class PostCategory extends Model
{
    use HasTranslations;

    protected $guarded = [];

    public array $translatable = ['name', 'description'];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_category_post');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
