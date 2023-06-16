<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\WisdomController;
use App\Http\Controllers\UsersController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [WisdomController::class, "index"]);
Route::get('/category/{id}', [WisdomController::class, "getWisdomsForCategory"]);
Route::get('/search', [WisdomController::class, "searchForWisdom"]);
Route::get('/login', [UsersController::class, "login"]);
Route::get('/signout', [UsersController::class, "signout"]);
Route::post('/signin', [UsersController::class, "signin"]);
Route::post('/changeCategory', [WisdomController::class, "changeCategory"])->middleware("auth");
Route::post('/changeText', [WisdomController::class, "changeText"])->middleware("auth");
Route::get('/delete/{wisdom}', [WisdomController::class, "deleteWisdom"])->middleware("auth");
Route::get('/lastAddedWisdom', [WisdomController::class, "lastAddedWisdom"]);
Route::get('/add', function () {
    return view("add");
});
Route::post('/getWisdomsByIds', [WisdomController::class, "retrieveWisdoms"]);
Route::get('/getRelatedWisdoms/{wisdom}', [WisdomController::class, "retrieveWisdoms"]);
Route::get('/id/{wisdom}', [WisdomController::class, "getOneWisdom"]);
Route::get('after/id/{wisdom}', [WisdomController::class, "getAfterWisdom"]);
Route::get('before/id/{wisdom}', [WisdomController::class, "getBeforeWisdom"]);

Route::post('/createWisdoms', [WisdomController::class, "createWisdoms"])->middleware("auth");
Route::get('/lastAddedWisdoms', [WisdomController::class, "lastAddedWisdoms"]);
Route::get('/likeWisdom/{wisdom}', [WisdomController::class, "likeWisdom"]);
Route::get('/removeLike/{wisdom}', [WisdomController::class, "removeLike"]);
Route::get('/getRandomQuote', [WisdomController::class, "getRandomQuote"]);
Route::post('/new-subscriber', [UsersController::class, "newSubscriber"]);
Route::post('/messageFromTwilio', [UsersController::class, "messageFromTwilio"]);
Route::post('/enteredOTP', [UsersController::class, "enteredOTP"]);
Route::post('/resendOTP', [UsersController::class, "resendOTP"]);
Route::get('/terms', function () {
    return view("terms");
});
Route::get('/new-subscriber', function () {
    return view("subscribe")->with('message', "يرجى إعادة إدخال البيانات");
});

Route::get('/الدكتور-عبدالعزيز-المطوع', function () {
    return view("about");
});
Route::get('/subscribe', function () {
    return view("subscribe");
});

Route::get('payment-result', function () {
    return view("payment-result");
});

Route::get('callback', [PaymentController::class, 'callback'])->name('callback');
Route::get('charge', [PaymentController::class, 'charge']);

Route::get('renew-subscription/{subscriber}', [PaymentController::class, 'renewSubscription']);


Route::post('save-subscription', [SubscribeController::class, 'saveSubscription']);
Route::get('/push/{id}', [SubscribeController::class, 'push'])->name('push');
Route::post('/push', [SubscribeController::class, 'saveSubscription']);
