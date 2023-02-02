<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Http\Request;

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
        if (!$exists) {
            $subscriber = new Subscriber();
            $subscriber->name = request()->name;
            $subscriber->telephone = request()->countryCode .  request()->telephone;
            $subscriber->save();
            return back()->with("message", "تم الاشتراك بنجاح");
        } else {
            return back()->with("message", "الرقم مسجل مسبقا");
        }
    }
}
