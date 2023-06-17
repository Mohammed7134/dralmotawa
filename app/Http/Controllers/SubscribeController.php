<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\User;
use App\Notifications\PushWisdom;
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
        $guest = User::firstOrCreate([
            'endpoint' => $endpoint
        ]);
        if ($guest) {
            $guest->updatePushSubscription($endpoint, $key, $token);
            Notification::send(User::all(), new PushWisdom(26564)); //test only
            return response()->json(['success' => true], 200);
        } else {
            return response()->json(['success' => false], 405);
        }
    }
    public function push()
    {
        Notification::send(User::all(), new PushWisdom(request()->id));
        Notification::send(Guest::all(), new PushWisdom(request()->id));
        return redirect()->back();
    }
}
