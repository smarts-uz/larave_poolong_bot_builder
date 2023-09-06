<?php

namespace App\MoonShine\Resources;

use App\Models\BotButton;
use App\Models\TgBot;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\PostUser;

use MoonShine\Actions\ExportAction;
use MoonShine\Actions\ImportAction;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class PostUserResource extends Resource
{
	public static string $model = PostUser::class;

	public static string $title = 'PostUsers';

    public static array $activeActions = ['show','delete'];

	public function fields(): array
	{
		return [
		    ID::make()->sortable()->showOnExport()->useOnImport(),
            Text::make('User','user_id')->showOnExport()->useOnImport()->hideOnIndex()->hideOnCreate()->hideOnDetail(),
            Text::make('Post','post_id')->showOnExport()->useOnImport()->hideOnIndex()->hideOnCreate()->hideOnDetail(),
            Text::make('Button','button_id')->showOnExport()->useOnImport()->hideOnIndex()->hideOnCreate()->hideOnDetail(),
            Text::make(trans('moonshine::ui.custom.post_title'),'id', function (PostUser $botButton) {
                return $botButton->posts->title;
            })->hideOnCreate(),
            Text::make(trans('moonshine::ui.custom.user_info'),'id', function (PostUser $botButton) {
                if (!empty($botButton->users->username)) {
                    return $botButton->users->username;
                }
                if (!empty($botButton->users->first_name)) {
                    return $botButton->users->first_name;
                }
                if (!empty($botButton->users->last_name)) {
                    return $botButton->users->last_name;
                }
                return 'default';
            })->hideOnCreate(),
            Text::make(trans('moonshine::ui.custom.button_title'),'id', function (PostUser $botButton) {
                return $botButton->buttons->title;
            })->hideOnCreate(),
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
            ExportAction::make('Export')->showInLine()
                ->disk('public')
                ->dir('buttons'),
            ImportAction::make('Import')->showInLine()
                ->disk('public')
                ->dir('buttons'),
        ];
    }
}
