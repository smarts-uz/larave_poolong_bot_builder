<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\TelegramBotButtonCreator;
use App\Services\TelegramBotService;
use Barryvdh\Debugbar\Facades\Debugbar;
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
        $bot->onCallbackQuery(function (Nutgram $bot) {
            $callbackData = $bot->callbackQuery()->data;
            $keyboardService = new TelegramBotButtonCreator();

            $messageId = $bot->callbackQuery()->message->message_id;
            $bot->answerCallbackQuery([
                'text' => $messageId
            ]);
            try {
                $post = Post::where('telegram_message_id', $messageId)->first();
                if ($post) {
                    $button = $post->button()->where('title', $callbackData)->first();
                    if ($button) {
                        $button->increment('count');
                        $keyboard = $keyboardService->botCreateInlineButtons($post);
                        $bot->editMessageReplyMarkup(['reply_markup' => $keyboard]);
                    }
                }
            } catch (\Exception $exception) {
                Debugbar::info($exception);
            }

        });

        $bot->run();
    }
}
