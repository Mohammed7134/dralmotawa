<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class WisdomPushNotification extends Notification
{
    public function __construct(
        private readonly string $titleText,
        private readonly string $bodyText,
    ) {}

    public function via(object $notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush(object $notifiable, object $notification): WebPushMessage
    {
        return WebPushMessage::create()
            ->title($this->titleText)
            ->body($this->bodyText)
            ->icon('/icons/icon-192x192.png')
            ->badge('/icons/badge-72x72.png')
            ->action('Read Wisdom', 'read')
            ->data(['url' => url('/')]);
    }
}
