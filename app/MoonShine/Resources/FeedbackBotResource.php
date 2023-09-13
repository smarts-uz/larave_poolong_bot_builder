<?php

namespace App\MoonShine\Resources;



use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\FeedbackBot\Entities\TelegramBot;
use Modules\FeedbackBot\Services\FeedbackSetWebhookService;
use Modules\PoolingBot\Entities\TgBot;
use Modules\PoolingBot\Entities\TgBotText;
use Modules\PoolingBot\Entities\TgGroup;
use Modules\PoolingBot\Services\BotSetWebhookService;
use MoonShine\Actions\ExportAction;
use MoonShine\Actions\FiltersAction;
use MoonShine\Actions\ImportAction;
use MoonShine\Fields\HasMany;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\Url;
use MoonShine\ItemActions\ItemAction;
use MoonShine\Resources\Resource;

class FeedbackBotResource extends Resource
{
	public static string $model = TelegramBot::class;

	public static string $title = 'TgBots';



	public function fields(): array
	{
		return [
		    ID::make()->sortable()->showOnExport()->useOnImport(),
            Text::make('Bot Token','bot_token')->hideOnIndex()->required()->showOnExport()->useOnImport(),
            Url::make('Base Url','base_url')->required()->hideOnIndex()->showOnExport()->useOnImport(),
            Text::make('Bot Username','username')->hideOnCreate()->hideOnUpdate()->showOnExport()->useOnImport(),
        ];
	}

    public function rules(Model $item): array
	{
	    return [];
    }

    public function search(): array
    {
        return ['id'];
    }

    public function filters(): array
    {
        return [];
    }

    public function actions(): array
    {
        return [
            FiltersAction::make(trans('moonshine::ui.filters')),
            ExportAction::make('Export')->showInLine()
                ->disk('public')
                ->dir('fbbot'),
            ImportAction::make('Import')->showInLine()
                ->disk('public')
                ->dir('fbbot'),
        ];
    }
    public function query(): Builder
    {
        return parent::query()->where('user_id', auth()->user()->id);
    }

    public function beforeCreating(Model $item)
    {
        $item->user_id = request()->user()->id;
        $item->default_bot_input_text  = [
            'en' => 'Hello! Write your question and we will answer you as soon as possible.',
        'ru' => 'Здравствуйте!  Напишите ваш вопрос и мы ответим Вам в ближайшее время.',
        'uz' => 'Salom! Savolingizni yozing va biz sizga imkon qadar tezroq javob beramiz.',
    ];
        $item->default_bot_response_text = [
        'en' => 'Thanks for your feedback. Expect a response.',
        'ru' => 'Спасибо за ваш отзыв. Ожидайте ответа.',
        'uz' => 'Fikr-mulohazangiz uchun rahmat. Javob kuting.',
        ];
    }
    protected function afterDeleted(Model $item)
    {
//        $botText = TgBotText::where('bot_id', $item->id)->delete();
//        $botGroups = TgGroup::where('tg_bot_id',$item->id)->delete();
    }
    protected function afterCreated(Model $item)
    {
//        $botText = new TgBotText();
//        $botText->create([
//            'first_action_msg' => 'Thanks for your vote',
//            'repeated_action_msg' => 'You have already voted',
//            'follow_msg' => 'To vote you need to be subscribed to the channel',
//            'bot_id' => $item->id,
//        ]);
    }
    public function itemActions(): array
    {
        return [
            ItemAction::make('Activate', function (Model $item) {

                $makeBot = new FeedbackSetWebhookService();
                $makeBot->setWebhook($item->id);

            },'Activated')->icon('heroicons.check'),
             ItemAction::make('Deactivate', function (Model $item) {

                 $makeBot = new FeedbackSetWebhookService();
                 $makeBot->deleteWebhook($item->bot_token);

             },'Deactivated')->icon('heroicons.x-mark')
        ];
    }
}
