<?php

namespace Modules\FeedbackBot\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class BotRemoveWebhookService
{
    public function removeWebhook($botToken)
    {
        $client = new Client();
        $urlApp = "https://api.telegram.org/bot{$botToken}/setWebhook?url=";
        $request = new Request('GET', $urlApp);
        $client->send($request, ['http_errors' => false]);
    }
}
