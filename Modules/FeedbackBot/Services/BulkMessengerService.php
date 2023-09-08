<?php

namespace Modules\FeedbackBot\Services;

use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;

class BulkMessengerService
{
    public function startBulkMessenger(Nutgram $bot, $chats, $fromChatId,$messageId)
    {
        try {
            $bot->getBulkMessenger()
                ->setChats($chats)
                ->setInterval(5)
                ->using(fn (Nutgram $bot, int $chatId) =>$bot->copyMessage($chatId, $fromChatId, $messageId))
                ->startSync();

        } catch (\Exception $e) {
            Log::channel('telegram')->info('LOG',['LOG:'=> $e]);
        }
    }
}
