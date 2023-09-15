<?php

namespace Modules\FeedbackBot\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\FeedbackBot\Services\BulkMessengerService;
use SergiX44\Nutgram\Nutgram;

class BulkMessengerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected Nutgram $bot;

    /**
     * @var array
     */

    protected $chats = array();
    /**
     * @var array
     */
    protected $value;

    /**
     * @var array
     */
    protected $fromChatId;
    protected $messageId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($bot, $chats, $fromChatId,$messageId)
    {
        $this->bot = $bot;
        $this->chats = $chats;
        $this->fromChatId = $fromChatId;
        $this->messageId = $messageId;
     }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $bulkMessenger = new BulkMessengerService();
        $bulkMessenger->startBulkMessenger($this->bot,$this->chats,$this->fromChatId, $this->messageId);
    }
}
