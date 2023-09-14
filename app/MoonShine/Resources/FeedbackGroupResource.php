<?php

namespace App\MoonShine\Resources;

use App\Models\MoonshineTranslate;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use Modules\FeedbackBot\Entities\Chat;
use Modules\FeedbackBot\Entities\TelegramBot;
use Modules\PoolingBot\Entities\TgBot;
use MoonShine\Actions\ExportAction;
use MoonShine\Actions\ImportAction;
use MoonShine\Fields\Select;
use MoonShine\Fields\SwitchBoolean;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class FeedbackGroupResource extends Resource
{
	public static string $model = Chat::class;

	public static string $title = 'FeedbackGroups';

	public function fields(): array
	{
        $text = MoonshineTranslate::query()->where('id',1)->first();

		return [
		    ID::make()->sortable()->showOnExport()->useOnImport(),
            Text::make($text->getTranslation('bot_chat_title',app()->getLocale(),false),'title')->required()->showOnExport()->useOnImport(),
            Text::make($text->getTranslation('group_id',app()->getLocale(),false),'chat_id')->required()->showOnExport()->useOnImport(),
            Select::make($text->getTranslation('group_language',app()->getLocale(),false),'language_code')->required()->showOnExport()->useOnImport()
            ->options([
                'none' => 'none',
                'en' => 'en',
                'ru' => 'ru',
                'uz' => 'uz',
            ]),
            Text::make($text->getTranslation('bot',app()->getLocale(),false),'telegram_bot_id')->required()->showOnExport()->useOnImport(),
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
                ->dir('fbgroup'),
            ImportAction::make('Import')->showInLine()
                ->disk('public')
                ->dir('fbgroup'),
        ];
    }
    public function query(): Builder
    {
        $bots = TelegramBot::where('user_id', auth()->user()->id)->get();
        $botIds = $bots->pluck('id');

        return parent::query()->whereIn('telegram_bot_id', $botIds);
    }
}
