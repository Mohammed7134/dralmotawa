<?php

namespace App\Services;

use App\Models\Subscriber;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Stream;

class MyService
{
    public function adjustLineBreaks($wisdom, $twitter)
    {
        if ($twitter) {
            str_replace(['&nbsp;', '/\xc2\xa0/', '\\u00a0"'], " ", $wisdom);
            str_replace(array("\r\n", "\r", "\n"), "<br />", $wisdom);
            $wisdom = nl2br($wisdom, true);
            $wisdom = str_replace("<br />", "%0A", $wisdom);
            echo $wisdom;
        } else {
            str_replace(['&nbsp;', '/\xc2\xa0/', '\\u00a0"'], " ", $wisdom);
            str_replace(array("\r\n", "\r", "\n"), "<br />", $wisdom);
            str_replace("<br>", "<br />", $wisdom);
            echo nl2br($wisdom, false);
        }
    }
    public function sendWhatsApp(Subscriber $subscriber, String $parameter1, String $templateName)
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
