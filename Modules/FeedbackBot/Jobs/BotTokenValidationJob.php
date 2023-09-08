<?php

namespace Modules\FeedbackBot\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\FeedbackBot\Services\BotTokenValidationService;

class BotTokenValidationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $botToken;

    protected $userId;

    protected $response;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($botToken, $userId)
    {
        $this->botToken = $botToken;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tokenValidation = new BotTokenValidationService();
        $tokenValidation->validateBotToken($this->botToken, $this->userId);
    }


}
