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

    /** Public-facing slides only — used by the homepage hero. */
    public function slides(): HasMany
    {
        return $this->hasMany(Slide::class)->where('is_active', true)->orderBy('sort');
    }

    /** Every slide regardless of status — used by the admin repeater, so inactive slides stay editable. */
    public function allSlides(): HasMany
    {
        return $this->hasMany(Slide::class)->orderBy('sort');
    }
}
