<?php

namespace Modules\FeedbackBot\Middleware;

use Modules\FeedbackBot\Entities\TelegramUserChat;
use SergiX44\Nutgram\Nutgram;

class CollectChat
{
    public function __invoke(Nutgram $bot, $next): void
    {
        $user = $bot->user();

        if ($user === null) {
            return;
        }


            //save or update chat
            $chat = TelegramUserChat::updateOrCreate([
                'chat_id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'username' => $user->username,
//                'language_code' => null,
                'blocked_at' => null,
            ]);

            if (!$chat->started_at && $bot->message()?->chat?->type === 'private') {
                $chat->started_at = now();
                $chat->save();
            }


        $bot->setData(TelegramUserChat::class, $chat);

        $next($bot);
    }
}
