<?php

namespace App\Notifications;

use App\Models\Wisdom;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class DailyWisdomNotification extends Notification
{
    public function via(object $notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush(object $notifiable, object $notification): WebPushMessage
    {
        $wisdom = Wisdom::inRandomOrder()->first();
        $preview = mb_substr($wisdom->text, 0, 100) . (mb_strlen($wisdom->text) > 100 ? '...' : '');

        return WebPushMessage::create()
            ->title('Your Daily Wisdom ✨')
            ->body($preview)
            ->icon('/icons/icon-192x192.png')
            ->badge('/icons/badge-72x72.png')
            ->data(['url' => url("/wisdom/{$wisdom->id}")]);
    }
}
