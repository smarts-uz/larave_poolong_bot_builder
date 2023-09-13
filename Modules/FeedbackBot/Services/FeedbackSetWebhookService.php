<?php

namespace Modules\FeedbackBot\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Modules\FeedbackBot\Entities\TelegramBot;
use Modules\PoolingBot\Entities\TgBot;

class FeedbackSetWebhookService
{
    public function setWebhook($id):void
    {

        $bot = TelegramBot::where('id', $id)->first();

        $client = new Client();

        $botToken = $bot->bot_token;

        $baseUrl = $bot->base_url;

        $url = "/api/fb/telegram/{$id}";


        $combinedURL = preg_replace("~^(?:f|ht)tps?://~i", "", $baseUrl);


        if (substr($combinedURL, -1) === '/') {
            $combinedURL = substr($combinedURL, 0, -1);
        }

        $combinedURL .= rtrim($url, '/');


        $urlApp = "https://api.telegram.org/bot{$botToken}/setWebhook?url={$combinedURL}";

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
