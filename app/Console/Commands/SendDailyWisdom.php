<?php

namespace App\Console\Commands;

use App\Models\Guest;
use App\Models\User;
use App\Notifications\DailyWisdomNotification;
use Illuminate\Console\Command;

class SendDailyWisdom extends Command
{
    protected $signature   = 'wisdom:send-daily';
    protected $description = 'Send a daily wisdom push notification to all subscribers';

    public function handle(): void
    {
        $notification = new DailyWisdomNotification();

        User::all()->each->notify($notification);
        Guest::all()->each->notify($notification);

        $this->info('Daily wisdom sent!');
    }
}
