<?php

namespace App\Observers;

use App\Models\Post;
use App\Service\TelegramBotService;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Webhook;

class PostObserver
{
    public function created(Post $post): void
    {
        dd($post->is_published);
    }


    public function updated(Post $post): ?Post
    {
        if ($post->isDirty('is_published') && $post->is_published == true) {
            $botService = new TelegramBotService();
            $cache = $botService->setCache();

            $bot = new Nutgram($_ENV['TELEGRAM_TOKEN'],
                ['cache' => $cache]);

            $bot->setRunningMode(Webhook::class);

            $bot->sendMessage($post->content,['chat_id' => 3182829]);

            $bot->run();
        }
        return null;
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
