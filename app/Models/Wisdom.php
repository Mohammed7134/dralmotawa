<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wisdom extends Model
{
    use HasFactory;
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_wisdom', 'wisdom_id', 'category_id')
            ->withTimestamps(); // Enable automatic timestamps for the pivot table

    }
}
