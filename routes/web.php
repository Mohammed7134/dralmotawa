<?php

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
Route::get('/id/{wisdom}', [WisdomController::class, "getOneWisdom"]);
Route::get('/category/{id}', [WisdomController::class, "getWisdomsForCategory"]);
Route::get('/search', [WisdomController::class, "searchForWisdom"]);
Route::get('/login', [UsersController::class, "login"]);
Route::get('/signout', [UsersController::class, "signout"]);
Route::post('/signin', [UsersController::class, "signin"]);
Route::get('/الدكتور-عبدالعزيز-المطوع', function () {
    return view("about");
});
