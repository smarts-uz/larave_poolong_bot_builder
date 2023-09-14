<?php

namespace App\MoonShine\Resources;

use App\Models\MoonshineTranslate;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use Modules\FeedbackBot\Entities\FeedbackUserChat;
use Modules\FeedbackBot\Entities\TelegramBot;
use Modules\PoolingBot\Entities\TgBot;
use MoonShine\Actions\ExportAction;
use MoonShine\Actions\ImportAction;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class FeedbackUserResource extends Resource
{
	public static string $model = FeedbackUserChat::class;

	public static string $title = 'FeedbackUsers';
    public static array $activeActions = ['show','delete'];

	public function fields(): array
	{
        $text = MoonshineTranslate::query()->where('id',1)->first();

		return [
		    ID::make()->sortable()->showOnExport()->useOnImport(),
            Text::make($text->getTranslation('user_name',app()->getLocale(),false),'first_name')->useOnImport()->showOnExport(),
            Text::make($text->getTranslation('user_lastname',app()->getLocale(),false),'last_name')->useOnImport()->showOnExport(),
            Text::make('Username','username')->useOnImport()->showOnExport(),
            Text::make('Telegram Id','chat_id')->useOnImport()->showOnExport(),
            Text::make($text->getTranslation('language_code',app()->getLocale(),false),'language_code')->useOnImport()->showOnExport(),
            Text::make('Started At','started_at')->useOnImport()->showOnExport(),
            Text::make('Bot Id','botid')->useOnImport()->showOnExport()->hideOnDetail()->hideOnIndex(),
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
                ->dir('fbusers'),
            ImportAction::make('Import')->showInLine()
                ->disk('public')
                ->dir('fbusers'),
        ];
    }
    public function query(): Builder
    {
        $bots = TelegramBot::where('user_id', auth()->user()->id)->get();
        $botIds = $bots->pluck('id');

        return parent::query()->whereIn('botid', $botIds);
    }
}
