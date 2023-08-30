<?php

namespace App\Jobs;

use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class ArtisanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $item;
    public function __construct($item)
    {
        $this->item = $item;
    }

    public function handle(): void
    {
        try {
            Artisan::call('tg:bot', ['id' => $this->item->id]);
        } catch (\Exception $exception) {
            Debugbar::error($exception);
        }
    }
}
