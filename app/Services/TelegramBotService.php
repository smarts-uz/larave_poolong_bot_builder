<?php

namespace App\Services;



use App\Models\Post;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Storage;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

class TelegramBotService
{
    public function setCache()
    {
        $cacheDirectory = storage_path('cache/' . md5($_ENV['TELEGRAM_TOKEN']));

        $psr6Cache = new FilesystemAdapter('telegram_bot','0',$cacheDirectory);
        $psr16Cache = new Psr16Cache($psr6Cache);

        return $psr16Cache;
    }

    public function botSendMessage(Nutgram $bot, $post)
    {
        try {
            $file_path = $post->media->file_name;
            $file_info = pathinfo($file_path);
            $file_extension = strtolower($file_info['extension']);

            if (in_array($file_extension, ['jpg', 'jpeg', 'png'])) {

                $fileContents = public_path('storage/' . $post->media->file_name);
                if (file_exists($fileContents)) {

                    $buttons = [];
                    foreach ($post->button as $item) {

                        $button = InlineKeyboardButton::make($item->title,callback_data: $item->title);

                        $buttons[] = $button;
                    }
                    $keyboard = InlineKeyboardMarkup::make()->addRow(...$buttons);

                    $photo = fopen($fileContents, 'r+');
                    $message = $bot->sendPhoto($photo,[
                        'chat_id' => 3182829,
                        'parse_mode' => 'html',
                        'caption' => $post->content,
                        'reply_markup' => $keyboard,
                    ]);
                    fclose($photo);
                } else {
                    Debugbar::info('Not Found');
                }

            } elseif (in_array($file_extension, ['mp4', 'avi', 'mov', 'mkv', 'wmv', 'mpeg', 'mpg', '3gp', 'webm',])) {
                $fileContents = public_path('storage/' . $post->media->file_name);
                if (file_exists($fileContents)) {

                    $buttons = [];
                    foreach ($post->button as $item) {

                        $button = InlineKeyboardButton::make($item->title,callback_data: $item->title);

                        $buttons[] = $button;
                    }
                    $keyboard = InlineKeyboardMarkup::make()->addRow(...$buttons);

                    $photo = fopen($fileContents, 'r+');
                    $message = $bot->sendVideo($photo,[
                        'chat_id' => 3182829,
                        'parse_mode' => 'html',
                        'caption' => $post->content,
                        'reply_markup' => $keyboard,
                    ]);
                    fclose($photo);
                } else {
                    Debugbar::info('Not Found');
                }
            }

            return $message->message_id;

        } catch (\Exception $exception) {
            Debugbar::info($exception);
            return null;
        }
    }
    public function botDeleteMessage(Nutgram $bot, $post)
    {
        try {
            $bot->deleteMessage(3182829,$post->telegram_message_id);
        } catch (\Exception $exception) {
            Debugbar::info($exception);
        }
    }
}
