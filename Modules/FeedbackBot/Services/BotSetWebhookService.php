<?php

namespace Modules\FeedbackBot\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\URL;
use Modules\FeedbackBot\Entities\TelegramBot;

class BotSetWebhookService
{
    public function setWebhook($botToken):void
    {
        $client = new Client();
        $id = TelegramBot::where('bot_token', $botToken)->first();
        $url = URL::route('bot_url',['id' => $id->id]);
        $url = str_replace('http://feedback/', env('TELEGRAM_WEBHOOK_URL'), $url);
        $url = preg_replace('#^https?://#', '', $url);
        $urlApp = "https://api.telegram.org/bot{$botToken}/setWebhook?url={$url}";
        $request = new Request('GET', $urlApp);
        $client->send($request, ['http_errors' => false]);
    }
}
