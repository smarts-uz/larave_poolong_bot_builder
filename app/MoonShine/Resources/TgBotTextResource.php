<?php

namespace App\MoonShine\Resources;



use App\Models\MoonshineTranslate;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\PoolingBot\Entities\TgBot;
use Modules\PoolingBot\Entities\TgBotText;
use MoonShine\Actions\FiltersAction;
use MoonShine\Fields\HasOne;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;

class TgBotTextResource extends Resource
{
	public static string $model = TgBotText::class;

	public static string $title = 'TgBotTexts';
    public static array $activeActions = ['show','edit'];

	public function fields(): array
	{
        $text = MoonshineTranslate::query()->where('id',1)->first();
		return [
		    ID::make()->sortable(),
            Text::make($text->getTranslation('first_action_message',app()->getLocale(),false),'first_action_msg')->required(),
            Text::make($text->getTranslation('repeated_action_message',app()->getLocale(),false),'repeated_action_msg')->required(),
            Text::make($text->getTranslation('unfollow_users_message',app()->getLocale(),false),'follow_msg')->required(),
            HasOne::make($text->getTranslation('bot',app()->getLocale(),false),'bots',new TgBotResource()),
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
    public function query(): Builder
    {
        $bots = TgBot::where('user_id', auth()->user()->id)->get();
        $botIds = $bots->pluck('id');

        return parent::query()->whereIn('bot_id', $botIds);
    }

    public function actions(): array
    {
        return [
            FiltersAction::make(trans('moonshine::ui.filters')),
        ];
    }
}
