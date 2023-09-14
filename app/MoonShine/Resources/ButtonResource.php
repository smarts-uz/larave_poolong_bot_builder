<?php

namespace App\MoonShine\Resources;


use App\Models\MoonshineTranslate;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


use Modules\FeedbackBot\Entities\Newslatter;
use Modules\PoolingBot\Entities\BotButton;
use Modules\PoolingBot\Entities\Post;
use MoonShine\Actions\ExportAction;
use MoonShine\Actions\ImportAction;
use MoonShine\Fields\BelongsTo;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class ButtonResource extends Resource
{
	public static string $model = BotButton::class;

	public static string $title = 'Buttons';
    public static string $orderField = 'count';
    public static string $orderType = 'desc';
    public static array $activeActions = ['show','delete','edit'];
	public function fields(): array
	{
        $text = MoonshineTranslate::query()->where('id',1)->first();
		return [
		    ID::make()->sortable()->showOnExport()->useOnImport(),
            Text::make($text->getTranslation('buttons',app()->getLocale(),false),'title')->showOnExport()->useOnImport(),
            Text::make($text->getTranslation('action_count',app()->getLocale(),false),'count')->showOnExport()->useOnImport(),
            Text::make($text->getTranslation('post_title',app()->getLocale(),false),'id', function (BotButton $botButton) {
                if (!empty($botButton->post->title)) {
                return $botButton->post->title;
                } else {
                    return null;
                }
            })->hideOnCreate()->hideOnUpdate(),
            BelongsTo::make('Post Id','post')->showOnExport()->useOnImport()->hideOnIndex()->hideOnCreate()->hideOnUpdate(),

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
        $bots = Post::where('user_id', auth()->user()->id)->get();
        $botIds = $bots->pluck('id');

        return parent::query()->whereIn('post_id', $botIds);
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
