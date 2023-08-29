<?php

namespace App\Console\Commands;

use App\Models\PostUser;
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
        $postUser = PostUser::find(18);
        $relatedPost = $postUser->posts->title; // Получить связанный пост
        $relatedUser = $postUser->users->username; // Получить связанного пользователя
        $relatedButton = $postUser->buttons->title;
        dd($relatedButton);
    }
}
