<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use NotificationChannels\WebPush\PushSubscription;
use Illuminate\Http\Request;

class PushController extends Controller
{
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'endpoint'   => 'required|string|max:500',
            'publicKey'  => 'nullable|string',
            'authToken'  => 'nullable|string',
            'encoding'   => 'nullable|string',
        ]);

        $guest = Guest::firstOrCreate(['endpoint' => $data['endpoint']]);
        $guest->updatePushSubscription(
            $data['endpoint'],
            $data['publicKey'] ?? null,
            $data['authToken'] ?? null,
            $data['encoding'] ?? 'aesgcm',
        );

        return response()->json(['status' => 'subscribed']);
    }

    public function unsubscribe(Request $request)
    {
        $endpoint = $request->input('endpoint');
        PushSubscription::where('endpoint', $endpoint)->delete();
        Guest::where('endpoint', $endpoint)->delete();

        return response()->json(['status' => 'unsubscribed']);
    }
}
