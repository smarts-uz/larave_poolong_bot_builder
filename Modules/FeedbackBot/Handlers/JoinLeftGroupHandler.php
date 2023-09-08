<?php

namespace Modules\FeedbackBot\Handlers;


use Modules\FeedbackBot\Services\BaseBotService;
use SergiX44\Nutgram\Nutgram;

class JoinLeftGroupHandler
{
    public function __invoke(Nutgram $bot)
    {
//        $botId = $bot->getData('botId');
//        $oldChatId = $bot->message()->chat->id;
//
//        if ($bot->chat()->isGroup() && $bot->message()->left_chat_member->id === $bot->getMe()->id) {
//
//            $baseBotService = new BaseBotService();
//            $baseBotService->deleteChat($oldChatId,$botId);
//            $bot->chatMember()->new_chat_member->status;
//        }
        $chatMemberId = $bot->chatMember()->new_chat_member->user->id;
        $chatId = $bot->chatMember()->chat->id;
        $chatTitle = $bot->chatMember()->chat->title;
        $botId = $bot->getMe()->id;
        $isGroup = $bot->chat()->isGroup();
        $status = $bot->chatMember()->new_chat_member->status;
        $id = $bot->getData('botId');


        $baseBotService = new BaseBotService();
        if ($isGroup && $chatMemberId === $botId && $status === 'member') {
            $baseBotService->setNewChat($chatId,$chatTitle,$id);

        }
        if ($isGroup && $chatMemberId === $botId && $status === 'left') {
            $baseBotService->deleteChat($chatId,$id);
        }
    }
}
