<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Support\Facades\Validator;

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
            $subscriber->save();


            return back()->with("message", "تم الاشتراك بنجاح");
        } else {
            return back()->with("message", "الرقم مسجل مسبقا");
        }
    }
    function messageFromTwilio()
    {
        // validate that the request is coming from Twilio
        $validator = Validator::make(request()->all(), [
            'AccountSid' => 'required',
            'MessageSid' => 'required',
            'Body' => 'required',
            'To' => 'required',
            'From' => 'required',
        ]);

        if ($validator->fails()) {
            return response('Invalid request', 400);
        }

        // handle the incoming message
        $messageBody = request()->input('Body');
        if ($messageBody == 'أوقف الخدمة') {
            $customer = Subscriber::where('telephone', '=', explode('+', request()->From)[1])->first();
            $customer->telephone = "0";
            $customer->save();
            return response('<Response><Message>تم إيقاف الخدمة' . request()->From . '</Message></Response>', 200)
                ->header('Content-Type', 'text/xml');
        } else {
            return response('<Response><Message>لإيقاف الخدمة أرسل: أوقف الخدمة</Message></Response>', 200)
                ->header('Content-Type', 'text/xml');
        }
    }
}
