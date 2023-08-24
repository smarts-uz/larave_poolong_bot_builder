<?php

namespace App\Telegram\Middleware;

use App\Jobs\TelegramBotCollectChatJob;
use App\Models\FeedbackUserChat;
use App\Models\TelegramUser;
use SergiX44\Nutgram\Nutgram;

class TelegramBotCollectChat
{
    public function __invoke(Nutgram $bot, $next): void
    {
        $user = $bot->user();
        if ($user === null) {
            return;
        }
        //save or update chat
        TelegramBotCollectChatJob::dispatch($user);

        $next($bot);
    }
}
