<?php

namespace Modules\FeedbackBot\Middleware;

use Modules\FeedbackBot\Entities\FeedbackUserChat;
use SergiX44\Nutgram\Nutgram;

class CollectFeedbackChat
{
    public function __invoke(Nutgram $bot, $next): void
    {
        $user = $bot->user();
        $botId = $bot->getMe()->id;
        if ($user === null) {
            return;
        }

            //save or update chat
            $chat = FeedbackUserChat::updateOrCreate([
                'bot_id' => $botId,
                'chat_id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'username' => $user->username,
                //'language_code' => null,
                'blocked_at' => null,
            ]);

            if (!$chat->started_at && $bot->message()?->chat?->type === 'private') {
                $chat->started_at = now();
                $chat->save();
            }


        $bot->setData(FeedbackUserChat::class, $chat);

        $next($bot);
    }
}
