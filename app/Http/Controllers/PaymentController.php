<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Subscriber;
use App\Services\MyService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tap\Charge;

class PaymentController extends Controller
{

    public function charge(Request $request)
    {
        $chosenCharge = $request->period == 30 ? env('MONTH_CHARGE') : env('YEAR_CHARGE');
        $user_id = session()->get('user_id');
        $subscriber = Subscriber::where('id', '=', $user_id)->first();
        // add data to payment table
        $payment = new Payment();
        $payment->payment_id = 0;
        $payment->period = $request->period;
        $payment->subscriber_id = $user_id;
        $payment->amount = $chosenCharge;
        $payment->currency = "USD";
        $payment->status = "FREE"; //"INITIATED";
        $payment->save();
        return $this->callback($request);
        //capture payment details
        // $paymentData = [
        //     "amount" => $chosenCharge,
        //     "threeDSecure" => true,
        //     "description" =>  'مرحبا ' . $subscriber->first_name . ' ' . $subscriber->last_name . ' رقم طلبك هو: ' . $payment->id . ' يرجى استكمال الدفع٬ ونشكر لك استخدامك لهذه الخدمة',
        //     "currency" => env('SUBSCRIPTION_CURRENCY'),
        //     "receipt" => [
        //         "email" => true,
        //         "sms" => true
        //     ],
        //     "customer" => [
        //         "first_name" => $subscriber->first_name,
        //         "last_name" => $subscriber->last_name,
        //         "email" => $subscriber->email,
        //         "phone" => [
        //             "country_code" => $subscriber->country_code,
        //             "number" => $subscriber->telephone
        //         ]
        //     ],
        //     "source" => [
        //         "id" => "src_all"
        //     ],
        //     "redirect" => [
        //         "url" => route('callback')
        //     ]
        // ];

        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => "https://api.tap.company/v2/charges",
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 30,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "POST",
        //     CURLOPT_POSTFIELDS => json_encode($paymentData),
        //     CURLOPT_HTTPHEADER => array(
        //         "authorization: Bearer " . env('TAP_API_KEY'),
        //         "content-type: application/json"
        //     ),
        // ));

        // $response = curl_exec($curl);
        // $err = curl_error($curl);

        // curl_close($curl);

        // $response = json_decode($response);
        // if (isset($response->errors)) {
        //     return back()->withErrors($response->errors[0]->description);
        // }
        // return redirect($response->transaction->url);
    }

    public function callback(Request $request)
    {
        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => "https://api.tap.company/v2/charges/" . $request->tap_id,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 30,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "GET",
        //     CURLOPT_POSTFIELDS => "{}",
        //     CURLOPT_HTTPHEADER => array(
        //         "authorization: Bearer " . env('TAP_API_KEY')
        //     ),
        // ));

        // $response = curl_exec($curl);
        // $err = curl_error($curl);

        // curl_close($curl);

        // $responseTap = json_decode($response);
        $user_id = session()->get('user_id');
        if ($user_id) {
            $subscriber = Subscriber::where('id', '=', $user_id)->first();
            $payment = $subscriber->payments->last();
            // if ($responseTap->status == 'CAPTURED') {
            // $payment->payment_id = $responseTap->id;
            // $payment->status = $responseTap->status;
            // $payment->save();
            $parameter1 = json_encode(array(
                "type" => "text",
                "text" => $payment->payment_id
            ));
            $parameter2 = json_encode(array(
                "type" => "text",
                "text" => "$payment->period يوما"
            ));
            $parameter3 = json_encode(array(
                "type" => "text",
                "text" => "الثامنة والنصف"
            ));
            $parameter4 = json_encode(array(
                "type" => "text",
                "text" => 'الكويت'
            ));
            $myservice = new MyService;
            $response = $myservice->sendWhatsApp($subscriber, [$parameter1, $parameter2, $parameter3, $parameter4], 'successful_subscription');
            // message to admin
            $admin = Subscriber::where('id', '=', 10)->first();

            $response = $myservice->sendWhatsApp($admin, [$parameter1, $parameter2, $parameter3, $parameter4], 'successful_subscription');
            session()->forget('user_id');
            if (isset($response->error->message)) {
                return redirect('payment-result')->with("message", 'تم الاشتراك بنجاح٬ لكن حدث خطأ في ارسال التأكيد للواتساب الخاص بك');
            } else {
                return redirect('payment-result')->with("message", 'تم الاشتراك بنجاح');
            }
            // } else {
            //     return redirect('payment-result')->withErrors('لم تقبل عملية الدفع');
            // }
        }
        // if ($responseTap->status == 'CAPTURED') {
        return redirect('payment-result')->withErrors('تم الاشتراك بنجاح٬ لكن حدث خطأ٬ يرجى الابلاغ');
        // }
        // return redirect('payment-result')->withErrors('حدث خطأ يرجى المحاولة مجددا');
    }
    public function renewSubscription(Subscriber $subscriber)
    {
        $date = Carbon::parse($subscriber->payments->last()->created_at);
        if ($date->addDays($subscriber->payments->last()->period)->isPast() == false) {
            if ($subscriber->payments->last()->status == "CAPTURED") {
                return redirect('/')->with("message", "الرقم مسجل مسبقا٬ ولم ينته الاشتراك");
            } else {
                session()->start();
                session()->put('user_id', $subscriber->id);
                return $this->charge(request());
            }
        } else {
            session()->start();
            session()->put('user_id', $subscriber->id);
            return $this->charge(request());
        }
    }
}
