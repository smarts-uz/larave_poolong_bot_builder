<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\TgBot;

use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class TgBotResource extends Resource
{
	public static string $model = TgBot::class;

	public static string $title = 'TgBots';

	public function fields(): array
	{
		return [
		    ID::make()->sortable(),
            Text::make('Bot Token','bot_token'),
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

    public function beforeCreating(Model $item)
    {
        $item->user_id = request()->user()->id;
    }
}
