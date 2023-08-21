<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Company;

use MoonShine\Decorations\Block;
use MoonShine\Fields\BelongsTo;
use MoonShine\Fields\Date;
use MoonShine\Fields\File;
use MoonShine\Fields\HasMany;
use MoonShine\Fields\HasManyThrough;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class CompanyResource extends Resource
{
	public static string $model = Company::class;

	public static string $title = 'Companies';
    public string $titleField = 'name';

	public function fields(): array
	{
		return [
		    Block::make([
                ID::make()->sortable(),
                Text::make('Название','name'),
                Date::make('Дата окончания компании','end_date'),
            ])
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
