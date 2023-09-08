<?php

namespace Modules\FeedbackBot\Conversations;



use Modules\FeedbackBot\Entities\FeedbackBotTextModel;
use Modules\FeedbackBot\Services\BotFatherService;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardRemove;

class BotFatherFeedBackConversation extends Conversation
{
    protected ?string $feedback;
    protected bool $success = false;
    protected int $chat_id;
    protected int $message_id;
    protected $chatID;
    protected $botText;
    protected $userLocale;
    public function start(Nutgram $bot)
    {
        $userId = $bot->user()->id;

        $lang = new BotFatherService();
        $value =  $lang->getLanguage($userId);

        $this->userLocale = $value;
        $this->botText = FeedbackBotTextModel::withTranslation($this->userLocale)->first();


        $message = $bot->sendMessage($this->botText->getTranslatedAttribute('feedback_input',$this->userLocale,'fallbackLocale'), [
            'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)
                ->addRow(KeyboardButton::make($this->botText->getTranslatedAttribute('cancel_button',$this->userLocale,'fallbackLocale')))
        ]);
        $this->chat_id = $message->chat->id;
        $this->message_id = $message->message_id;

        $this->next('getFeedback');

    }

    public function getFeedback(Nutgram $bot) : void
    {
        if ($bot->message()?->text === $this->botText->getTranslatedAttribute('cancel_button',$this->userLocale,'fallbackLocale')){
            $this->end();
            return;
        }
//        if ($bot->message()?->text === null){
//            $bot->sendMessage('Неверный отзыв.');
//            $this->start($bot);
//
//            return;
//        }

        //$this->chatID = $bot->getData('chatId');
        $service = new BotFatherService();
        $botId = $bot->getMe()->id;
        $botChatId = $service->getBotFatherChat($botId);

        if ($botChatId !== null && $botChatId !== ''){
            $this->messageId = $bot->message()->message_id;
            $this->chatId = $bot->message()->chat->id;
            $bot->forwardMessage($botChatId, "$this->chatId", "$this->messageId");
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


            $bot->sendMessage($this->botText->getTranslatedAttribute('feedback_response',$this->userLocale,'fallbackLocale'));

            return;
        }
        $bot->sendMessage($this->botText->getTranslatedAttribute('cancel',$this->userLocale,'fallbackLocale'),[
            'reply_markup' => ReplyKeyboardRemove::make(true),
        ]);
    }

}
