<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;

use Modules\FeedbackBot\Entities\Newslatter;
use MoonShine\Actions\ExportAction;
use MoonShine\Actions\ImportAction;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;
use VI\MoonShineSpatieTranslatable\Fields\Translatable;

class FbBulkMessengerTextsResource extends Resource
{
	public static string $model = Newslatter::class;

	public static string $title = 'FeedbackBulkMessengers';

	public function fields(): array
	{
		return [
		    ID::make()->sortable(),
            Translatable::make('main_menu_text','main_menu_text')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('main_menu_all_button','main_menu_all_button')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('main_menu_ru_button','main_menu_ru_button')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('main_menu_en_button','main_menu_en_button')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('main_menu_uz_button','main_menu_uz_button')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('cancel_button','cancel_button')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('save_message_text','save_message_text')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('preview_button','preview_button')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('start_newslatter_button','start_newslatter_button')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('newslatter_preview_text','newslatter_preview_text')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('all_menu_text','all_menu_text')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('ru_menu_text','ru_menu_text')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('en_menu_text','en_menu_text')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('uz_menu_text','uz_menu_text')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
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
