<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use NotificationChannels\WebPush\HasPushSubscriptions;

class Guest extends Model
{
    use HasPushSubscriptions;

    protected $fillable = ['endpoint'];
}
