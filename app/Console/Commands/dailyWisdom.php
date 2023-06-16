<?php

namespace App\Console\Commands;

use App\Models\Guest;
use App\Models\Subscriber;
use App\Models\User;
use App\Models\Wisdom;
use App\Notifications\PushWisdom;
use App\Services\MyService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Guid\Guid;

class dailyWisdom extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Wisdom:day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'showing wisdom every time';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Notification::send(Guest::all(), new PushWisdom);
        // $subscribers = Subscriber::all();
        // $wisdom = Wisdom::where('text', 'NOT LIKE', '%\n%')->whereRaw('CHAR_LENGTH(text) <= ?', [950])->inRandomOrder()->first()->text;
        // // $wisdom = str_replace(array("\r\n", "\r", "\n"), " ", $wisdom);
        // $myservice = new MyService;
        // foreach ($subscribers as $subscriber) {
        //     $startDate = Carbon::parse($subscriber->payments->last()->created_at);
        //     $endDate = $startDate->addDays($subscriber->payments->last()->period);
        //     $remiderDate = $endDate->addDays(1);
        //     $paymentStatus = $subscriber->payments->last()->status;
        //     if ($endDate->isPast() == false) {
        //         if ($paymentStatus == "CAPTURED" ||  $paymentStatus == "FREE") {
        //             $parameter1 = json_encode(array(
        //                 "type" => "text",
        //                 "text" => $wisdom
        //             ));
        //             Log::debug("User first name is:");
        //             Log::debug($subscriber->first_name);
        //             Log::debug("first choice wisdom text is");
        //             Log::debug($wisdom);
        //             $response = $myservice->sendWhatsApp($subscriber, [$parameter1], 'wisdom');
        //             while (isset($response->error->message)) {
        //                 Log::debug("Searching for another wisdom");
        //                 $wisdom = Wisdom::where('text', 'NOT LIKE', '%\n%')->whereRaw('CHAR_LENGTH(text) <= ?', [950])->inRandomOrder()->first()->text;
        //                 Log::debug($wisdom);
        //                 $parameter1 = json_encode(array(
        //                     "type" => "text",
        //                     "text" => $wisdom
        //                 ));
        //                 $response = $myservice->sendWhatsApp($subscriber, [$parameter1], 'wisdom');
        //             }
        //         }
        //     } elseif ($remiderDate->isToday()) {
        //         if ($paymentStatus == "CAPTURED" ||  $paymentStatus == "FREE") {
        //             $parameter1 = json_encode(array(
        //                 "type" => "text",
        //                 "text" => "https://dralmutawa.com/renew-subscription/" . $subscriber->id . "?period=30"
        //             ));
        //             $parameter2 = json_encode(array(
        //                 "type" => "text",
        //                 "text" => "https://dralmutawa.com/renew-subscription/" . $subscriber->id . "?period=365"
        //             ));
        //             $response = $myservice->sendWhatsApp($subscriber, [$parameter1, $parameter2], 'renew_subscription_reminder');
        //             if (isset($response->error->message)) {
        //                 Log::debug("One renew subscription message was not sent to $subscriber->id due to error");
        //                 Log::debug($response->error->message);
        //             } else {
        //                 Log::debug("One renew subscription message sent successfully to $subscriber->id");
        //             }
        //         }
        //     }
        // }
    }
}
