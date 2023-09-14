<?php

namespace App\MoonShine\Resources;

use App\Models\MoonshineTranslate;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use Modules\FeedbackBot\Entities\TelegramBot;
use MoonShine\Actions\ExportAction;
use MoonShine\Actions\ImportAction;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;
use VI\MoonShineSpatieTranslatable\Fields\Translatable;

class FeedbackTextResource extends Resource
{
	public static string $model = TelegramBot::class;

	public static string $title = 'FeedbackTexts';
    public static array $activeActions = ['show','edit'];

	public function fields(): array
	{
        $text = MoonshineTranslate::query()->where('id',1)->first();
		return [
		    ID::make()->sortable()->showOnExport()->useOnImport(),
            Text::make($text->getTranslation('bot',app()->getLocale(),false),'username')->required()->disabled()->showOnExport()->useOnImport(),
            Translatable::make($text->getTranslation('bot_input_text',app()->getLocale(),false),'user_bot_input_text')->required()->showOnExport()->useOnImport()
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make($text->getTranslation('bot_response_text',app()->getLocale(),false),'user_bot_response_text')->required()->showOnExport()->useOnImport()
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
                ->dir('fbtext'),
            ImportAction::make('Import')->showInLine()
                ->disk('public')
                ->dir('fbtext'),
        ];
    }
    public function query(): Builder
    {
        return parent::query()->where('user_id', auth()->user()->id);
    }
}
