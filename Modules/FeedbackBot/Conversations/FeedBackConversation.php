<?php

namespace Modules\FeedbackBot\Conversations;


use Modules\FeedbackBot\Entities\FeedbackBotTextModel;
use Modules\FeedbackBot\Entities\TelegramBot;
use Modules\FeedbackBot\Services\BaseBotService;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardRemove;

class FeedBackConversation extends Conversation
{
    protected ?string $feedback;
    protected bool $success = false;
    protected int $chat_id;
    protected int $message_id;
    protected $chatID;
    protected $botText;
    protected $userLocale;
    protected $botJsonText;
    protected $botInputText;
    protected $botResponseText;
    public function start(Nutgram $bot)
    {
        $userId = $bot->user()->id;
        $botId = $bot->getMe()->id;
        $lang = new BaseBotService();
        $value =  $lang->getLanguage($userId,$botId);

        $this->userLocale = $value;
        //$this->botText = FeedbackBotTextModel::withTranslation($this->userLocale)->first();

        $this->botJsonText = TelegramBot::where('bot_id' , $botId)->first();


        $this->botInputText = $this->botJsonText->getTranslation('user_bot_input_text', $this->userLocale,false);
        $this->botResponseText = $this->botJsonText->getTranslation('user_bot_response_text', $this->userLocale, false);

        if (empty($this->botInputText) ) {
            $this->botInputText = $this->botJsonText->getTranslation('default_bot_input_text', $this->userLocale, false);
        }
        if (empty($this->botResponseText)) {
            $this->botResponseText = $this->botJsonText->getTranslation('default_bot_response_text', $this->userLocale, false);
        }

        $message = $bot->sendMessage($this->botInputText, [
            'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)
                ->addRow(KeyboardButton::make('Cancel'))
        ]);
        $this->chat_id = $message->chat->id;
        $this->message_id = $message->message_id;

        $this->next('getFeedback');

    }

    public function getFeedback(Nutgram $bot) : void
    {
        if ($bot->message()?->text === 'Cancel'){
            $this->end();
            return;
        }
//        if ($bot->message()?->text === null){
//            $bot->sendMessage('Неверный отзыв.');
//            $this->start($bot);
//
//            return;
//        }

        $this->chatID = $bot->getData('chatId');

        if ($this->chatID !== null && $this->chatID !== ''){
            $this->messageId = $bot->message()->message_id;
            $this->chatId = $bot->message()->chat->id;
            $bot->forwardMessage($this->chatID, "$this->chatId", "$this->messageId");
            $this->success = true;
            $this->end();
        }
        else{
            $this->messageId = $bot->message()->message_id;
            $this->chatId = $bot->message()->chat->id;
            $bot->forwardMessage($this->chatId, "$this->chatId", "$this->messageId");
            $this->success = true;
            $this->end();
        }

    }
    public function closing(Nutgram $bot)
    {
        //$bot->deleteMessage($this->chat_id, $this->message_id);
        if ($this->success) {

            $botId = $bot->getData('botId');

            TelegramBot::where('id', $botId)->increment('incoming_messages', 1);

            $bot->sendMessage($this->botResponseText);

            return;
        }
        $bot->sendMessage('Cancel',[
            'reply_markup' => ReplyKeyboardRemove::make(true),
        ]);
    }

}
