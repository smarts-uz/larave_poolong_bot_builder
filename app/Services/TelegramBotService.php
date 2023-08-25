<?php

namespace App\Services;


use App\Models\Post;
use App\Models\PostUser;
use App\Models\TelegramUser;
use Barryvdh\Debugbar\Facades\Debugbar;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
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

    public function setCache()
    {
        $cacheDirectory = storage_path('cache/' . md5($_ENV['TELEGRAM_TOKEN']));

        $psr6Cache = new FilesystemAdapter('telegram_bot', '0', $cacheDirectory);
        $psr16Cache = new Psr16Cache($psr6Cache);

        return $psr16Cache;
    }

    public function botSendMessage(Nutgram $bot, $post)
    {
        [$media, $fileContents] = $this->fileCheckService->fileCheck($post);
        switch ($media) {
            case 'photo':
                $photo = fopen($fileContents, 'r+');
                if ($photo) {
                    $keyboard = $this->buttonService->botCreateInlineButtons($post);
                    $message = $bot->sendPhoto($photo, [
                        'chat_id' => $_ENV['TELEGRAM_BOT_GROUP_ID'],
                        'parse_mode' => 'html',
                        'caption' => $post->content,
                        'reply_markup' => $keyboard,
                    ]);
                    $this->fileCheckService->closeFile($photo);
                    $this->saveChatId($post, $message);

                }
                break;
            case 'video':

                $photo = fopen($fileContents, 'r+');
                if ($photo) {
                    $keyboard = $this->buttonService->botCreateInlineButtons($post);
                    $message = $bot->sendVideo($photo, [
                        'chat_id' => $_ENV['TELEGRAM_BOT_GROUP_ID'],
                        'parse_mode' => 'html',
                        'caption' => $post->content,
                        'reply_markup' => $keyboard,
                    ]);

                    $this->fileCheckService->closeFile($photo);
                    $this->saveChatId($post, $message);
                }
                break;
            default:
                Debugbar::info('Error');
        }
    }

    public function botDeleteMessage(Nutgram $bot, $post)
    {
        try {
            $bot->deleteMessage($_ENV['TELEGRAM_BOT_GROUP_ID'], $post->telegram_message_id);
        } catch (\Exception $exception) {
            Debugbar::info($exception);
        }
    }

    public function saveChatId($post, $message)
    {
        $post->telegram_message_id = $message->message_id;
        $post->saveQuietly();
    }

    public function buttonsAction($messageId, $callbackData, $userId)
    {
        $post = Post::where('telegram_message_id', $messageId)->first();
        $button = $post->button()->where('title', $callbackData)->first();
        $user = TelegramUser::where('telegram_id', $userId)->first();

        if (!empty($post && $button && $user)) {
            $postUser = PostUser::where('user_id', $user->id)->where('post_id', $post->id)->first();
            if (empty($postUser)) {
                PostUser::firstOrCreate([
                    'user_id' => $user->id,
                    'post_id' => $post->id,
                    'button_id' => $button->id,
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


}
