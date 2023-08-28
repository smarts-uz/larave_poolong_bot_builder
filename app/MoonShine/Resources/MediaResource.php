<?php

namespace App\MoonShine\Resources;

use App\Models\BotButton;
use Illuminate\Database\Eloquent\Model;
use App\Models\Media;

use MoonShine\Actions\ExportAction;
use MoonShine\Actions\ImportAction;
use MoonShine\Fields\BelongsTo;
use MoonShine\Fields\File;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class MediaResource extends Resource
{
	public static string $model = Media::class;

	public static string $title = 'Media';

	public function fields(): array
	{
		return [
		    ID::make()->sortable()->showOnIndex()->useOnImport()->showOnExport(),
            File::make('File','file_name')
                ->dir('/mediad')
                ->keepOriginalFileName()
                ->removable()
                ->useOnImport()
                ->showOnExport()
                ->allowedExtensions(['jpg', 'gif', 'png']),
            Text::make('Post Title','id', function (Media $media) {
                return $media->post->title;
            })->hideOnCreate()->hideOnUpdate(),
            BelongsTo::make('Post Title', 'post')->showOnExport()->useOnImport()->hideOnIndex()->hideOnCreate()->hideOnUpdate(),
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
                ->dir('media'),
            ImportAction::make('Import')->showInLine()
                ->disk('public')
                ->dir('media'),
        ];
    }
}
