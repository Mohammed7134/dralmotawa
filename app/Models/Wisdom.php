<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Wisdom extends Model
{
    protected $fillable = ['text', 'search_text', 'likes'];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_wisdom');
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where('search_text', 'like', "%{$term}%");
    }

    public function incrementLike(): void
    {
        $this->increment('likes');
    }
}
