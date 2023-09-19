<?php

namespace Modules\FeedbackBot\Services;


use Modules\FeedbackBot\Entities\Chat;
use Modules\FeedbackBot\Entities\FeedbackUserChat;
use Modules\FeedbackBot\Entities\TelegramBot;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

class BaseBotService
{
    public function getBotToken($id)
    {
        $botToken = TelegramBot::where('id', $id)->value('bot_token');

        return $botToken;
    }

    public function setCacheStorage($botToken)
    {
        $cacheDirectory = storage_path('cache/' . md5($botToken));

        return $cacheDirectory;
    }

    public function setCacheAdapter($cacheDirectory)
    {
        $psr6Cache = new FilesystemAdapter('telegram_bot','0',$cacheDirectory);
        $psr16Cache = new Psr16Cache($psr6Cache);

        return $psr16Cache;
    }

    public function setBotId($id,$botId)
    {
        $value = TelegramBot::where('id', $id)->first();
        $value->bot_id = $botId;
        $value->save();
    }
    public function getLanguage($userId, $botId)
    {
        $chat = FeedbackUserChat::where('chat_id', $userId)
            ->where('bot_id', $botId)
            ->value('language_code');

        return $chat;
    }



    public function setLanguage($userId, $botId, $data)
    {

//        $chat = FeedbackUserChat::where('chat_id', $userId)
//            ->where('bot_id', $botId)
//            ->first();
//        Log::channel('telegram')->info('Language', ['LANG:'=> $chat]);
//        if ($chat) {
//
//            $chat->language_code = $data;
//            Log::channel('telegram')->info('language_code', ['CODE:'=> $chat->language_code]);
//            $chat->save();
//        }
        FeedbackUserChat::where('chat_id', $userId)
            ->where('bot_id', $botId)
            ->update(['language_code' => $data]);
    }


    /***
     * Function  languageCodeValidation
     * @param $telegram_bot_id
     * @param $requiredLanguageCode []
     * @return  mixed
     */
    public function languageCodeValidation($telegram_bot_id, $requiredLanguageCode)
    {
        $lastChat = Chat::where('telegram_bot_id', $telegram_bot_id)
            ->where('language_code', $requiredLanguageCode)
            ->latest()
            ->value('chat_id');

        return $lastChat;
    }

    public function findByLanguageCode($botId, $chatId)
    {

        $laguageCode = FeedbackUserChat::where('chat_id', $chatId)
            ->where('bot_id',$botId)
            ->value('language_code');
        return $laguageCode;
    }

    public function findLatestChat($botId)
    {
        $chatId = Chat::where('telegram_bot_id', $botId)->latest()->value('chat_id');

        return $chatId;
    }

    public function adminValidation($userName,$userId)
    {
        $currentUser = TelegramBot::where('username', $userName)
            ->where('user_id', $userId)
            ->value('user_id');

        return $currentUser;
    }

    public function updateChatTitle($botId, $groupId,$newChatTitle)
    {
        Chat::where('telegram_bot_id', $botId)->where('chat_id', $groupId)->update(['title'=>$newChatTitle]);
    }

    public function setNewChat($chatId,$chatTitle,$botId)
    {
        Chat::updateOrCreate([
            'chat_id' => $chatId,
            'title' => $chatTitle,
            'telegram_bot_id' => $botId,
        ]);

    }

    public function deleteChat($oldChatId, $botId)
    {
        Chat::where('chat_id', $oldChatId)->where('telegram_bot_id',$botId)->delete();
    }
}
