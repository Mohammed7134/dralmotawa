<?php

namespace App\Console\Commands;


use App\Models\Subscriber;
use App\Models\Wisdom;
use App\Services\MyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        $subscribers = Subscriber::all();
        $wisdom = Wisdom::inRandomOrder()->first()->text;
        foreach ($subscribers as $subscriber) {
            $parameter1 = json_encode(array(
                "type" => "text",
                "text" => $wisdom
            ));
            $myservice = new MyService;
            Log::debug(print_r($myservice->sendWhatsApp($subscriber, $parameter1, 'wisdom'), true));
        }
    }
}
