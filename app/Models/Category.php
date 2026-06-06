<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = ['category_name', 'category_url'];

    public function wisdoms(): BelongsToMany
    {
        return $this->belongsToMany(Wisdom::class, 'category_wisdom');
    }

    public function getRouteKeyName(): string
    {
        return 'category_url';
    }
}
