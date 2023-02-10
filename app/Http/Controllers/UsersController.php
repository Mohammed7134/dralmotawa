<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Stream;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
// use Twilio\Rest\Client;

class UsersController extends Controller
{
    function login()
    {
        session(['url.intended' => url()->previous()]);
        return view("users.login");
    }
    function signout()
    {
        auth()->logout();
        session()->flush();
        return back();
    }
    function signin()
    {
        $validatedData = request()->validate([
            'name' => 'required',
            'password' => 'required|min:5'
        ]);
        if (auth()->attempt(['name' => request()->name,  'password' => request()->password], true)) {
            return redirect()->intended();
        } else {
            return back();
        }
    }
    function newSubscriber()
    {
        // Validate the form data
        $validatedData = request()->validate([
            'name' => 'required',
            'telephone' => 'required|numeric|min:6',
        ]);
        $exists = Subscriber::where('telephone', '=', request()->countryCode . request()->telephone)->get();
        if (count($exists) == 0) {
            $subscriber = new Subscriber();
            $subscriber->name = request()->name;
            $subscriber->telephone = request()->countryCode .  request()->telephone;
            $subscriber->otp = mt_rand(100000, 999999);
            $subscriber->otp_expiry = time() + 60;
            $client = new Client();
            $uri = 'https://graph.facebook.com/v15.0/100375426320424/messages';
            $headers = array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer EABR9lTePtecBAOdjMOitf7hCPOXXDbPZBN06O8GJ0Wy87wdLLJV1ZBo5ygIEgeo0ZC2ev3a4J264gRaLKcncRTSDqMbGpu1Ic81x7SPR4YGo8feeB8y0MVFstadl2TX6qoHi6HZBxvPqScIBTkcbiJPEuxmJmEVk8bxkTDIfGJvlphZC5szmD1RzXzq6xpZCOADZCt2UmIVfCuMCFJFrxG3'
            );
            $to = $subscriber->telephone;
            $request = new Request('POST', $uri, $headers);
            $stream = new Stream(fopen('php://temp', 'r+'));
            $body = ["messaging_product" => "whatsapp", "to" => $to, "type" => "template", "template" => ["name" => "otp_message", "language" => ["code" => "ar"], "components" => [
                [
                    "type" => "body",
                    "parameters" => [
                        "type" => "text",
                        "text" => $subscriber->otp
                    ]
                ]
            ]]];
            $stream->write(json_encode($body));
            $stream->rewind();
            $request = $request->withBody($stream);
            $response = $client->send($request);

            $subscriber->save();

            return view('OTP')->with('telephone', request()->countryCode .  request()->telephone)->with('timer', $subscriber->otp_expiry)->with('name', request()->name);
        } else {
            return back()->with("message", "الرقم مسجل مسبقا");
        }
    }
    function resendOTP()
    {
        // Validate the form data
        $validatedData = request()->validate([
            'telephone' => 'required|numeric|min:6',
        ]);
        $user = Subscriber::where('telephone', '=', request()->telephone)->first();
        if ($user) {
            $user->otp = mt_rand(100000, 999999);
            $user->otp_expiry = time() + 60;
            $user->save();
            $client = new Client();
            $uri = 'https://graph.facebook.com/v15.0/100375426320424/messages';
            $headers = array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer EABR9lTePtecBAOdjMOitf7hCPOXXDbPZBN06O8GJ0Wy87wdLLJV1ZBo5ygIEgeo0ZC2ev3a4J264gRaLKcncRTSDqMbGpu1Ic81x7SPR4YGo8feeB8y0MVFstadl2TX6qoHi6HZBxvPqScIBTkcbiJPEuxmJmEVk8bxkTDIfGJvlphZC5szmD1RzXzq6xpZCOADZCt2UmIVfCuMCFJFrxG3'
            );
            $to = $user->telephone;
            $request = new Request('POST', $uri, $headers);
            $stream = new Stream(fopen('php://temp', 'r+'));
            $body = ["messaging_product" => "whatsapp", "to" => $to, "type" => "template", "template" => ["name" => "otp_message", "language" => ["code" => "ar"], "components" => [
                [
                    "type" => "body",
                    "parameters" => [
                        "type" => "text",
                        "text" => $user->otp
                    ]
                ]
            ]]];
            $stream->write(json_encode($body));
            $stream->rewind();
            $request = $request->withBody($stream);
            $response = $client->send($request);

            $result['error'] = false;
            $result['message'] = "تم الإرسال";
        } else {
            $result['error'] = true;
            $result['message'] = "خطأ في الادخال٬ يرجى إعادة المحاولة";
        }
        return json_encode($result);
    }

    function enteredOTP()
    {
        // Validate the form data
        $validatedData = request()->validate([
            'OTP' => 'required',
            'telephone' => 'required|numeric|min:7',
        ]);
        $user = Subscriber::where('telephone', '=', request()->telephone)->first();
        if ($user) {
            if ($user->otp == request()->OTP) {
                if (time() < $user->otp_expiry) {
                    $user->otp = null;
                    $user->otp_expiry = null;
                    $user->save();
                    $result['error'] = false;
                    $result['message'] = "تم الاشتراك بنجاح";
                } else {
                    $result['error'] = true;
                    $result['message'] = "خطأ في الادخال٬ انتهت صلاحية الرمز";
                }
            } else {
                $result['error'] = true;
                $result['message'] = "خطأ في الادخال٬ يرجى إعادة المحاولة";
            }
        } else {
            $result['error'] = true;
            $result['message'] = "الرقم مسجل مسبقا";
        }
        return json_encode($result);
    }
    function messageFromTwilio()
    {
        // validate that the request is coming from Twilio
        // $validator = Validator::make(request()->all(), [
        //     'AccountSid' => 'required',
        //     'MessageSid' => 'required',
        //     'Body' => 'required',
        //     'To' => 'required',
        //     'From' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return response('Invalid request', 400);
        // }
        $data = json_decode(request()->getContent());
        $inboundNotification = isset($data->entry[0]->changes[0]->value->messages[0]);
        if ($inboundNotification) {
            $client = new Client();
            $uri = 'https://graph.facebook.com/v15.0/100375426320424/messages';
            $headers = array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer EABR9lTePtecBAOdjMOitf7hCPOXXDbPZBN06O8GJ0Wy87wdLLJV1ZBo5ygIEgeo0ZC2ev3a4J264gRaLKcncRTSDqMbGpu1Ic81x7SPR4YGo8feeB8y0MVFstadl2TX6qoHi6HZBxvPqScIBTkcbiJPEuxmJmEVk8bxkTDIfGJvlphZC5szmD1RzXzq6xpZCOADZCt2UmIVfCuMCFJFrxG3'
            );
            $to = $data->entry[0]->changes[0]->value->messages[0]->from;
            $request = new Request('POST', $uri, $headers);
            $stream = new Stream(fopen('php://temp', 'r+'));
            if ($data->entry[0]->changes[0]->value->messages[0]->text->body === 'أوقف الخدمة') {
                $body = ["messaging_product" => "whatsapp", "to" => $to, "type" => "template", "template" => ["name" => "stop_service", "language" => ["code" => "ar"]]];
                $stream->write(json_encode($body));
                $stream->rewind();
                $request = $request->withBody($stream);
                $response = $client->send($request);
            } else {
                $body = ["messaging_product" => "whatsapp", "to" => $to, "type" => "template", "template" => ["name" => "to_stop_service", "language" => ["code" => "ar"]]];
                $stream->write(json_encode($body));
                $stream->rewind();
                $request = $request->withBody($stream);
                $response = $client->send($request);
            }
        } else {
            // Log::debug(print_r($data, true));
        }
    }
}
