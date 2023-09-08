<?php

namespace Modules\FeedbackBot\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use Modules\FeedbackBot\Conversations\BotFatherFeedBackConversation;
use Modules\FeedbackBot\Conversations\ReplyToUserConversation;
use Modules\FeedbackBot\Handlers\BotFatherJoinLeftGroupHandler;
use Modules\FeedbackBot\InlineMenu\BotFather\BotFatherChoseLanguage;
use Modules\FeedbackBot\InlineMenu\BotFather\MainMenu;
use Modules\FeedbackBot\Middleware\CollectChat;
use Modules\FeedbackBot\Services\BotFatherService;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Webhook;

class BotFatherController extends Controller
{

    public function handle(Nutgram $bot){

        $botFatherService = new BotFatherService();
        $cache =  $botFatherService->setCache();

        $bot = new Nutgram($_ENV['TELEGRAM_TOKEN'],
            ['cache' => $cache]);

        //Collect Bot Father users to db
        $bot->middleware(CollectChat::class);

        $bot->setRunningMode(Webhook::class);

        $bot->onMyChatMember(BotFatherJoinLeftGroupHandler::class);

        $bot->onCommand('start', function (Nutgram $bot) {
            $chatId = $bot->chat()->id;

            $service = new BotFatherService();
            $laguageCode = $service->findByLanguageCode($chatId);

            if ($laguageCode == null) {
                BotFatherChoseLanguage::begin($bot);
            } else {
                MainMenu::begin($bot);
            }
        })->description('Start Command');

        $bot->onCommand('lang', function (Nutgram $bot) {
            BotFatherChoseLanguage::begin($bot);
        })->description('Change language command');

        $bot->onCommand('feedback', function (Nutgram $bot) {
            BotFatherFeedBackConversation::begin($bot);
        })->description('Ask your question and we will answer it');

        $bot->onMessage(function (Nutgram $bot) {
            $message = $bot->message();

            $botFatherService = new BotFatherService();
            $botId = $bot->getMe()->id;

            $chatId = $botFatherService->getBotFatherChat($botId);


            if ($message->reply_to_message !== null && $message->chat->id == $chatId){
                //Reply message logic
                ReplyToUserConversation::begin($bot);
            }
            if ($message->new_chat_title !== null && $message->chat->id == $chatId) {

                //Update chat title
                $newChatTitle = $bot->message()->new_chat_title;
                $groupId = $bot->message()->chat->id;

                $botFatherService->updateChatTitle($botId, $groupId,$newChatTitle);

            }
        })->skipGlobalMiddlewares();

        //payments
        $bot->onPreCheckoutQuery(function (Nutgram $bot) {
            $bot->answerPreCheckoutQuery(true);
        });
        //payments end

        $bot->run();
    }
}
