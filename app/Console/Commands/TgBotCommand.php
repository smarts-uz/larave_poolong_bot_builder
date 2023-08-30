<?php

namespace App\Console\Commands;

use App\Handlers\Telegram\TgBotGroupHandler;
use App\Models\TgBot;
use App\Services\TelegramBotButtonCreator;
use App\Services\TelegramBotGroupService;
use App\Services\TelegramBotService;
use App\Telegram\Middleware\TelegramBotCollectChat;
use Illuminate\Console\Command;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Polling;

class TgBotCommand extends Command
{
    protected $signature = 'tg:bot {id}';

    protected $description = 'Command description';
    protected $botId;

    public function handle(Nutgram $bot): void
    {
        $this->botId = $this->argument('id');
        $tgBot = TgBot::where('id', $this->botId)->first();

        $botService = new TelegramBotService();
        $cache = $botService->setCache();

        $bot = new Nutgram($tgBot->bot_token,
            ['cache' => $cache]);

        $tgBot->update(['bot_username' => $bot->getMe()->username]);

        $bot->middleware(function (Nutgram $bot, $next) {
            $bot->setData('bot_id', $this->botId);
            $next($bot);
        });

        $bot->middleware(TelegramBotCollectChat::class);

        $bot->setRunningMode(Polling::class);

        $bot->onMyChatMember(TgBotGroupHandler::class);


        $bot->onCommand('start', function (Nutgram $bot) {
            $bot->sendMessage('Hello world');
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

        $bot->onNewChatTitle(function (Nutgram $bot) {
            $newTitle = $bot->chat()->title;
            $groupId = $bot->message()->chat->id;
            $service = new TelegramBotGroupService();
            $service->updateChatTitle($groupId,$this->botId,$newTitle);
        });


        $bot->run();
    }
}
