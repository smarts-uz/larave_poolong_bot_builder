<?php

namespace Modules\FeedbackBot\Http\Controllers\Telegram;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lukasss93\ModelSettings\Managers\TableSettingsManager;
use Modules\FeedbackBot\Conversations\FeedBackConversation;
use Modules\FeedbackBot\Conversations\ReplyToUserConversation;
use Modules\FeedbackBot\Entities\TelegramBot;
use Modules\FeedbackBot\Handlers\JoinLeftGroupHandler;
use Modules\FeedbackBot\InlineMenu\BaseBot\ChoseLangugage;
use Modules\FeedbackBot\InlineMenu\BaseBot\NewsletterMenu;
use Modules\FeedbackBot\Middleware\CollectFeedbackChat;
use Modules\FeedbackBot\Services\BaseBotService;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Webhook;

class BaseBotController extends Controller
{
    protected $botId;
    protected TableSettingsManager $settings;
    protected $botToken;
    protected $userLocale;

    public function handle(Request $request, $id):void
    {
        //Bot Id
        $this->botId = $id;

        //Base Bot Controller service
        $baseBotService = new BaseBotService();

        $tgBot = TelegramBot::where('id',$this->botId)->first();

        //Get bot token  from database
        $botToken = $baseBotService->getBotToken($id);
        $this->botToken = $botToken;

        //Set cache directory
        $cacheDirectory = $baseBotService->setCacheStorage($botToken);
        //Set cache adapter
        $psr16Cache = $baseBotService->setCacheAdapter($cacheDirectory);


        //Init nutgram
        $bot = new Nutgram($this->botToken,
            ['cache' => $psr16Cache]);

        $tgBot->update(['username' => $bot->getMe()->username]);




        $bot->middleware(function (Nutgram $bot, $next) {
            $userId = $bot->user()->id;
            $botId = $bot->getMe()->id;

            $bot->setData('botId', $this->botId);
            $id = $this->botId;

            $baseBotService = new BaseBotService();
            $baseBotService->setBotId($id,$botId);

            $langValue = $baseBotService->getLanguage($userId,$botId);


            $value = $baseBotService->languageCodeValidation($this->botId, $langValue);

            if ($value) {

                $bot->setData('chatId', $value);

            }
            else{

                $chatId = $baseBotService->findLatestChat($this->botId);

                $bot->setData('chatId', $chatId);
            }

            $next($bot);
        });

        //Collect feedback users to database
        $bot->middleware(CollectFeedbackChat::class);

         //$bot->middleware(DisableBot::class);
        // $bot->onMyChatMember(UpdateChatStatus::class);

        //Set webhook
        $bot->setRunningMode(Webhook::class);

        //Save/Delete bot group to/from db
        $bot->onMyChatMember(JoinLeftGroupHandler::class);

        //Start Command
        $bot->onCommand('start', function (Nutgram $bot) {

            $chatId = $bot->chat()->id;
            $botId = $bot->getMe()->id;
            $localBotId = $this->botId;

            $service = new BaseBotService();
            $laguageCode = $service->findByLanguageCode($botId, $chatId);

            if ($laguageCode == null) {
                ChoseLangugage::begin($bot);
            } else {
                FeedBackConversation::begin($bot);
            }


        })->description('Start command');

        $bot->onCommand('lang', function (Nutgram $bot) {
            ChoseLangugage::begin($bot);
        })->description('Change language command');

        //NewsLetter command
        $bot->onCommand('start newsletter', function (Nutgram $bot) {

            $userName = $bot->getMe()->username;
            $userId = $bot->chat()->id;

            $baseBotService = new BaseBotService();
            $currentUser = $baseBotService->adminValidation($userName, $userId);

            if ($currentUser == $bot->chat()->id) {
                NewsletterMenu::begin($bot);
            }
        });


        $bot->onMessage(function (Nutgram $bot) {
            $message = $bot->message();

            $chatId = $bot->getData('chatId');

            $baseBotService = new BaseBotService();

            if ($message->reply_to_message !== null && $message->chat->id == $chatId){
                //Reply message logic
                ReplyToUserConversation::begin($bot);
                TelegramBot::where('id', $this->botId)->increment('response_messages', 1);
            }
            if ($message->new_chat_title !== null && $message->chat->id == $chatId) {

                //Update chat title
                $newChatTitle = $bot->message()->new_chat_title;
                $groupId = $bot->message()->chat->id;

                $baseBotService->updateChatTitle($this->botId, $groupId,$newChatTitle);

            }
        });

        $bot->registerMyCommands();
        $bot->run();
    }
}

