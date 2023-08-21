<?php

namespace App\MoonShine\Resources;

use App\Models\BotButton;
use Illuminate\Database\Eloquent\Model;


use MoonShine\Fields\BelongsTo;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class ButtonResource extends Resource
{
	public static string $model = BotButton::class;

	public static string $title = 'Buttons';

	public function fields(): array
	{
		return [
		    ID::make()->sortable(),
            Text::make('Заголовок','title'),
            BelongsTo::make('Buttons','post'),

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
