<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\TelegramUser;

use MoonShine\Actions\ExportAction;
use MoonShine\Actions\ImportAction;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class TelegramUserResource extends Resource
{
	public static string $model = TelegramUser::class;

	public static string $title = 'TelegramUsers';
    public static array $activeActions = ['show','delete'];

	public function fields(): array
	{
		return [
		    ID::make()->sortable()->useOnImport()->showOnExport(),
            Text::make('First Name','first_name')->useOnImport()->showOnExport(),
            Text::make('Last Name','last_name')->useOnImport()->showOnExport(),
            Text::make('Username','username')->useOnImport()->showOnExport(),
            Text::make('Telegram Id','telegram_id')->useOnImport()->showOnExport(),

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
