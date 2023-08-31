<?php

namespace App\Services;


use App\Models\Post;
use App\Models\PostUser;
use App\Models\TelegramUser;
use App\Models\TgBot;
use App\Models\TgGroup;
use Barryvdh\Debugbar\Facades\Debugbar;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;
use function Symfony\Component\Translation\t;

class TelegramBotService
{
    private $buttonService;
    private $fileCheckService;

    public function __construct()
    {
        $this->buttonService = new TelegramBotButtonCreator();
        $this->fileCheckService = new TelegramBotFileCheck();
    }

    public function setCache()
    {
        $cacheDirectory = storage_path('cache/' . md5($_ENV['TELEGRAM_TOKEN']));

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
                        $this->fileCheckService->closeFile($photo);
                        $this->saveChatId($post, $message);
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

                        $this->fileCheckService->closeFile($photo);
                        $this->saveChatId($post, $message);
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

    public function saveChatId($post, $message)
    {
        $post->tg_message_id = $message->message_id;
        $post->saveQuietly();
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
