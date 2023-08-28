<?php

namespace App\MoonShine\Resources;

use App\Models\BotButton;
use Barryvdh\Debugbar\Facades\Debugbar;
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

    public static array $activeActions = ['show','edit','delete'];

	public function fields(): array
	{
		return [
		    ID::make()->sortable()->showOnExport()->useOnImport(),
            Text::make('User','user_id')->showOnExport()->useOnImport(),
            Text::make('Post','post_id')->showOnExport()->useOnImport(),
            Text::make('Button','button_id')->showOnExport()->useOnImport(),
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
            ExportAction::make('Export')->showInLine()
                ->disk('public')
                ->dir('buttons'),
            ImportAction::make('Import')->showInLine()
                ->disk('public')
                ->dir('buttons'),
        ];
    }
}
