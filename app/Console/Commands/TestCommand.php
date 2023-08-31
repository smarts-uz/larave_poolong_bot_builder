<?php

namespace App\Console\Commands;

use App\Models\PostUser;
use App\Services\BotSetWebhookService;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $botSetvice = new BotSetWebhookService();
        $botSetvice->setWebhook('qweqweqwe');
    }
}
