<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\FeedbackBot\Http\Controllers\Telegram\BaseBotController;
use Modules\FeedbackBot\Http\Controllers\Telegram\BotFatherController;
use Modules\PoolingBot\Http\Controllers\Telegram\BotController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Route::post('telegram',[\App\Http\Controllers\Telegram\BotController::class,'handle'])->name('telegram-bot');

Route::match(['get', 'post'],'/telegram/{id}', [BotController::class,'handle'])->name('bot_url');

Route::match(['get', 'post'],'/fb/telegram/{id}', [BaseBotController::class, 'handle'])->name('fb_bot_url');

Route::match(['get', 'post'],'/botfather', [BotFatherController::class, 'handle'])->name('bot_father');
