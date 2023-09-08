<?php

namespace Modules\FeedbackBot\InlineMenu\BaseBot;


use Modules\FeedbackBot\Conversations\FeedBackConversation;
use Modules\FeedbackBot\Services\BaseBotService;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class ChoseLangugage extends InlineMenu
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

        $botId = $bot->getMe()->id;

        $lang = new BaseBotService();
        $lang->setLanguage($userId,$botId,$data);

        $this->end();

        FeedBackConversation::begin($bot);
    }
}
