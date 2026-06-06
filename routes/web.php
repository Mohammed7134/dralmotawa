<?php

use App\Http\Controllers\WisdomController;
use App\Livewire\GeminiChat;
use Illuminate\Support\Facades\Route;
/*|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which contains the "web" middleware group. Now create something great!   
*/

Route::get('/', [WisdomController::class, 'index'])->name('home');
Route::get('/wisdom/{wisdom}', [WisdomController::class, 'show'])->name('wisdom.show');
Route::get('/category/{category:category_url}', [WisdomController::class, 'category'])->name('category.show');
Route::get('/chat', GeminiChat::class)->name('chat');
Route::get('/service-worker.js', fn() => response()->file(public_path('service-worker.js'), ['Content-Type' => 'application/javascript']));

// Push subscription API
Route::post('/push/subscribe', [App\Http\Controllers\PushController::class, 'subscribe']);
Route::post('/push/unsubscribe', [App\Http\Controllers\PushController::class, 'unsubscribe']);
Route::get('/login', function () {
    return redirect()->to('/admin/login');
})->name('login');
