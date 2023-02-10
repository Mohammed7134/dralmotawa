<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Services\MyService;

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
            $parameter1 = json_encode(array(
                "type" => "text",
                "text" => $subscriber->otp
            ));
            $myservice = new MyService;
            $myservice->sendWhatsApp($subscriber, $parameter1, 'otp_message');
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
        $subscriber = Subscriber::where('telephone', '=', request()->telephone)->first();
        if ($subscriber) {
            $subscriber->otp = mt_rand(100000, 999999);
            $subscriber->otp_expiry = time() + 60;
            $subscriber->save();
            $parameter1 = json_encode(array(
                "type" => "text",
                "text" => $subscriber->otp
            ));
            $myservice = new MyService;
            $myservice->sendWhatsApp($subscriber, $parameter1, 'otp_message');
            $subscriber->save();
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
            $to = $data->entry[0]->changes[0]->value->messages[0]->from;
            $subscriber = Subscriber::where('telephone', '=', $to);
            if ($data->entry[0]->changes[0]->value->messages[0]->text->body === 'أوقف الخدمة') {
                $myservice = new MyService;
                $myservice->sendWhatsApp($subscriber, '', 'stop_service');
            } else {
                $myservice = new MyService;
                $myservice->sendWhatsApp($subscriber, '', 'to_stop_service');
            }
        } else {
            // Log::debug(print_r($data, true));
        }
    }
}
