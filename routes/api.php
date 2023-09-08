<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

//Route::match(['get', 'post'],'/telegram/{id}', [\App\Http\Controllers\Telegram\BaseBotController::class, 'handle'])->name('bot_url');
//
//Route::match(['get', 'post'],'/botfather', [\App\Http\Controllers\Telegram\BotFatherController::class, 'handle'])->name('bot_father');
