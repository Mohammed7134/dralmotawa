<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\User;
use App\Notifications\PushDemo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class SubscribeController extends Controller
{
    public function saveSubscription()
    {
        $this->validate(request(), [
            'endpoint'    => 'required',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required'
        ]);
        $endpoint = request()->endpoint;
        $token = request()->keys['auth'];
        $key = request()->keys['p256dh'];
        $user = Guest::firstOrCreate([
            'endpoint' => $endpoint
        ]);
        if ($user) {
            $user->updatePushSubscription($endpoint, $key, $token);
            return response()->json(['success' => true], 200);
        } else {
            return response()->json(['success' => false], 405);
        }
    }
    public function push()
    {
        Notification::send(Guest::all(), new PushDemo);
        return redirect()->back();
    }
}
