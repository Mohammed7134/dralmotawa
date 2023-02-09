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
            $sid    = getenv('TWILIO_SID');
            $token  = getenv('TWILIO_TOKEN');
            $twilio = new Client($sid, $token);
            $message = $twilio->messages->create(
                "whatsapp:+" . $subscriber->telephone, // to 
                array(
                    "from" => "whatsapp:+14155238886",
                    "body" => $subscriber->otp
                )
            );
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
            $sid    = getenv('TWILIO_SID');
            $token  = getenv('TWILIO_TOKEN');
            $twilio = new Client($sid, $token);
            $message = $twilio->messages->create(
                "whatsapp:+" . $user->telephone, // to 
                array(
                    "from" => "whatsapp:+14155238886",
                    "body" => $user->otp
                )
            );
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

        $data = json_decode(request()->getContent(), true);
        Log::debug(print_r($data['entry']['changes'], true));
        return;
        if ($data['field'] === 'messages') {
            $client = new Client();
            $uri = 'https://graph.facebook.com/v15.0/100375426320424/messages';
            $headers = array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer EABR9lTePtecBAOdjMOitf7hCPOXXDbPZBN06O8GJ0Wy87wdLLJV1ZBo5ygIEgeo0ZC2ev3a4J264gRaLKcncRTSDqMbGpu1Ic81x7SPR4YGo8feeB8y0MVFstadl2TX6qoHi6HZBxvPqScIBTkcbiJPEuxmJmEVk8bxkTDIfGJvlphZC5szmD1RzXzq6xpZCOADZCt2UmIVfCuMCFJFrxG3'
            );
            $body = ["messaging_product" => "whatsapp", "to" => "96597134776", "type" => "template", "template" => ["name" => "hello_world", "language" => ["code" => "en_US"]]];

            $request = new Request('POST', $uri, $headers);
            $stream = new Stream(fopen('php://temp', 'r+'));
            $stream->write(json_encode($body));
            $stream->rewind();
            $request = $request->withBody($stream);
            $response = $client->send($request);
        }
        // $mode = $_GET['hub.mode'];
        // $challenge = $_GET['hub.challenge'];
        // if ($mode === 'subscribe') {
        //     header('Content-Type: text/plain');
        //     echo $challenge;
        //     exit;
        // }
        // return response()->json(['success' => true]);

        // if ($verify = "Verify") {
        //     $response = [
        //         'hub_challenge' => $challenge,
        //         'message' => 'Hello, World!',
        //     ];
        // } else {
        //     $response = [
        //         'hub_challenge' => $key,
        //         'message' => 'Error',
        //     ];
        // }

        // return json_encode($response);

        //     // validate that the request is coming from Twilio
        //     $validator = Validator::make(request()->all(), [
        //         'AccountSid' => 'required',
        //         'MessageSid' => 'required',
        //         'Body' => 'required',
        //         'To' => 'required',
        //         'From' => 'required',
        //     ]);

        //     if ($validator->fails()) {
        //         return response('Invalid request', 400);
        //     }

        //     // handle the incoming message
        //     $messageBody = request()->input('Body');
        //     if ($messageBody == 'أوقف الخدمة') {
        //         $customer = Subscriber::where('telephone', '=', explode('+', request()->From)[1])->first();
        //         $customer->telephone = "0";
        //         $customer->save();
        //         return response('<Response><Message>تم إيقاف الخدمة</Message></Response>', 200)
        //             ->header('Content-Type', 'text/xml');
        //     } else {
        //         return response('<Response><Message>لإيقاف الخدمة أرسل: أوقف الخدمة</Message></Response>', 200)
        //             ->header('Content-Type', 'text/xml');
        //     }
    }
}
