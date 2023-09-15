<?php

namespace Modules\FeedbackBot\InlineMenu\BaseBot;

use Modules\FeedbackBot\Entities\FeedbackUserChat;
use Modules\FeedbackBot\Entities\Newslatter;
use Modules\FeedbackBot\Jobs\BulkMessengerJob;
use Modules\FeedbackBot\Services\BaseBotService;
use Modules\FeedbackBot\Services\BotFatherService;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class NewsletterMenu extends InlineMenu
{
    protected $value;
    protected $allUsers;
    protected $fromChatId;
    protected $msgId;
    protected $currentChatId;
    protected $userLocale;
    protected $botText;

    public function start(Nutgram $bot)
    {
        $userId = $bot->user()->id;
        $botId = $bot->getMe()->id;

        $service = new BaseBotService();
        $value =  $service->getLanguage($userId,$botId);

        $this->userLocale = $value;

        $this->botText = Newslatter::query()->where('id',1)->first();
        //$this->botInputText = $this->botJsonText->getTranslation('user_bot_input_text', $this->userLocale,false);

        $this->clearButtons();
        $this->menuText($this->botText->getTranslation('main_menu_text',$this->userLocale,false), [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
        ]);

        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslation('main_menu_all_button',$this->userLocale,false), callback_data: 'allUser@allUserSender')
        );
        $this->addButtonRow(
            InlineKeyboardButton::make('Русский 🇷🇺', callback_data: 'ruUser@ruUserSender'),
            InlineKeyboardButton::make('English 🇺🇸', callback_data: 'enUser@enUserSender'),
            InlineKeyboardButton::make("O'zbek 🇺🇿", callback_data: 'uzUser@uzUserSender'),
        );
        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslation('cancel_button',$this->userLocale,false), callback_data: 'cancel@cancelNewsletter')
        );

        $this->showMenu();
    }

    public function allUserSender(Nutgram $bot)
    {
        $this->allUsers = FeedbackUserChat::where('bot_id', $bot->getMe()->id)->pluck('chat_id')->toArray();
        $userCount = count($this->allUsers);
        $this->clearButtons();

        $this->menuText($this->botText->getTranslation('all_menu_text',$this->userLocale,false) . $userCount, [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
        ])
            ->orNext('setAllUserSender');

        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslation('cancel_button',$this->userLocale,false), callback_data: 'cancel@cancelNewsletter')
        );

        $this->showMenu();
    }

    /**
     *
     * Function  setAllUserSender
     * @param Nutgram $bot
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function setAllUserSender(Nutgram $bot)
    {

        $this->value = $bot->message()->text;

        $this->currentChatId = $bot->message()->chat->id;
        $this->fromChatId = $bot->message()->from->id;
        $this->msgId = $bot->messageId();

        $this->closeMenu();
        $this->clearButtons();

        $this->menuText($this->botText->getTranslation('save_message_text',$this->userLocale,false), [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
        ]);

        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslation('preview_button',$this->userLocale,false), callback_data: 'preview@previewNewsletter'),
            InlineKeyboardButton::make($this->botText->getTranslation('cancel_button',$this->userLocale,false), callback_data: 'cancel@cancelNewsletter')
        );
        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslation('start_newslatter_button',$this->userLocale,false), callback_data: 'startNewsletter@startAllNewsletter')
        );
        $this->showMenu();

    }

    public function previewNewsletter(Nutgram $bot)
    {
        $this->closeMenu();
        $this->closeMenu();
        $bot->sendMessage($this->botText->getTranslation('newslatter_preview_text',$this->userLocale,false));
        $bot->copyMessage($this->currentChatId, $this->fromChatId,$this->msgId);
        $this->showPreviewMenu($bot);
    }

    public function showPreviewMenu(Nutgram $bot)
    {
        $this->closeMenu();
        $this->clearButtons();

        $this->menuText($this->botText->getTranslation('save_message_text',$this->userLocale,false), [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
        ]);

        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslation('preview_button',$this->userLocale,false), callback_data: 'preview@previewNewsletter'),
            InlineKeyboardButton::make($this->botText->getTranslation('cancel_button',$this->userLocale,false), callback_data: 'cancel@cancelNewsletter')
        );
        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslation('start_newslatter_button',$this->userLocale,false), callback_data: 'startNewsletter@startAllNewsletter')
        );
        $this->showMenu();
    }
    public function startAllNewsletter(Nutgram $bot)
    {
        BulkMessengerJob::dispatch($bot, $this->allUsers, $this->fromChatId,$this->msgId );
    }
    public function ruUserSender(Nutgram $bot)
    {
        $this->allUsers = FeedbackUserChat::where('bot_id', $bot->getMe()->id)->where('language_code', 'ru')->pluck('chat_id')->toArray();
        $userCount = count($this->allUsers);

        $this->clearButtons();

        $this->menuText($this->botText->getTranslation('ru_menu_text',$this->userLocale,false) . $userCount, [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
        ]);

        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslation('cancel_button',$this->userLocale,false), callback_data: 'cancel@cancelNewsletter')
        )->orNext('setAllUserSender');

        $this->showMenu();
    }

    public function enUserSender(Nutgram $bot)
    {
        $this->allUsers = FeedbackUserChat::where('bot_id', $bot->getMe()->id)->where('language_code', 'en')->pluck('chat_id')->toArray();
        $userCount = count($this->allUsers);

        $this->clearButtons();

        $this->menuText($this->botText->getTranslation('en_menu_text',$this->userLocale,false) . $userCount, [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
        ]);

        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslation('cancel_button',$this->userLocale,false), callback_data: 'cancel@cancelNewsletter')
        )->orNext('setAllUserSender');

        $this->showMenu();
    }
    public function uzUserSender(Nutgram $bot)
    {
        $this->allUsers = FeedbackUserChat::where('bot_id', $bot->getMe()->id)->where('language_code', 'uz')->pluck('chat_id')->toArray();
        $userCount = count($this->allUsers);

        $this->clearButtons();

        $this->menuText($this->botText->getTranslation('uz_menu_text',$this->userLocale,false) . $userCount, [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
        ]);

        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslation('cancel_button',$this->userLocale,false), callback_data: 'cancel@cancelNewsletter')
        )->orNext('setAllUserSender');

        $this->showMenu();
    }
    public function cancelNewsletter(Nutgram $bot)
    {
        $this->closeMenu();
        $this->closeMenu();
        $this->end();
    }
}
