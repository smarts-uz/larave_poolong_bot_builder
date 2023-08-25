<?php

namespace App\Jobs;

use App\Events\TelegramBotButtonActionJobCompletedEvent;
use App\Models\Post;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TelegramBotButtonActionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $messageId;
    protected $callbackData;

    protected $post;
    public $queue = 'someJob';
    public function __construct($messageId,$callbackData,$post)
    {
        $this->messageId = $messageId;
        $this->callbackData = $callbackData;
        $this->post = $post;

    }

    public function handle(): void
    {
        try {
            if ($this->post) {
                $button = $this->post->button()->where('title', $this->callbackData)->first();
                if ($button) {
                    $button->increment('count');
                }
            }
        } catch (\Exception $exception) {
            Debugbar::info($exception);
        }
    }
}
