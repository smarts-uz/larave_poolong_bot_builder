<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\FeedbackBulkMessenger;

use MoonShine\Actions\ExportAction;
use MoonShine\Actions\ImportAction;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class FeedbackBulkMessengerResource extends Resource
{
	public static string $model = FeedbackBulkMessenger::class;

	public static string $title = 'FeedbackBulkMessengers';

	public function fields(): array
	{
		return [
		    ID::make()->sortable(),
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
                ->dir('fbbulkmessanger'),
            ImportAction::make('Import')->showInLine()
                ->disk('public')
                ->dir('fbbulkmessanger'),
        ];
    }
}
