<?php

namespace App\Services;

use App\Models\Subscriber;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Stream;

class MyService
{
    function sendWhatsApp(Subscriber $subscriber, String $parameter1, String $templateName)
    {
        $client = new Client();
        $uri = getenv('WHATSAPP_URI');
        $headers = array(
            'Content-Type' => 'application/json',
            'Authorization' => getenv('WHATSAPP_TOKEN')
        );
        $component1 = json_encode(array(
            "type" => "body",
            "parameters" => [$parameter1]
        ));
        $body = ["messaging_product" => "whatsapp", "to" => $subscriber->telephone, "type" => "template", "template" => ["name" => $templateName, "language" => ["code" => "ar"], "components" => [$component1]]];

        $request = new Request('POST', $uri, $headers);
        $stream = new Stream(fopen('php://temp', 'r+'));
        $stream->write(json_encode($body));
        $stream->rewind();
        $request = $request->withBody($stream);
        $response = $client->send($request);
    }
}
