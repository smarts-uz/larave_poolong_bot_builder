<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\TelegramBotButtonCreator;
use App\Services\TelegramBotService;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Webhook;

class PostObserver
{
    public function created(Post $post): void
    {

    }


    public function updated(Post $post): void
    {

        $botService = new TelegramBotService();
        $bot = new Nutgram($_ENV['TELEGRAM_TOKEN']);

        if ($post->isDirty('is_published')) {
            if ($post->is_published == true) {
                $botService->botSendMessage($bot, $post);

            } else {
                $botService->botDeleteMessage($bot, $post);
            }
        }
        if ($post->isDirty('telegram_message_id')) {
            echo 'Done';
        }
    }

    public function deleted(Post $post): void
    {
    }

    public function restored(Post $post): void
    {
    }

    public function forceDeleted(Post $post): void
    {
    }
}
