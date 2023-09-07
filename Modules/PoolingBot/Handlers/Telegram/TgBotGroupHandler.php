<?php

namespace Modules\PoolingBot\Handlers\Telegram;

use App\Services\TelegramBotGroupService;
use SergiX44\Nutgram\Nutgram;

class TgBotGroupHandler
{

    public function __invoke(Nutgram $bot)
    {
        $chatMemberId = $bot->chatMember()->new_chat_member->user->id;
        $chatId = $bot->chatMember()->chat->id;
        $chatTitle = $bot->chatMember()->chat->title;
        $botId = $bot->getMe()->id;
        $isChannel = $bot->chat()->isChannel();
        $status = $bot->chatMember()->new_chat_member->status;
        $tgBotId = $bot->getData('bot_id');

        $tgGroupService = new TelegramBotGroupService();

        if ($chatMemberId === $botId && $status === 'member') {
            $tgGroupService->setNewChat($chatId,$chatTitle,$tgBotId,$isChannel);
        }
        if ($chatMemberId === $botId && $status === 'administrator') {
            $tgGroupService->setNewChat($chatId,$chatTitle,$tgBotId,$isChannel);
        }
        if ($chatMemberId === $botId && $status === 'left') {
            $tgGroupService->deleteChat($chatId,$tgBotId);
        }
        if ($chatMemberId === $botId && $status === 'kicked') {
            $tgGroupService->deleteChat($chatId,$tgBotId);
        }
    }
}
