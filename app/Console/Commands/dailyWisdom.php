<?php

namespace App\Console\Commands;


use App\Models\Subscriber;
use App\Models\Wisdom;
use Illuminate\Console\Command;
use Twilio\Rest\Client;

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
        $sid    = getenv('TWILIO_SID');
        $token  = getenv('TWILIO_TOKEN');
        $twilio = new Client($sid, $token);

        $subscriber = Subscriber::all()->first();
        $subscriber->name = "Mohammed Almutawa";
        $subscriber->save();

        $wisdom = Wisdom::inRandomOrder()->first()->text;

        $message = $twilio->messages->create(
            "whatsapp:+" . $subscriber->telephone, // to 
            array(
                "from" => "whatsapp:+14155238886",
                "body" => $wisdom
            )
        );
    }
}
