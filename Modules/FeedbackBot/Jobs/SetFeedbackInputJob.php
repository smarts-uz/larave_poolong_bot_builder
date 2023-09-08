<?php

namespace Modules\FeedbackBot\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\FeedbackBot\Services\BotFatherService;

class SetFeedbackInputJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chatId;
    protected $botId;
    protected $botTextLocale;
    protected $value;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($chatId, $botId,$botTextLocale, $value)
    {
        $this->chatId = $chatId;
        $this->botId = $botId;
        $this->botTextLocale = $botTextLocale;
        $this->value = $value;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $service = new BotFatherService();

        $currentBot = $service->getBotTranslation($this->botId, $this->chatId);

        $currentBot->setTranslation('user_bot_input_text', $this->botTextLocale, $this->value);
        $currentBot->save();
    }
}
