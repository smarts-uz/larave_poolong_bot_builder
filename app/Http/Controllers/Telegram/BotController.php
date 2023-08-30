<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Jobs\TelegramBotButtonActionJob;
use App\Models\Post;
use App\Models\PostUser;
use App\Models\TelegramUser;
use App\Services\TelegramBotButtonCreator;
use App\Services\TelegramBotService;
use App\Telegram\Middleware\TelegramBotCollectChat;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Queue\Queue;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Polling;
use SergiX44\Nutgram\RunningMode\Webhook;

class BotController extends Controller
{
    public function handle(Nutgram $bot)
    {
        $botService = new TelegramBotService();
        $cache = $botService->setCache();

        $bot = new Nutgram($_ENV['TELEGRAM_TOKEN'],
            ['cache' => $cache]);

        try {
            $bot->middleware(TelegramBotCollectChat::class);
        } catch (\Exception $exception) {
            Debugbar::info($exception);
        }
        $bot->setRunningMode(Polling::class);


        $bot->onCommand('start', function (Nutgram $bot) {
            $bot->sendMessage('Hello');
        });

        $bot->onCallbackQuery(function (Nutgram $bot) {
            $callbackData = $bot->callbackQuery()->data;
            $keyboardService = new TelegramBotButtonCreator();
            $botService = new TelegramBotService();

            $messageId = $bot->callbackQuery()->message->message_id;
            $userId = $bot->user()->id;


            $post = $keyboardService->findPost($messageId);
            $isChatMember = $bot->getChatMember($_ENV['TELEGRAM_BOT_GROUP_ID'],$userId)->status;
            if ($isChatMember == 'member' || $isChatMember == 'administrator' || $isChatMember == 'creator') {
                $buttonAction = $botService->buttonsAction($messageId,$callbackData,$userId);
                if (!empty($buttonAction)) {
                    switch ($buttonAction) {
                        case 'notRated':
                            $bot->answerCallbackQuery([
                                'text' => 'Sizning ovozingiz hisoblab chiqildi.',
                                'cache_time' => 1,
                                'show_alert' => true,
                            ]);
                            $keyboard = $keyboardService->botCreateInlineButtons($post);
                            $bot->editMessageReplyMarkup(['reply_markup' => $keyboard]);
                        case 'rated':
                            $bot->answerCallbackQuery([
                                'text' => 'Siz allaqachon ovoz bergansiz',
                                'cache_time' => 1,
                                'show_alert' => true,
                            ]);
                    }
                }
            }else{
                $bot->answerCallbackQuery([
                    'text' => "Ovoz berish uchun OYINA.UZ kanaliga obuna bo'lishingiz kerak",
                    'cache_time' => 1,
                    'show_alert' => true,
                ]);
            }

        });

        $bot->run();
    }
}
