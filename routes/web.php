<?php

use App\Http\Controllers\CategoryController;
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

Route::get('/', [WisdomController::class, "index"])->name('home');
Route::get('/category/{id}', [WisdomController::class, "getWisdomsForCategory"]);
Route::get('/search', [WisdomController::class, "searchForWisdom"]);
Route::get('/login', [UsersController::class, "login"])->name('login');
Route::get('/signout', [UsersController::class, "signout"]);
Route::post('/signin', [UsersController::class, "signin"]);
Route::post('/changeCategory', [WisdomController::class, "changeCategory"])->middleware("auth");
Route::post('/changeText', [WisdomController::class, "changeText"])->middleware("auth");
Route::get('/delete/{wisdom}', [WisdomController::class, "deleteWisdom"])->middleware("auth");
Route::get('/add', function () {
    return view("add");
});
Route::post('/getWisdomsByIds', [WisdomController::class, "retrieveWisdoms"]);
Route::get('/getRelatedWisdoms/{wisdom}', [WisdomController::class, "retrieveWisdoms"]);
Route::get('/id/{id}', [WisdomController::class, "getOneWisdom"]);
Route::get('after/id/{wisdom}', [WisdomController::class, "getAfterWisdom"]);
Route::get('before/id/{wisdom}', [WisdomController::class, "getBeforeWisdom"]);

Route::post('/createWisdoms', [WisdomController::class, "createWisdoms"])->middleware("auth");
Route::get('/likeWisdom/{wisdom}', [WisdomController::class, "likeWisdom"]);
Route::get('/removeLike/{wisdom}', [WisdomController::class, "removeLike"]);
Route::get('/getRandomQuote', [WisdomController::class, "getRandomQuote"]);

Route::get('/الدكتور-عبدالعزيز-المطوع', function () {
    return view("about");
});

Route::post('save-subscription', [SubscribeController::class, 'saveSubscription']);
Route::get('/push/{id}', [SubscribeController::class, 'push'])->name('push');
Route::post('/push', [SubscribeController::class, 'saveSubscription']);

// temporary
Route::get('getWisdoms', [WisdomController::class, 'getWisdoms']);
