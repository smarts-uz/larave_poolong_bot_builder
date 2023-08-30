<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\TgGroup;

use MoonShine\Fields\BelongsTo;
use MoonShine\Fields\HasMany;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class TgGroupResource extends Resource
{
	public static string $model = TgGroup::class;

	public static string $title = 'TgGroups';

	public function fields(): array
	{
		return [
		    ID::make()->sortable(),
            Text::make('Bot Chats','title'),
            HasMany::make('Bots','tg_bot_id','bots'),
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
}
