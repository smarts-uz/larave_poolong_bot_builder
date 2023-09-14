<?php

namespace App\MoonShine\Resources;


use App\Models\MoonshineTranslate;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\PoolingBot\Entities\PostUser;
use Modules\PoolingBot\Entities\TgBot;
use MoonShine\Actions\ExportAction;
use MoonShine\Actions\FiltersAction;
use MoonShine\Actions\ImportAction;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;

class PostUserResource extends Resource
{
	public static string $model = PostUser::class;

	public static string $title = 'PostUsers';

    public static array $activeActions = ['show','delete'];

	public function fields(): array
	{
        $text = MoonshineTranslate::query()->where('id',1)->first();
		return [
		    ID::make()->sortable()->showOnExport()->useOnImport(),
            Text::make('User','user_id')->showOnExport()->useOnImport()->hideOnIndex()->hideOnCreate()->hideOnDetail(),
            Text::make('Post','post_id')->showOnExport()->useOnImport()->hideOnIndex()->hideOnCreate()->hideOnDetail(),
            Text::make('Button','button_id')->showOnExport()->useOnImport()->hideOnIndex()->hideOnCreate()->hideOnDetail(),
            Text::make($text->getTranslation('post_title',app()->getLocale(),false),'id', function (PostUser $botButton) {
                if (!empty($botButton->posts->title)) {
                return $botButton->posts->title;
                } else {
                    return null;
                }
            })->hideOnCreate(),
            Text::make($text->getTranslation('user_info',app()->getLocale(),false),'id', function (PostUser $botButton) {
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
            Text::make($text->getTranslation('button_title',app()->getLocale(),false),'id', function (PostUser $botButton) {
                if (!empty($botButton->buttons->title)) {
                return $botButton->buttons->title;
                } else {
                    return null;
                }
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
