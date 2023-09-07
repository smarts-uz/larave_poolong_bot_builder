<?php

namespace App\MoonShine\Resources;


use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\PoolingBot\Entities\Media;
use Modules\PoolingBot\Entities\Post;
use MoonShine\Actions\ExportAction;
use MoonShine\Actions\FiltersAction;
use MoonShine\Actions\ImportAction;
use MoonShine\Fields\BelongsTo;
use MoonShine\Fields\File;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;

class MediaResource extends Resource
{
	public static string $model = Media::class;

	public static string $title = 'Media';
    public static array $activeActions = ['show','delete','edit'];
	public function fields(): array
	{
		return [
		    ID::make()->sortable()->showOnIndex()->useOnImport()->showOnExport(),
            File::make(trans('moonshine::ui.custom.file'),'file_name')
                ->dir('/mediad')
                ->keepOriginalFileName()
                ->removable()
                ->useOnImport()
                ->showOnExport()
                ->allowedExtensions(['jpg', 'gif', 'png']),
            Text::make(trans('moonshine::ui.custom.post_title'),'id', function (Media $media) {
                if (!empty($media->post->title)) {

                return $media->post->title;
                } else {
                    return null;
                }
            })->hideOnCreate()->hideOnUpdate(),
            BelongsTo::make(trans('moonshine::ui.custom.post_title'), 'post')->showOnExport()->useOnImport()->hideOnIndex()->hideOnCreate()->hideOnUpdate(),
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
                ->dir('media'),
            ImportAction::make('Import')->showInLine()
                ->disk('public')
                ->dir('media'),
        ];
    }
}
