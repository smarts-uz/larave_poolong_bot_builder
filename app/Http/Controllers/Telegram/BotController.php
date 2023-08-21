<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Service\TelegramBotService;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Webhook;

class BotController extends Controller
{
    public function handle(Nutgram $bot)
    {
        $botService = new TelegramBotService();
        $cache = $botService->setCache();

        $bot = new Nutgram($_ENV['TELEGRAM_TOKEN'],
            ['cache' => $cache]);

        $bot->setRunningMode(Webhook::class);

        $bot->onCommand('start', function (Nutgram $bot) {
            $bot->sendMessage('Hello');
        });

        $bot->run();
    }
}
