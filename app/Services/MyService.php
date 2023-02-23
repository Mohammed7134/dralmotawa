<?php

namespace App\Services;

use App\Models\Subscriber;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Stream;
use Illuminate\Support\Facades\Log;

class MyService
{
    function sendWhatsApp(Subscriber $subscriber, array $parameters, String $templateName)
    {
        $client = new Client();
        $uri = getenv('WHATSAPP_URI');
        $headers = array(
            'Content-Type' => 'application/json',
            'Authorization' => getenv('WHATSAPP_TOKEN')
        );
        $component1 = json_encode(array(
            "type" => "body",
            "parameters" => $parameters
        ));
        $body = ["messaging_product" => "whatsapp", "to" => $subscriber->country_code . $subscriber->telephone, "type" => "template", "template" => ["name" => $templateName, "language" => ["code" => "ar"], "components" => [$component1]]];

        $request = new Request('POST', $uri, $headers);
        $stream = new Stream(fopen('php://temp', 'r+'));
        $stream->write(json_encode($body));
        $stream->rewind();
        $request = $request->withBody($stream);
        try {
            $response = $client->send($request);
            return $response;
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsJSON = json_decode($response->getBody()->getContents());
            return $responseBodyAsJSON;
        }
    }
}
