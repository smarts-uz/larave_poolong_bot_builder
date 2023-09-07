<?php

namespace Modules\PoolingBot\Services;



use Barryvdh\Debugbar\Facades\Debugbar;
use Modules\PoolingBot\Entities\Post;
use Modules\PoolingBot\Entities\PostUser;
use Modules\PoolingBot\Entities\TelegramUser;
use Modules\PoolingBot\Entities\TgBot;
use Modules\PoolingBot\Entities\TgGroup;
use SergiX44\Nutgram\Nutgram;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

class TelegramBotService
{
    private $buttonService;
    private $fileCheckService;

    public function __construct()
    {
        $this->buttonService = new TelegramBotButtonCreator();
        $this->fileCheckService = new TelegramBotFileCheck();
    }

    public function setCache($id)
    {
        $botToken = TgBot::where('id', $id)->first()->bot_token;
        $cacheDirectory = storage_path('cache/' . md5($botToken));

        $psr6Cache = new FilesystemAdapter('telegram_bot', '0', $cacheDirectory);
        $psr16Cache = new Psr16Cache($psr6Cache);

        return $psr16Cache;
    }

    public function getBotById($id)
    {
        return TgBot::where('id', $id)->first();
    }

    public function botSendMessage(Nutgram $bot, $post,$botId)
    {
        $tgGroups = TgGroup::where('tg_bot_id',$botId)->where('tg_bot_on',true)->get();

        [$media, $fileContents] = $this->fileCheckService->fileCheck($post);
        switch ($media) {
            case 'photo':
                $photo = fopen($fileContents, 'r+');
                if ($photo) {
                    $keyboard = $this->buttonService->botCreateInlineButtons($post);
                    foreach ($tgGroups as $group) {

                        $message = $bot->sendPhoto($photo, [
                            'chat_id' => $group->group_id,
                            'parse_mode' => 'html',
                            'caption' => $post->content,
                            'reply_markup' => $keyboard,
                        ]);

                        $isChannel = $message->chat->isChannel();


                        if ($isChannel) {
                            $this->saveChatId($post, $message);
                        }

                        $this->fileCheckService->closeFile($photo);
                        $this->saveMessageId($post, $message);
                    }

                }
                break;
            case 'video':

                $photo = fopen($fileContents, 'r+');
                if ($photo) {
                    $keyboard = $this->buttonService->botCreateInlineButtons($post);
                    foreach ($tgGroups as $group) {

                        $message = $bot->sendVideo($photo, [
                            'chat_id' => $group->group_id,
                            'parse_mode' => 'html',
                            'caption' => $post->content,
                            'reply_markup' => $keyboard,
                        ]);
                        $isChannel = $message->chat->isChannel();


                        if ($isChannel) {
                            $this->saveChatId($post, $message);
                        }

                        $this->fileCheckService->closeFile($photo);
                        $this->saveMessageId($post, $message);
                    }
                }
                break;
            default:
                Debugbar::info('Error');
        }
    }

    public function botDeleteMessage(Nutgram $bot, $post,$botId)
    {
        try {
            $tgGroups = TgGroup::where('tg_bot_id',$botId)->where('tg_bot_on',true)->get();
            foreach ($tgGroups as $group) {
                $bot->deleteMessage($group->group_id, $post->tg_message_id);
            }

        } catch (\Exception $exception) {
            Debugbar::info($exception);
        }
    }

    public function saveMessageId($post, $message)
    {
        $post->tg_message_id = $message->message_id;
        $post->saveQuietly();
    }
    public function saveChatId($post, $message)
    {
        $post->tg_chat_title = $message->chat->username;
        $post->tg_groups_id = $message->chat->id;
        $post->saveQuietly();
    }
    public function createPostTgUrl($post)
    {
        $tgChatTitle = $post->tg_chat_title;
        $tgMessageId = $post->tg_message_id;

        $post->tg_public_url = "https://t.me/{$tgChatTitle}/{$tgMessageId}";
        $post->saveQuietly();
    }
    public function botEditeMessage(Nutgram $bot, $chatId, $messageId, $caption,$post)
    {
        $keyboard = $this->buttonService->botCreateInlineButtons($post);

        $bot->editMessageCaption([
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'caption' => $caption,
            'reply_markup' => $keyboard,
            'parse_mode' => 'html',
        ]);
    }

    public function buttonsAction($messageId, $callbackData, $userId)
    {
        $post = Post::where('tg_message_id', $messageId)->first();
        $button = $post->button()->where('title', $callbackData)->first();
        $user = TelegramUser::where('telegram_id', $userId)->first();

        if (!empty($post && $button && $user)) {
            $postUser = PostUser::where('tg_user_id', $user->id)->where('tg_post_id', $post->id)->first();
            if (empty($postUser)) {
                PostUser::firstOrCreate([
                    'tg_user_id' => $user->id,
                    'tg_post_id' => $post->id,
                    'tg_button_id' => $button->id,
                    'bot_id' => $post->bot_id,
                ])->save();
                $button->increment('count');
                return 'notRated';
            }
            if (!empty($postUser)) {
                return 'rated';
            }

        }
        return null;
    }

    public static function execInBackground($cmd)
    {
        pclose(popen("start /B ". $cmd, "r"));
    }


}
