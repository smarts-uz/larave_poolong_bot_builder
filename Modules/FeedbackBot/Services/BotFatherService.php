<?php

namespace Modules\FeedbackBot\Services;


use Modules\FeedbackBot\Entities\BotFatherChat;
use Modules\FeedbackBot\Entities\Chat;
use Modules\FeedbackBot\Entities\FeedbackUserChat;
use Modules\FeedbackBot\Entities\TelegramBot;
use Modules\FeedbackBot\Entities\TelegramUserChat;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

class BotFatherService
{
    public function setCache()
    {
        $cacheDirectory = storage_path('cache/' . md5($_ENV['TELEGRAM_TOKEN']));

        $psr6Cache = new FilesystemAdapter('telegram_bot','0',$cacheDirectory);
        $psr16Cache = new Psr16Cache($psr6Cache);

        return $psr16Cache;
    }

    public function getMyBots($userId)
    {
        $myBots = TelegramBot::where('user_id', $userId)->get();

        return $myBots;
    }

    public function getBotUsername($botId, $userId)
    {
        $botUsername = TelegramBot::where('id', $botId)->where('user_id', $userId)->value('username');

        return $botUsername;
    }

    public function getBotChats($botId)
    {
        $botChats = Chat::where('telegram_bot_id', $botId)->get();

        return $botChats;
    }

    public function updateLanguageCode($botChatId,$botId,$languageCode)
    {
        Chat::where('chat_id', $botChatId)
            ->where('telegram_bot_id', $botId)
            ->update(['language_code'=>$languageCode]);
    }

    public function getBotStats($botId)
    {
        $statBot = TelegramBot::where('id', $botId)->first();

        return $statBot;

    }

    public function getUserCount($botId)
    {
        $countOfUser = FeedbackUserChat::where('bot_id', $botId)->count();

        return $countOfUser;
    }
    public function getBotToken($botId, $userId)
    {
        $botToken = TelegramBot::where('id', $botId)->where('user_id', $userId)->value('bot_token');

        return $botToken;
    }

    public function deleteBotFromDataBase($botId,$userId)
    {
        TelegramBot::where('id', $botId)->where('user_id', $userId)->delete();
    }

    public function setLanguage($userId, $data)
    {
        TelegramUserChat::where('chat_id', $userId)
            ->update(['language_code' => $data]);
    }
    public function findByLanguageCode($chatId)
    {

        $laguageCode = TelegramUserChat::where('chat_id', $chatId)
            ->value('language_code');
        return $laguageCode;
    }
    public function getLanguage($userId)
    {
        $chat = TelegramUserChat::where('chat_id', $userId)
            ->value('language_code');

        return $chat;
    }
    public function setNewChat($chatId,$chatTitle,$botId)
    {
        BotFatherChat::updateOrCreate([
            'chat_id' => $chatId,
            'title' => $chatTitle,
            'telegram_bot_id' => $botId,
        ]);

    }
    public function deleteChat($oldChatId, $botId)
    {
        BotFatherChat::where('chat_id', $oldChatId)->where('telegram_bot_id',$botId)->delete();
    }

    public function getBotFatherChat($telegram_bot_id)
    {
        $lastChat = BotFatherChat::where('telegram_bot_id', $telegram_bot_id)
            ->latest()
            ->value('chat_id');

        return $lastChat;
    }
    public function updateChatTitle($botId, $groupId,$newChatTitle)
    {
        BotFatherChat::where('telegram_bot_id', $botId)->where('chat_id', $groupId)->update(['title'=>$newChatTitle]);
    }

    public function getBotTranslation($botId, $userId)
    {
        $bot = TelegramBot::where('id' , $botId)->where('user_id', $userId)->first();

        return $bot;
    }

    public function setPaymentsSetting($donationValue, $botText, $userLocale)
    {

        $description = $botText->getTranslatedAttribute('payment_description', $userLocale, 'fallbackLocale');

        switch ($donationValue) {
            case 1:
                $value = 100;
                $title = $botText->getTranslatedAttribute('one_month_title', $userLocale, 'fallbackLocale');
                break;
            case 3:
                $value = 250;
                $title = $botText->getTranslatedAttribute('three_month_title', $userLocale, 'fallbackLocale');
                break;
            case 6:
                $value = 500;
                $title = $botText->getTranslatedAttribute('six_month_title', $userLocale, 'fallbackLocale');
                break;
            case 12:
                $value = 1000;
                $title = $botText->getTranslatedAttribute('twelve_month_title', $userLocale, 'fallbackLocale');
                break;
            default:
                $value = 1;
        }

        $result = array(
            'description' => $description,
            'value' => $value,
            'title' => $title,
        );

        return $result;
    }

}
