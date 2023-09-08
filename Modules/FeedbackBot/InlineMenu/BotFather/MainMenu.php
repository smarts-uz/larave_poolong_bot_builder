<?php

namespace Modules\FeedbackBot\InlineMenu\BotFather;

use Lukasss93\ModelSettings\Managers\TableSettingsManager;
use Modules\FeedbackBot\Entities\BotFatherTextModel;
use Modules\FeedbackBot\Jobs\SetDefaultFeedbackInputJob;
use Modules\FeedbackBot\Jobs\SetDefaultFeedbackResponseJob;
use Modules\FeedbackBot\Jobs\SetFeedbackInputJob;
use Modules\FeedbackBot\Jobs\SetFeedbackResponseJob;
use Modules\FeedbackBot\Services\BotFatherService;
use Modules\FeedbackBot\Services\BotRemoveWebhookService;
use Modules\FeedbackBot\Services\BotSetWebhookService;
use Modules\FeedbackBot\Services\BotTokenValidationService;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class MainMenu extends InlineMenu
{
    protected $message;
    protected $messageText;
    protected $userId;
    protected bool $success = false;
    protected TableSettingsManager $settings;
    protected bool $reopen = false;
    protected $botId;
    protected $botChatId;
    protected $userLocale;
    protected $botText;
    protected $botTextLocale;



    public function start(Nutgram $bot)
    {
        $userId = $bot->user()->id;

        $service = new BotFatherService();
        $value = $service->getLanguage($userId);

        $this->userLocale = $value;
        $this->botText = BotFatherTextModel::withTranslation($this->userLocale)->first();

//        $this->settings = $this->settings ?? $bot->getData(TelegramUserChat::class)->settings();

        $this->menuText($this->botText->getTranslatedAttribute('main_menu_text', $this->userLocale, 'fallbackLocale'), [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
        ])->clearButtons()
            ->addButtonRow(
                InlineKeyboardButton::make($this->botText->getTranslatedAttribute('main_menu_bot_set_button', $this->userLocale, 'fallbackLocale'), callback_data: 'add_bot@handleBotCreate'),
                InlineKeyboardButton::make($this->botText->getTranslatedAttribute('main_menu_bot_control_button', $this->userLocale, 'fallbackLocale'), callback_data: 'my_bots@myBots'))
            ->addButtonRow(
                InlineKeyboardButton::make($this->botText->getTranslatedAttribute('main_menu_bot_help_button', $this->userLocale, 'fallbackLocale'), callback_data: 'help@help'))
            ->addButtonRow(
                InlineKeyboardButton::make($this->botText->getTranslatedAttribute('main_menu_premium_button', $this->userLocale, 'fallbackLocale'), callback_data: 'premium@premiumMenu')
            )
            ->orNext('none')
            ->showMenu();
    }

    public function premiumMenu(Nutgram $bot)
    {
        $this->clearButtons();

        $this->menuText("{$this->botText->getTranslatedAttribute('premium_menu_text',$this->userLocale,'fallbackLocale')}", [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
        ]);

        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('premium_menu_one_month_button', $this->userLocale, 'fallbackLocale'), callback_data: '1@setPremium')
        );
        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('premium_menu_three_month_button', $this->userLocale, 'fallbackLocale'), callback_data: '3@setPremium')
        );
        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('premium_menu_six_month_button', $this->userLocale, 'fallbackLocale'), callback_data: '6@setPremium')
        );
        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('premium_menu_twelve_month_button', $this->userLocale, 'fallbackLocale'), callback_data: '12@setPremium')
        );
        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('back_button', $this->userLocale, 'fallbackLocale'), callback_data: 'back@start')
        );
        $this->showMenu();
    }

    public function setPremium(Nutgram $bot,string $data)
    {

        $donationValue = (int)$data;

        $this->clearButtons();
        $this->menuText("{$this->botText->getTranslatedAttribute('premium_menu_month_text',$this->userLocale,'fallbackLocale')}", [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
        ]);
        $this > $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('premium_menu_click_button', $this->userLocale, 'fallbackLocale'), callback_data: "{$donationValue}@clickPay")
        );
        $this > $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('premium_menu_payme_button', $this->userLocale, 'fallbackLocale'), callback_data: "{$donationValue}@paymePay")
        );
        $this > $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('back_button', $this->userLocale, 'fallbackLocale'), callback_data: 'back@premiumMenu')
        );
        $this->showMenu();
    }

    public function clickPay(Nutgram $bot, string $data)
    {

        $donationValue = (int)$data;

        $botFatherService = new BotFatherService();
        $settings = $botFatherService->setPaymentsSetting($donationValue, $this->botText, $this->userLocale);

        $description = $settings['description'];
        $title = $settings['title'];
        $value = $settings['value'];

        $bot->sendInvoice(
            $title,
            $description,
            'click',
            $_ENV['PAYMENT_PROVIDER_TOKEN_CLICK'],
            'RUB',
            [['label' => "{$value}", 'amount' => $value * 100]],
            ['photo_url' => 'https://img.freepik.com/free-photo/social-media-marketing-concept-for-marketing-with-applications_23-2150063165.jpg?w=900&t=st=1690203411~exp=1690204011~hmac=06a33860a9a1783dde295ac6a8285c1356c3eda829008ab45fc16c26f0e744a9',
                'photo_size' => 512,
                'photo_width' => 512,
                'photo_height' => 512]
        );


    }

    public function paymePay(Nutgram $bot, string $data)
    {
        $donationValue = (int)$data;

        $botFatherService = new BotFatherService();
        $settings = $botFatherService->setPaymentsSetting($donationValue, $this->botText, $this->userLocale);

        $description = $settings['description'];
        $title = $settings['title'];
        $value = $settings['value'];

        $bot->sendInvoice(
            $title,
            $description,
            'peyme',
            $_ENV['PAYMENT_PROVIDER_TOKEN_PAYME'],
            'RUB',
            [['label' => "{$value}", 'amount' => $value * 100]],
            ['photo_url' => 'https://img.freepik.com/free-photo/social-media-marketing-concept-for-marketing-with-applications_23-2150063165.jpg?w=900&t=st=1690203411~exp=1690204011~hmac=06a33860a9a1783dde295ac6a8285c1356c3eda829008ab45fc16c26f0e744a9',
                'photo_size' => 512,
                'photo_width' => 512,
                'photo_height' => 512]
        );

    }

    public function handleBotCreate(Nutgram $bot)
    {
        $this->menuText("{$this->botText->getTranslatedAttribute('bot_set_text',$this->userLocale,'fallbackLocale')}", [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
        ])->clearButtons()->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('back_button', $this->userLocale, 'fallbackLocale'), callback_data: 'back@start'))->orNext('setBotCreate')->showMenu();

    }

    public function setBotCreate(Nutgram $bot)
    {

        $this->message = $bot->message();
        $this->messageText = (string)$this->message->text;
        $this->userId = $this->message->chat->id;

        $tokenValidation = new BotTokenValidationService();
        $item = $tokenValidation->validateBotToken($this->messageText, $this->userId, $this->userLocale);
//        $item = BotTokenValidationJob::dispatch($this->messageText, $this->userId);


        if ($item->result) {

            $makeNewBot = new BotSetWebhookService();
            $makeNewBot->setWebhook($item->token);
            $this->clearButtons();
            $this->newBotSettings($bot);
            $this->showMenu(true);;
        } else {
            $bot->sendMessage($item->message);
        }

    }

    public function newBotSettings(Nutgram $bot)
    {
        $this->clearButtons();

        $this->menuText($this->botText->getTranslatedAttribute('bot_set_response_text', $this->userLocale, 'fallbackLocale'), [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
        ]);
        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('bot_set_response_bot_settings_button', $this->userLocale, 'fallbackLocale'), callback_data: 'botSettings@myBots')
        );

        $this->showMenu();
    }

    public function myBots(Nutgram $bot)
    {
        $userId = $bot->message()->chat->id;

        $botFatherService = new BotFatherService();
        $myBots = $botFatherService->getMyBots($userId);

        $this->clearButtons();
        $this->menuText($this->botText->getTranslatedAttribute('bot_settings_text', $this->userLocale, 'fallbackLocale'), [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
        ]);


        foreach ($myBots as $myBot) {

            $this->addButtonRow(InlineKeyboardButton::make("@$myBot->username", callback_data: "{$myBot->id}@setBotId"));
        }

        $this->addButtonRow(InlineKeyboardButton::make($this->botText->getTranslatedAttribute('back_button', $this->userLocale, 'fallbackLocale'), callback_data: 'back@start'));
        $this->showMenu(true);
    }

    public function setBotId(Nutgram $bot)
    {
        $this->botId = $bot->callbackQuery()->data;

        $this->botSettings($bot);
    }

    public function botSettings(Nutgram $bot)
    {
//        $getBotId = $bot->callbackQuery()->data;
//        $botID = $getBotId;
//        $this->botId = $botID;

        $userId = $bot->chat()->id;

        $botFatherService = new BotFatherService();
        $botUsername = $botFatherService->getBotUsername($this->botId, $userId);


//        $bot->middleware(function (Nutgram $bot, $next) {
//
//            $this->settings = TelegramBot::first('id', $this->botId)->settings();
//            $bot->setData('tgBotSettings' , $this->settings);
//            $next($bot);
//        });

        $this
            ->clearButtons()
            ->menuText($this->botText->getTranslatedAttribute('bot_settings_text', $this->userLocale, 'fallbackLocale') . " @$botUsername", [
                'parse_mode' => ParseMode::HTML,
                'disable_web_page_preview' => true,
            ]);

        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('bot_settings_texts_button', $this->userLocale, 'fallbackLocale'), callback_data: 'text@botChatsText'),
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('bot_settings_newslatter_button', $this->userLocale, 'fallbackLocale'), callback_data: 'newsletter', url: "http://t.me/{$botUsername}?start=newsletter")
        );
        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('bot_settings_chats_button', $this->userLocale, 'fallbackLocale'), callback_data: 'botChats@botChatsSettings'),
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('bot_settings_statistics_button', $this->userLocale, 'fallbackLocale'), callback_data: 'statistics@botStatistics')
        );
        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('bot_settings_disable_bot_button', $this->userLocale, 'fallbackLocale'), callback_data: 'disableMyBot@askDisableBot')
        );
        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('back_button', $this->userLocale, 'fallbackLocale'), callback_data: 'back@myBots')
        );

        $this->showMenu($this->reopen);
        $this->reopen = false;
    }

    public function botChatsText(Nutgram $bot)
    {

        $this->clearButtons();
        $this->menuText(
            $this->botText->getTranslatedAttribute('bot_chats_text', $this->userLocale, 'fallbackLocale'), [
                'parse_mode' => ParseMode::HTML,
                'disable_web_page_preview' => true,
            ]
        );
        $this->addButtonRow(
            InlineKeyboardButton::make('English 🇺🇸', callback_data: 'en@setLocale'),
            InlineKeyboardButton::make('Русский 🇷🇺', callback_data: 'ru@setLocale'),
            InlineKeyboardButton::make("O'zbek 🇺🇿", callback_data: 'uz@setLocale'),
        );
        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('back_button', $this->userLocale, 'fallbackLocale'), callback_data: 'back@botSettings')
        );
        $this->showMenu();
    }

    public function setLocale(Nutgram $bot)
    {
        $this->botTextLocale = $bot->callbackQuery()->data;

        $this->feedbackInputText($bot);
    }

    public function feedbackInputText(Nutgram $bot)
    {

        $service = new BotFatherService();
        $chatId = $bot->chat()->id;
        $currentBot = $service->getBotTranslation($this->botId, $chatId);
        $value = $currentBot->getTranslation('user_bot_input_text', $this->botTextLocale, false);


        if (empty($value)) {
            $value = $currentBot->getTranslation('default_bot_input_text', $this->botTextLocale, false);
        }


        //$inputText = $this->currentBot->getTranslation('default_bot_input_text', $this->botTextLocale);
        $this->clearButtons()
            ->menuText("{$this->botText->getTranslatedAttribute('bot_texts_menu_hello_message_text',$this->userLocale,'fallbackLocale')}\n
            `{$value}`", [
                'parse_mode' => ParseMode::MARKDOWN_LEGACY,
                'disable_web_page_preview' => true,
            ]);

        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('bot_texts_menu_next_button', $this->userLocale, 'fallbackLocale'), callback_data: 'next@feedbackResponseText'),
        )
            ->addButtonRow(
                InlineKeyboardButton::make($this->botText->getTranslatedAttribute('bot_texts_menu_edit_button', $this->userLocale, 'fallbackLocale'), callback_data: 'changeInputText@setFeedbackInput'),
            );

        $isEmptyValue = $currentBot->getTranslation('user_bot_input_text', $this->botTextLocale, false);
        if (!empty($isEmptyValue)) {
            $this->addButtonRow(
                InlineKeyboardButton::make($this->botText->getTranslatedAttribute('bot_texts_menu_default_button', $this->userLocale, 'fallbackLocale'), callback_data: 'defaultFeedbackInput@setDefaultFeedbackInput')
            );
        }
        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('back_button', $this->userLocale, 'fallbackLocale'), callback_data: 'back@botChatsText')
        );

        $this->showMenu();
    }

    public function setDefaultFeedbackInput(Nutgram $bot)
    {

        $chatId = $bot->chat()->id;

        SetDefaultFeedbackInputJob::dispatch($chatId, $this->botId, $this->botTextLocale);

        $this->clearButtons();
        $this->feedbackInputText($bot);

    }

    public function setFeedbackInput(Nutgram $bot)
    {
        $this->clearButtons();
        $this->menuText($this->botText->getTranslatedAttribute('bot_texts_edit_menu_text', $this->userLocale, 'fallbackLocale'))->orNext('getFeedbackInput');
        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('back_button', $this->userLocale, 'fallbackLocale'), callback_data: 'back@feedbackInputText')
        );
        $this->showMenu();

    }

    public function getFeedbackInput(Nutgram $bot)
    {
        $value = (string)$bot->message()->text;
        $chatId = $bot->chat()->id;

        SetFeedbackInputJob::dispatch($chatId, $this->botId, $this->botTextLocale, $value);

        $this->closeMenu();
        $this->feedbackResponseText($bot);
    }

    public function feedbackResponseText(Nutgram $bot)
    {

        $service = new BotFatherService();
        $chatId = $bot->chat()->id;
        $currentBot = $service->getBotTranslation($this->botId, $chatId);

        $value = $currentBot->getTranslation('user_bot_response_text', $this->botTextLocale, false);


        if (empty($value)) {
            $value = $currentBot->getTranslation('default_bot_response_text', $this->botTextLocale, false);
        }

        //$responseText = $this->currentBot->getTranslation('default_bot_response_text', $this->botTextLocale);
        $this->clearButtons()
            ->menuText("{$this->botText->getTranslatedAttribute('bot_texts_menu_response_message_text',$this->userLocale,'fallbackLocale')}\n
            `{$value}`", [
                'parse_mode' => ParseMode::MARKDOWN_LEGACY,
                'disable_web_page_preview' => true,
            ]);
        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('bot_texts_menu_next_button', $this->userLocale, 'fallbackLocale'), callback_data: 'next@botSettings'),
        )->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('bot_texts_menu_edit_button', $this->userLocale, 'fallbackLocale'), callback_data: 'changeInputText@setFeedbackResponse')
        );
        $isEmptyValue = $currentBot->getTranslation('user_bot_response_text', $this->botTextLocale, false);

        if (!empty($isEmptyValue)) {
            $this->addButtonRow(
                InlineKeyboardButton::make($this->botText->getTranslatedAttribute('bot_texts_menu_default_button', $this->userLocale, 'fallbackLocale'), callback_data: 'defaultFeedbackInput@setDefaultFeedbackResponse')
            );
        }
        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('back_button', $this->userLocale, 'fallbackLocale'), callback_data: 'back@feedbackInputText')
        );
        $this->showMenu();
    }

    public function setDefaultFeedbackResponse(Nutgram $bot)
    {

        $chatId = $bot->chat()->id;

        SetDefaultFeedbackResponseJob::dispatch($chatId, $this->botId, $this->botTextLocale);

        $this->clearButtons();
        $this->feedbackResponseText($bot);
    }

    public function setFeedbackResponse(Nutgram $bot)
    {
        $this->clearButtons();
        $this->menuText($this->botText->getTranslatedAttribute('bot_texts_edit_menu_text', $this->userLocale, 'fallbackLocale'))->orNext('getFeedbackResponse');
        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('back_button', $this->userLocale, 'fallbackLocale'), callback_data: 'back@feedbackResponseText')
        );
        $this->showMenu();
    }

    public function getFeedbackResponse(Nutgram $bot)
    {
        $value = (string)$bot->message()->text;
        $chatId = $bot->chat()->id;


        SetFeedbackResponseJob::dispatch($chatId, $this->botId, $this->botTextLocale, $value);

        $this->closeMenu();
        $this->botSettings($bot);
    }

    public function botChatsSettings(Nutgram $bot)
    {

        $botFatherService = new BotFatherService();
        $botChats = $botFatherService->getBotChats($this->botId);


        $this->clearButtons();
        $this->menuText($this->botText->getTranslatedAttribute('bot_chat_settings_text', $this->userLocale, 'fallbackLocale'), [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
        ]);
        foreach ($botChats as $myBotChat) {

            $this->addButtonRow(InlineKeyboardButton::make("$myBotChat->title", callback_data: "{$myBotChat->chat_id}@botChatSettings"));
        }
        $this->addButtonRow(InlineKeyboardButton::make($this->botText->getTranslatedAttribute('back_button', $this->userLocale, 'fallbackLocale'), callback_data: 'back@botSettings'));
        $this->showMenu(true);
    }

    public function botChatSettings(Nutgram $bot)
    {
        $this->botChatId = $bot->callbackQuery()->data;
        $this->chatSettings($bot);
    }

    public function chatSettings(Nutgram $bot)
    {
        $this->clearButtons();
        $this->menuText($this->botText->getTranslatedAttribute('bot_chat_settings_nested_menu_text', $this->userLocale, 'fallbackLocale'), [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
        ]);
        $this->addButtonRow(
            InlineKeyboardButton::make('English 🇺🇸', callback_data: 'en@setGroupLanguage'),
            InlineKeyboardButton::make('Русский 🇷🇺', callback_data: 'ru@setGroupLanguage'),
            InlineKeyboardButton::make("O'zbek 🇺🇿", callback_data: 'uz@setGroupLanguage'),
        );
        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('back_button', $this->userLocale, 'fallbackLocale'), callback_data: 'back@botChatsSettings')
        );
        $this->showMenu();
    }

    public function setGroupLanguage(Nutgram $bot)
    {
        $languageCode = $bot->callbackQuery()->data;

        $botFatherService = new BotFatherService();
        $botFatherService->updateLanguageCode($this->botChatId, $this->botId, $languageCode);

        $this->botSettings($bot);
    }

    public function botStatistics(Nutgram $bot)
    {

        $userId = $bot->chat()->id;

        $botFatherService = new BotFatherService();
        $botUsername = $botFatherService->getBotUsername($this->botId, $userId);

        $botFatherService = new BotFatherService();
        $statBot = $botFatherService->getBotStats($this->botId);
        $botId = $statBot->bot_id;

        $countOfUser = $botFatherService->getUserCount($botId);

        $this->clearButtons();

        $this->menuText("{$this->botText->getTranslatedAttribute('bot_statistics_title',$this->userLocale,'fallbackLocale')} @{$botUsername}\n\n
        {$this->botText->getTranslatedAttribute('bot_statistics_users',$this->userLocale,'fallbackLocale')}\n\n
         {$this->botText->getTranslatedAttribute('bot_statistics_users_count',$this->userLocale,'fallbackLocale')} {$countOfUser}\n\n
         {$this->botText->getTranslatedAttribute('bot_statistics_messages',$this->userLocale,'fallbackLocale')}\n\n
         {$this->botText->getTranslatedAttribute('bot_statistics_messages_all_count',$this->userLocale,'fallbackLocale')} {$statBot->incoming_messages}\n\n
         {$this->botText->getTranslatedAttribute('bot_statistics_messages_response_count',$this->userLocale,'fallbackLocale')} {$statBot->response_messages}", [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
        ]);
        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('back_button', $this->userLocale, 'fallbackLocale'), callback_data: 'back@botSettings')
        );
        $this->showMenu();
    }

    public function askDisableBot(Nutgram $bot)
    {
        $this->clearButtons();
        $this->menuText($this->botText->getTranslatedAttribute('bot_disable_text', $this->userLocale, 'fallbackLocale'), [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
        ]);
        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('bot_disable_yes_button', $this->userLocale, 'fallbackLocale'), callback_data: 'yes@disableBot'),
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('bot_disable_no_button', $this->userLocale, 'fallbackLocale'), callback_data: 'no@botSettings'),

        );
        $this->showMenu();
    }

    public function disableBot(Nutgram $bot)
    {
        $userId = $bot->chat()->id;
        $botFatherService = new BotFatherService();
        $botToken = $botFatherService->getBotToken($this->botId, $userId);
        $removeWebHook = new BotRemoveWebhookService();
        $removeWebHook->removeWebhook($botToken);
        $botFatherService->deleteBotFromDataBase($this->botId, $userId);
        $this->disableBotDone($bot);
    }

    public function disableBotDone(Nutgram $bot)
    {
        $this->clearButtons();
        $this->menuText($this->botText->getTranslatedAttribute('bot_disable_response_text', $this->userLocale, 'fallbackLocale'), [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
        ]);
        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('bot_disable_response_button', $this->userLocale, 'fallbackLocale'), callback_data: 'mainMenu@start')
        );
        $this->showMenu();
    }

    public function help(Nutgram $bot)
    {
        $this->menuText($this->botText->getTranslatedAttribute('help_menu_text', $this->userLocale, 'fallbackLocale'), [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
        ])->clearButtons()->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('back_button', $this->userLocale, 'fallbackLocale'), callback_data: 'back@start'))->orNext('none')->showMenu();
    }

    public function none(Nutgram $bot)
    {

        $this->clearButtons();
        $this->menuText($this->botText->getTranslatedAttribute('help_menu_text', $this->userLocale, 'fallbackLocale'), [
            'parse_mode' => ParseMode::HTML,
            'disable_web_page_preview' => true,
        ]);
        $this->addButtonRow(
            InlineKeyboardButton::make($this->botText->getTranslatedAttribute('help_menu_button', $this->userLocale, 'fallbackLocale'), callback_data: 'mainMenu@start')
        );
        $this->closeMenu();
        $this->showMenu();
    }

}
