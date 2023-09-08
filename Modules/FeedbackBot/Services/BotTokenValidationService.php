<?php

namespace Modules\FeedbackBot\Services;




use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Modules\FeedbackBot\Entities\BotFatherTextModel;
use Modules\FeedbackBot\Entities\TelegramBot;
use Modules\FeedbackBot\Items\BotTokenValid;
use Nette\Utils\Json;


class BotTokenValidationService
{
    public function validateBotToken($botToken, $userId, $userLocale)
    {
        $item = new BotTokenValid();
        $botText = BotFatherTextModel::withTranslation($userLocale)->first();

        $parametr = $botToken;
        $pattern = '/^\d+:[A-Za-z0-9_-]+$/';

        if (preg_match($pattern, $parametr)) {

            $client = new Client();
            $request = new Request('GET', "https://api.telegram.org/bot{$parametr}/getMe");
            $res = $client->send($request, ['http_errors' => false]);
            $body = $res->getBody()->getContents();
            $data = json_decode($body);

            if (isset($data->ok) && $data->ok === true) {
                if (isset($data->result)) {

                    $item->first_name = $data->result->first_name;
                    $item->username = $data->result->username;
                    $item->userId = $userId;
                    $item->token = $parametr;
                    $item->result = true;
                    $item->message = "Бот @$item->username успешно подключен к Nutgram Feedback. Попробуйте отправить боту любое сообщение, а затем ответить на него.
                    \nКак отвечать на входящие сообщения?
Используйте встроенную функцию Telegram для  ответов.Для этого сделайте свайп влево (или кликните два раза) по сообщению, на которое хотите ответить.";

                    Json::encode($item);

                    $bot = TelegramBot::firstOrCreate(['bot_token' => $item->token],[
                        'first_name' => $item->first_name,
                        'username' => $item->username,
                        'user_id' => $item->userId,
                        'default_bot_input_text' => [
                            'en' => 'Hello! Write your question and we will answer you as soon as possible.',
                            'ru' => 'Здравствуйте!  Напишите ваш вопрос и мы ответим Вам в ближайшее время.',
                            'uz' => 'Salom! Savolingizni yozing va biz sizga imkon qadar tezroq javob beramiz.',
                        ],
                        'default_bot_response_text' => [
                            'en' => 'Thanks for your feedback. Expect a response.',
                            'ru' => 'Спасибо за ваш отзыв. Ожидайте ответа.',
                            'uz' => 'Fikr-mulohazangiz uchun rahmat. Javob kuting.',
                        ],
                    ]);
                    $bot->save();
                }
            } elseif (isset($data->ok) && $data->ok === false) {

                $item->result = false;
                $item->message = $botText->getTranslatedAttribute('incorrect_bot_token',$userLocale,'fallbackLocale');

            }
        } else {

            $item->result = false;
            $item->message = $botText->getTranslatedAttribute('incorrect_bot_token',$userLocale,'fallbackLocale');
        }
        return $item;
    }
}
