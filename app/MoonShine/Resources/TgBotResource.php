<?php

namespace App\MoonShine\Resources;


use App\Jobs\ArtisanJob;
use App\Services\TelegramBotService;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Database\Eloquent\Model;
use App\Models\TgBot;

use Illuminate\Support\Facades\Artisan;
use MoonShine\Fields\Text;
use MoonShine\ItemActions\ItemAction;
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
    public function itemActions(): array
    {
        return [
            ItemAction::make('Activate', function (Model $item) {
                ArtisanJob::dispatch($item);
//                $cmd = 'php artisan tg:bot' . "{$item->id}";
//                $service = new TelegramBotService();
//                $service::execInBackground($cmd);
            },'Activated')->icon('heroicons.check'),
             ItemAction::make('Deactivate', function (Model $item) {

             },'Deactivated')->icon('heroicons.x-mark')
        ];
    }
}
