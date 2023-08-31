<?php

namespace App\Services;

use App\Models\TelegramBot;
use App\Models\TgBot;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\URL;

class BotSetWebhookService
{
    public function setWebhook($botToken):void
    {
        $client = new Client();
        $id = TgBot::where('bot_token', $botToken)->first();
        $url = URL::route('bot_url',['id' => $id->id]);
        $url = str_replace('http://oyinauzbot/', env('TELEGRAM_WEBHOOK_URL'), $url);
        $url = preg_replace('#^https?://#', '', $url);
        $urlApp = "https://api.telegram.org/bot{$botToken}/setWebhook?url={$url}";
        $request = new Request('GET', $urlApp);
        $client->send($request, ['http_errors' => false]);
    }
    public function deleteWebhook($botToken):void
    {
        $client = new Client();
        $urlApp = "https://api.telegram.org/bot{$botToken}/setWebhook?url=";
        $request = new Request('GET', $urlApp);
        $client->send($request, ['http_errors' => false]);
    }
}
