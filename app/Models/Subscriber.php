<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;
    protected $table = 'whatsapp_subscribers';

    public $timestamps = true;

    protected $fillable = [
        'country_code',
        'telephone'
    ];

    protected $hidden = [
        'id',
        'name'
    ];
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
