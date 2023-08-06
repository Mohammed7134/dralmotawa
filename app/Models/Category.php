<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'category_name', 'category_url'];
    public function wisdoms()
    {
        return $this->belongsToMany(Wisdom::class, 'category_wisdom', 'category_id', 'wisdom_id')
            ->withTimestamps(); // Enable automatic timestamps for the pivot table

    }
}
