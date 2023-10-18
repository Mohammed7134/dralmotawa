<?php

namespace App\Notifications;

use App\Models\Wisdom;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;
use Illuminate\Support\Str;

class PushWisdom extends Notification
{
    use Queueable;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }


    public function toWebPush($notifiable, $notification)
    {
        $wisdomId = empty(request()->id) ? 26647 : request()->id;
        $wisdom = Wisdom::where('id', '=', $wisdomId)->first();
        $body = Str::limit($wisdom->text, 550, '...');
        $url = "https://dralmutawa.com/id/$wisdom->id?title=حكمة%20اليوم";
        return (new WebPushMessage)
            ->title('حكمة اليوم')
            ->icon('/images/logo.png')
            ->body($body)
            ->action('افتح التطبيق', 'notification_action')
            ->data(['url' => $url]);
    }


    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
