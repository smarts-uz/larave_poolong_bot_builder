<?php

namespace App\MoonShine\Resources;


use App\Models\MoonshineTranslate;
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
        $text = MoonshineTranslate::query()->where('id',1)->first();
		return [
		    ID::make()->sortable(),
            Text::make($text->getTranslation('bot_chat_title',app()->getLocale(),false),'title')->required(),
            Text::make($text->getTranslation('group_id',app()->getLocale(),false),'group_id')->required(),
            Text::make($text->getTranslation('bot',app()->getLocale(),false),'tg_bot_id')->required(),
            SwitchBoolean::make($text->getTranslation('group_on_off',app()->getLocale(),false),'tg_bot_on'),
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
