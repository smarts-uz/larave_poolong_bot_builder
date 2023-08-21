<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Media;

use MoonShine\Fields\BelongsTo;
use MoonShine\Fields\File;
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
		    ID::make()->sortable(),
            File::make('File','file_name')
                ->dir('/mediad')
                ->keepOriginalFileName()
                ->removable()
                ->allowedExtensions(['jpg', 'gif', 'png']),
            BelongsTo::make('Media', 'post'),
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
        ];
    }
}
