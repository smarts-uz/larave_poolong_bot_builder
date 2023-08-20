<?php

namespace App\Service;


use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

class TelegramBotService
{
    public function setCache()
    {
        $cacheDirectory = storage_path('cache/' . md5($_ENV['TELEGRAM_TOKEN']));

        $psr6Cache = new FilesystemAdapter('telegram_bot','0',$cacheDirectory);
        $psr16Cache = new Psr16Cache($psr6Cache);

        return $psr16Cache;
    }
}
