<?php

namespace App\MoonShine\Resources;

use App\Models\TgBot;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\TgBotText;

use MoonShine\Fields\HasOne;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class TgBotTextResource extends Resource
{
	public static string $model = TgBotText::class;

	public static string $title = 'TgBotTexts';
    public static array $activeActions = ['show','edit'];

	public function fields(): array
	{
		return [
		    ID::make()->sortable(),
            Text::make('First Action Message','first_action_msg')->required(),
            Text::make('Repeated Action Message','repeated_action_msg')->required(),
            Text::make('Unfollow Users Message','follow_msg')->required(),
            HasOne::make('Bot','bots',new TgBotResource())
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
