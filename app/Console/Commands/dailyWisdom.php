<?php

namespace App\Console\Commands;


use App\Models\Subscriber;
use App\Models\Wisdom;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Stream;

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
            $client = new Client();
            $uri = 'https://graph.facebook.com/v15.0/100375426320424/messages';
            $headers = array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer EABR9lTePtecBAOdjMOitf7hCPOXXDbPZBN06O8GJ0Wy87wdLLJV1ZBo5ygIEgeo0ZC2ev3a4J264gRaLKcncRTSDqMbGpu1Ic81x7SPR4YGo8feeB8y0MVFstadl2TX6qoHi6HZBxvPqScIBTkcbiJPEuxmJmEVk8bxkTDIfGJvlphZC5szmD1RzXzq6xpZCOADZCt2UmIVfCuMCFJFrxG3'
            );
            $parameter1 = json_encode(array(
                "type" => "text",
                "text" => $wisdom
            ));
            $component1 = json_encode(array(
                "type" => "body",
                "parameters" => [$parameter1]
            ));
            $body = ["messaging_product" => "whatsapp", "to" => $subscriber->telephone, "type" => "template", "template" => ["name" => "wisdom", "language" => ["code" => "ar"], "components" => [$component1]]];

            $request = new Request('POST', $uri, $headers);
            $stream = new Stream(fopen('php://temp', 'r+'));
            $stream->write(json_encode($body));
            $stream->rewind();
            $request = $request->withBody($stream);
            $response = $client->send($request);
        }
    }
}
