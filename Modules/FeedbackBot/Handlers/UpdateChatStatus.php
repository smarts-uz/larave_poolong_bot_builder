<?php

namespace Modules\FeedbackBot\Handlers;


use Modules\FeedbackBot\Entities\FeedbackUserChat;
use SergiX44\Nutgram\Nutgram;

class UpdateChatStatus
{
    public function __invoke(Nutgram $bot): void
    {
        $user = FeedbackUserChat::where('bot_id', $bot->getMe()->id)->where('chat_id', $bot->chat()->id)->first();
        $status = $bot->chatMember()->new_chat_member->status;
        switch ($status) {
            case 'member':
                $user->user_chat_status = 'member';
                $user->save();
                break;
            default:
                $user->user_chat_status = 'kicked';
                $user->save();
                break;

        }
    }
}
