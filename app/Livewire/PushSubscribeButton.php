<?php

namespace App\Livewire;

use App\Models\Guest;
use Livewire\Component;
use NotificationChannels\WebPush\PushSubscription;

class PushSubscribeButton extends Component
{
    public bool $subscribed = false;

    public function subscribe(string $endpoint, ?string $publicKey, ?string $authToken, string $encoding): void
    {
        $guest = Guest::firstOrCreate(['endpoint' => $endpoint]);
        $guest->updatePushSubscription($endpoint, $publicKey, $authToken, $encoding);
        $this->subscribed = true;
        $this->dispatch('notify', message: 'You are now subscribed to notifications!');
    }

    public function unsubscribe(string $endpoint): void
    {
        PushSubscription::where('endpoint', $endpoint)->delete();
        Guest::where('endpoint', $endpoint)->delete();
        $this->subscribed = false;
    }

    public function render()
    {
        return view('livewire.push-subscribe-button');
    }
}
