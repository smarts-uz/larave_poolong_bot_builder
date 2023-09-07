<?php

namespace Modules\PoolingBot\Telegram\Middleware;

use App\Models\TelegramUser;
use SergiX44\Nutgram\Nutgram;

class TelegramBotCollectChat
{
    public function __invoke(Nutgram $bot, $next): void
    {
        $botId = $bot->getData('bot_id');
        $user = $bot->user();
        if ($user === null) {
            return;
        }
        //save or update chat
        //TelegramBotCollectChatJob::dispatch($user);
        $chat = TelegramUser::updateOrCreate([
            'telegram_id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'username' => $user->username,
            'user_status' => null,
            'bot_id' => $botId,
        ]);

        $chat->save();

        $next($bot);
    }
}
