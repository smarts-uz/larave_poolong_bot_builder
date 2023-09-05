<?php

namespace App\MoonShine\Resources;

use App\Models\TgBot;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\TgGroup;

use MoonShine\Fields\BelongsTo;
use MoonShine\Fields\HasMany;
use MoonShine\Fields\SwitchBoolean;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class TgGroupResource extends Resource
{
	public static string $model = TgGroup::class;

	public static string $title = 'TgGroups';
//    public static array $activeActions = ['show'];
	public function fields(): array
	{
		return [
		    ID::make()->sortable(),
            Text::make('Bot Chat Title','title'),
            Text::make('Group Id','group_id'),
            Text::make('Bot','tg_bot_id'),
            SwitchBoolean::make('ON/OFF','tg_bot_on'),
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

        return parent::query()->whereIn('tg_bot_id', $botIds);
    }

    public function actions(): array
    {
        return [
            FiltersAction::make(trans('moonshine::ui.filters')),
        ];
    }
}
