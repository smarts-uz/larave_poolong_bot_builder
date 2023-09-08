<?php

namespace Modules\FeedbackBot\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\FeedbackBot\Services\BotFatherService;

class SetDefaultFeedbackInputJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chatId;
    protected $botId;
    protected $botTextLocale;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($chatId, $botId,$botTextLocale)
    {
        $this->chatId = $chatId;
        $this->botId = $botId;
        $this->botTextLocale = $botTextLocale;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $value = '';

        $service = new BotFatherService();
        $currentBot = $service->getBotTranslation($this->botId, $this->chatId);

        $isEmptyValue = $currentBot->getTranslation('user_bot_input_text', $this->botTextLocale, false);

        if (!empty($isEmptyValue)) {
            $currentBot->setTranslation('user_bot_input_text', $this->botTextLocale, $value);
            $currentBot->save();
        }
    }
}
