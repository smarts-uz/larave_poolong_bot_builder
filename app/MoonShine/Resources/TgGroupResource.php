<?php

namespace App\MoonShine\Resources;


use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\PoolingBot\Entities\TgBot;
use Modules\PoolingBot\Entities\TgGroup;
use MoonShine\Actions\FiltersAction;
use MoonShine\Fields\ID;
use MoonShine\Fields\SwitchBoolean;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;

class TgGroupResource extends Resource
{
	public static string $model = TgGroup::class;

	public static string $title = 'TgGroups';
//    public static array $activeActions = ['show'];
	public function fields(): array
	{
		return [
		    ID::make()->sortable(),
            Text::make('Bot Chat Title','title')->required(),
            Text::make('Group Id','group_id')->required(),
            Text::make('Bot','tg_bot_id')->required(),
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
