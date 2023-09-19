<?php

namespace App\Services;

use Barryvdh\Debugbar\Facades\Debugbar;

class TelegramBotFileCheck
{
    public function fileCheck($post)
    {
        try {
            $file_path = $post->media->file_name;
            $file_info = pathinfo($file_path);
            $file_extension = strtolower($file_info['extension']);

            if (in_array($file_extension, ['jpg', 'jpeg', 'png'])) {

                $fileContents = public_path('storage/' . $post->media->file_name);
                if (file_exists($fileContents)) {
                    return ['photo' , $fileContents];
                }

                Debugbar::info('Telegram Bot File Not Found');

            } elseif (in_array($file_extension, ['mp4', 'avi', 'mov', 'mkv', 'wmv', 'mpeg', 'mpg', '3gp', 'webm',])) {
                $fileContents = public_path('storage/' . $post->media->file_name);
                if (file_exists($fileContents)) {

                    return ['video' , $fileContents];

                } else {
                    Debugbar::info('Telegram Bot File Not Found');
                }
            }

        } catch (\Exception $exception) {
            Debugbar::info($exception);
        }
        return null;
    }

    public function closeFile($photo): void
    {
        if (is_resource($photo)) {
            fclose($photo);
        }
    }
}
