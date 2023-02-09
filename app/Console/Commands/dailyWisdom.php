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

        $sid    = getenv('TWILIO_SID');
        $token  = getenv('TWILIO_TOKEN');
        $twilio = new Client($sid, $token);

        $subscriber = Subscriber::all()->first();

        $wisdom = Wisdom::inRandomOrder()->first()->text;

        $message = $twilio->messages->create(
            "whatsapp:+" . $subscriber->telephone, // to 
            array(
                "from" => "whatsapp:+96597132849",
                "body" => $wisdom
            )
        );
        $client = new Client();
        $uri = 'https://graph.facebook.com/v15.0/116169031385897/messages';
        $headers = array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer EABR9lTePtecBABRLMiYHsajFAZB1vOmb2MykRoReAeQNGWkSZCuRIZAUFaLx0BG0I1ZArEKYi04y0KY5ZAyq75gQLYDzFrxD6VZCcgxb4qLRbmJzj13xroj6BZAXajbwLx6lLHmpE4FNZB6wmsqngxGUdCcBQKn1WgtQQcIsShAcN4wOyzfocptuHUhvKR2reSkA0p59aM1JyBIe5rIbhfcI'
        );
        $body = ["messaging_product" => "whatsapp", "to" => "96597134776", "type" => "template", "template" => ["name" => "hello_world", "language" => ["code" => "en_US"]]];

        $request = new Request('POST', $uri, $headers);
        $stream = new Stream(fopen('php://temp', 'r+'));
        $stream->write(json_encode($body));
        $stream->rewind();
        $request = $request->withBody($stream);
        $response = $client->send($request);
    }
}
