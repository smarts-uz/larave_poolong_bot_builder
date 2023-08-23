<?php

namespace App\Services;

use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class TelegramBotButtonCreator
{
    public function botCreateInlineButtons($post)
    {

        $buttons = [];
        $keyboards = [];
        foreach ($post->button as $item) {

            $button = InlineKeyboardButton::make($item->title . " ({$item->count})",callback_data: $item->title);

            $buttons[] = $button;
            $keyboards[] = $buttons;
            $buttons = [];
        }
        $keyboard = InlineKeyboardMarkup::make();
        foreach ($keyboards as $k) {
            $keyboard->addRow(...$k);
        }
        return $keyboard;
    }
}
