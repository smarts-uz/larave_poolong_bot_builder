<?php

namespace App\MoonShine\Resources;



use App\Models\MoonshineTranslate;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\PoolingBot\Entities\TgBot;
use Modules\PoolingBot\Entities\TgBotText;
use Modules\PoolingBot\Entities\TgGroup;
use Modules\PoolingBot\Services\BotSetWebhookService;
use MoonShine\Actions\FiltersAction;
use MoonShine\Fields\HasMany;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\Url;
use MoonShine\ItemActions\ItemAction;
use MoonShine\Resources\Resource;

class TgBotResource extends Resource
{
	public static string $model = TgBot::class;

	public static string $title = 'TgBots';



	public function fields(): array
	{
        $text = MoonshineTranslate::query()->where('id',1)->first();

		return [
		    ID::make()->sortable(),
            Text::make($text->getTranslation('bot_toke',app()->getLocale(),false),'bot_token')->hideOnIndex()->required(),
            Url::make($text->getTranslation('base_url',app()->getLocale(),false),'base_url')->required()->hideOnIndex(),
            Text::make($text->getTranslation('bot_username',app()->getLocale(),false),'bot_username')->hideOnCreate()->hideOnUpdate(),
            HasMany::make('BotGroup','groups',new TgGroupResource())->resourceMode()->hideOnIndex()->hideOnUpdate(),
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
        ];
    }
    public function query(): Builder
    {
        return parent::query()->where('user_id', auth()->user()->id);
    }

    public function beforeCreating(Model $item)
    {
        $item->user_id = request()->user()->id;
    }
    protected function afterDeleted(Model $item)
    {
        $botText = TgBotText::where('bot_id', $item->id)->delete();
        $botGroups = TgGroup::where('tg_bot_id',$item->id)->delete();
    }
    protected function afterCreated(Model $item)
    {
        $botText = new TgBotText();
        $botText->create([
            'first_action_msg' => 'Thanks for your vote',
            'repeated_action_msg' => 'You have already voted',
            'follow_msg' => 'To vote you need to be subscribed to the channel',
            'bot_id' => $item->id,
        ]);
    }
    public function itemActions(): array
    {
        return [
            ItemAction::make('Activate', function (Model $item) {

                $makeBot = new BotSetWebhookService();
                $makeBot->setWebhook($item->id);

            },'Activated')->icon('heroicons.check'),
             ItemAction::make('Deactivate', function (Model $item) {

                 $makeBot = new BotSetWebhookService();
                 $makeBot->deleteWebhook($item->bot_token);

             },'Deactivated')->icon('heroicons.x-mark')
        ];
    }
}
