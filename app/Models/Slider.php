<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Slider extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function slides(): HasMany
    {
        return $this->hasMany(Slide::class)->where('is_active', true)->orderBy('sort');
    }
}
