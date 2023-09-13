<?php

namespace App\MoonShine\Resources;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use Modules\FeedbackBot\Entities\TelegramBot;
use MoonShine\Actions\ExportAction;
use MoonShine\Actions\ImportAction;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class FeedbackReportResource extends Resource
{
	public static string $model = TelegramBot::class;

	public static string $title = 'FeedbackReports';
    public static array $activeActions = ['show'];

	public function fields(): array
	{
		return [
		    ID::make()->sortable()->showOnExport()->useOnImport(),
            Text::make('Bot Username','username')->showOnExport()->useOnImport(),
            Text::make('Bot Incoming Messages','incoming_messages')->showOnExport()->useOnImport(),
            Text::make('Bot Response Messages','response_messages')->showOnExport()->useOnImport(),
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
                ->dir('fbreport'),
            ImportAction::make('Import')->showInLine()
                ->disk('public')
                ->dir('fbreport'),
        ];
    }
    public function query(): Builder
    {
        return parent::query()->where('user_id', auth()->user()->id);
    }
}
