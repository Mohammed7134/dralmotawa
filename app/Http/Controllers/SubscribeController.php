<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\User;
use App\Notifications\PushWisdom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        $guest = Guest::firstOrCreate([
            'endpoint' => $endpoint
        ]);
        if ($guest) {
            $guest->updatePushSubscription($endpoint, $key, $token);
            Notification::send(User::all(), new PushWisdom(26564));
            return response()->json(['success' => true], 200);
        } else {
            return response()->json(['success' => false], 405);
        }
    }
    public function push()
    {
        Notification::send(User::all(), new PushWisdom());
        // Notification::send(Guest::all(), new PushWisdom());
        return redirect()->back();
    }
}
