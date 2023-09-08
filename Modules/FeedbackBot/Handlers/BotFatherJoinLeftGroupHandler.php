<?php

namespace Modules\FeedbackBot\Handlers;


use Modules\FeedbackBot\Services\BotFatherService;
use SergiX44\Nutgram\Nutgram;

class BotFatherJoinLeftGroupHandler
{
    public function __invoke(Nutgram $bot)
    {

        $chatMemberId = $bot->chatMember()->new_chat_member->user->id;
        $chatId = $bot->chatMember()->chat->id;
        $chatTitle = $bot->chatMember()->chat->title;
        $botId = $bot->getMe()->id;
        $isGroup = $bot->chat()->isGroup();
        $status = $bot->chatMember()->new_chat_member->status;



        $baseBotService = new BotFatherService();
        if ($isGroup && $chatMemberId === $botId && $status === 'member') {
            $baseBotService->setNewChat($chatId,$chatTitle,$botId);

        }
        if ($isGroup && $chatMemberId === $botId && $status === 'left') {
            $baseBotService->deleteChat($chatId,$botId);
        }
    }
}
