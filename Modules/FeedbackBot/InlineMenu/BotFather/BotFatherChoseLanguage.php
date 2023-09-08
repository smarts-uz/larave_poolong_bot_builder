<?php

namespace Modules\FeedbackBot\InlineMenu\BotFather;


use Modules\FeedbackBot\Services\BotFatherService;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class BotFatherChoseLanguage extends InlineMenu
{
    private $data;
    public function start(Nutgram $bot)
    {
        $this->clearButtons();
        $this->menuText("Выберите язык бота\n\nSelect bot language\n\nBot tilini tanlang");

        $this->addButtonRow(
            InlineKeyboardButton::make('Русский', callback_data: 'ru@setLanguage'),
            InlineKeyboardButton::make('English', callback_data: 'en@setLanguage'),
            InlineKeyboardButton::make("O'zbek", callback_data: 'uz@setLanguage'),
        )->orNext('start');

        $this->showMenu();
    }

    public function setLanguage(Nutgram $bot)
    {
        $data = $bot->callbackQuery()->data;

        $userId = $bot->userId();

        $lang = new BotFatherService();
        $lang->setLanguage($userId,$data);

        $this->end();

        MainMenu::begin($bot);
    }
}
