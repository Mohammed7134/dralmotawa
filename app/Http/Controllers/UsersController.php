<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Services\MyService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'telephone' => 'required|numeric|min:6',
            'country_code' => 'required|numeric|min:1',
            'agree' => 'required',
        ]);
        $exists = Subscriber::where('telephone', '=', request()->telephone)->where("country_code", "=", request()->country_code)->get();
        if (request()->agree) {
            if (count($exists) == 0) {
                $subscriber = new Subscriber();
                $subscriber->first_name = request()->first_name;
                $subscriber->last_name = request()->last_name;
                $subscriber->telephone = request()->telephone;
                $subscriber->country_code = request()->country_code;
                $subscriber->email = request()->email;
                $subscriber->otp = mt_rand(100000, 999999);
                $subscriber->otp_expiry = time() + 60;
                $parameter1 = json_encode(array(
                    "type" => "text",
                    "text" => $subscriber->otp
                ));
                $myservice = new MyService;
                $response = $myservice->sendWhatsApp($subscriber, [$parameter1], 'otp_message');
                if (isset($response->error->message)) {
                    return back()->with("message", $response->error->message);
                } else {
                    $subscriber->save();
                    session()->start();
                    session()->put('user_id', $subscriber->id);
                    return view('OTP')->with('subscriber', $subscriber);
                }
            } else {
                if ($exists[0]->otp == null) {
                    if ($exists[0]->payments->last()->status == "CAPTURED") {
                        $date = Carbon::parse($exists[0]->payments->last()->created_at);
                        if ($date->addDays($exists[0]->payments->last()->period)->isPast()) {
                            $exists[0]->otp = mt_rand(100000, 999999);
                            $exists[0]->otp_expiry = time() + 60;
                            $exists[0]->save();
                            $parameter1 = json_encode(array(
                                "type" => "text",
                                "text" => $exists[0]->otp
                            ));
                            $myservice = new MyService;
                            $response = $myservice->sendWhatsApp($exists[0], [$parameter1], 'otp_message');
                            if (isset($response->error->message)) {
                                return back()->with("message", $response->error->message);
                            } else {
                                $exists[0]->save();
                                session()->start();
                                session()->put('user_id', $exists[0]->id);
                                return view('OTP')->with('subscriber', $exists[0]);
                            }
                        } else {
                            return back()->with("message", "الرقم مسجل مسبقا٬ ولم ينته الاشتراك");
                        }
                    } else {
                        $exists[0]->otp = mt_rand(100000, 999999);
                        $exists[0]->otp_expiry = time() + 60;
                        $exists[0]->save();
                        $parameter1 = json_encode(array(
                            "type" => "text",
                            "text" => $exists[0]->otp
                        ));
                        $myservice = new MyService;
                        $response = $myservice->sendWhatsApp($exists[0], [$parameter1], 'otp_message');
                        if (isset($response->error->message)) {
                            return back()->with("message", $response->error->message);
                        } else {
                            $exists[0]->save();
                            session()->start();
                            session()->put('user_id', $exists[0]->id);
                            return view('OTP')->with('subscriber', $exists[0]);
                        }
                    }
                } elseif ($exists[0]->otp_expiry > time()) {
                    return view('OTP')->with('subscriber', $exists[0]);
                } else {
                    $exists[0]->delete();
                    return back()->with("message", "يرجى المحاولة مجددا");
                }
            }
        } else {
            return back()->with("message", "يجب الموافقة على الشروط");
        }
    }
    function resendOTP()
    {
        // Validate the form data
        $validatedData = request()->validate([
            'telephone' => 'required|numeric|min:6',
            'country_code' => 'required|numeric|min:1',
        ]);
        $subscriber = Subscriber::where('telephone', '=', request()->telephone)->where("country_code", "=", request()->country_code)->first();
        if ($subscriber) {
            $subscriber->otp = mt_rand(100000, 999999);
            $subscriber->otp_expiry = time() + 60;
            $subscriber->save();
            $parameter1 = json_encode(array(
                "type" => "text",
                "text" => $subscriber->otp
            ));
            $myservice = new MyService;
            $response = $myservice->sendWhatsApp($subscriber, [$parameter1], 'otp_message');
            if (isset($response->error->message)) {
                $result['error'] = true;
                $result['message'] = $response->error->message;
            } else {
                $result['error'] = false;
                $result['message'] = "تم الإرسال";
            }
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
            'telephone' => 'required|numeric|min:6',
            'country_code' => 'required|numeric|min:1',

        ]);
        $user = Subscriber::where('telephone', '=', request()->telephone)->where("country_code", "=", request()->country_code)->first();
        if ($user) {
            if ($user->otp == request()->OTP) {
                if (time() < $user->otp_expiry) {
                    $user->otp = null;
                    $user->otp_expiry = null;
                    $user->save();
                    $result['error'] = false;
                    $result['message'] = "تم التحقق من الرقم بنجاح";
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
        $data = json_decode(request()->getContent());
        if (isset($data->entry[0]->changes)) {
            if (isset($data->entry[0]->changes[0]->value)) {
                $inboundNotification = isset($data->entry[0]->changes[0]->value->messages[0]);
                $messageType = $data->entry[0]->changes[0]->value->messages[0]->type;
                if ($inboundNotification) {
                    $to = $data->entry[0]->changes[0]->value->messages[0]->from;
                    $subscriber = Subscriber::where('telephone', '=', $to)->first();
                    if ($subscriber) {
                        if ($messageType == "text" || $messageType = "button") {
                            if ($data->entry[0]->changes[0]->value->messages[0]->text->body === 'أوقف الخدمة') {
                                $myservice = new MyService;
                                $response = $myservice->sendWhatsApp($subscriber, [], 'stop_service');
                                if (isset($response->error->message)) {
                                    Log::debug($response->error->message);
                                } else {
                                    Log::debug("One message was sent to one user who sent request to stop service");
                                }
                                $subscriber = Subscriber::where('telephone', '=', $to);
                                $subscriber->delete();
                            } else {
                                $myservice = new MyService;
                                $response = $myservice->sendWhatsApp($subscriber, [], 'to_stop_service');
                                if (isset($response->error->message)) {
                                    Log::debug($response->error->message);
                                } else {
                                    Log::debug("One message was sent to one user who sent something");
                                }
                            }
                        } else {
                            // message is not text
                        }
                    } else {
                        // user no longer a subscriber
                    }
                } else {
                    Log::debug(print_r($data, true));
                }
            } else {
                Log::debug(print_r($data, true));
            }
        } else {
            Log::debug(print_r($data, true));
        }
    }
}
