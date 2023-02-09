<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client;

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
        $key = $_GET['hub_challenge'];
        $verify = $_GET['hub_verify_token'];
        if ($verify = "Verify") {
            $response = [
                'hub_challenge' => $key,
                'message' => 'Hello, World!',
            ];
        } else {
            $response = [
                'hub_challenge' => $key,
                'message' => 'Error',
            ];
        }

        return json_encode($response);

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
