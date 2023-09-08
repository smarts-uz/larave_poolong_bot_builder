<?php

namespace Modules\FeedbackBot\Middleware;


use Modules\FeedbackBot\Entities\Chat;
use Modules\FeedbackBot\Entities\TelegramBot;
use SergiX44\Nutgram\Nutgram;

class DisableBot
{
        public function __invoke(Nutgram $bot, $next)
        {
            $botId = $bot->getData('botId');
            $currentBot = TelegramBot::where('id', $botId)->first();
            $botChats = Chat::where('telegram_bot_id', $botId)->get();

            if ($currentBot && $currentBot->disable_bot) {
                foreach ($botChats as $chat) {
                    $chatId = $chat->chat_id;
                    $bot->leaveChat($chatId);
                }
            }

            $next($bot);
        }
}
