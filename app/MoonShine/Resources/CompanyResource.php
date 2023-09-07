<?php

namespace App\MoonShine\Resources;

use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Actions\FiltersAction;
use MoonShine\Decorations\Block;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;

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
