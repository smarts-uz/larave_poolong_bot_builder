<?php

namespace Modules\PoolingBot\Services;



use Modules\PoolingBot\Entities\TgGroup;

class TelegramBotGroupService
{
    public function setNewChat($groupId,$title,$tgBotId,$isChannel)
    {
        TgGroup::updateOrCreate([
            'group_id' => $groupId,
            'title' => $title,
            'tg_bot_id' => $tgBotId,
            'is_channel' => $isChannel,
        ]);
    }

    public function deleteChat($groupId,$botId)
    {
        TgGroup::where('group_id', $groupId)->where('tg_bot_id', $botId)->delete();
    }

    public function updateChatTitle($groupId,$botId,$newTitle)
    {
        TgGroup::where('group_id',$groupId)->where('tg_bot_id',$botId)->update(['title'=>$newTitle]);
    }
}
