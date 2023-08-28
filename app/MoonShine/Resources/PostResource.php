<?php

namespace App\MoonShine\Resources;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

use MoonShine\Actions\ExportAction;
use MoonShine\Actions\ImportAction;
use MoonShine\CKEditor\Fields\CKEditor;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\BelongsTo;
use MoonShine\Fields\File;
use MoonShine\Fields\HasMany;
use MoonShine\Fields\HasOne;
use MoonShine\Fields\SwitchBoolean;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Fields\TinyMce;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;
use MoonShine\Trix\Fields\Trix;

class PostResource extends Resource
{
	public static string $model = Post::class;

	public static string $title = 'Posts';
    public string $titleField = 'id';

	public function fields(): array
	{
		return [
            Grid::make([
               Column::make([
                   Block::make('Basic Information',[
                       Text::make('Title','title')->required()->showOnExport()->useOnImport(),
                       Textarea::make('Content','content')->required()->showOnExport()->useOnImport(),
                   ]),
               ]),
                       Column::make([
                           Block::make('Media Content',[
                               HasOne::make('Add media','media')->fields([
                                   ID::make()->sortable()->showOnExport(),
                                   File::make('File','file_name')
                                       ->dir('/media')
                                       ->keepOriginalFileName()
                                       ->removable()
                                       ->allowedExtensions([
                                           'jpg',
                                           'jpeg',
                                           'png',
                                           'mp4',
                                           'avi',
                                           'mov',
                                           'mkv',
                                           'wmv',
                                           'mpeg',
                                           'mpg',
                                           '3gp',
                                           'webm',
                                       ]),
                               ])->hideOnIndex()->fullPage()->required(),
                           ]),
                       ])->columnSpan(6),
                       Column::make([
                           Block::make('Bot Buttons',[
                               HasMany::make('Button')->fields([
                                   ID::make(),
                                   Text::make('Add button','title'),
                                   Text::make('Action count','count')->hidden()
                               ])->hideOnIndex()->fullPage()->required(),
                           ]),
                       ])->columnSpan(6),

                        SwitchBoolean::make('Publish a Post', 'is_published')
                            ->default(false)
                            ->showOnCreate(false)
                            ->showOnDetail(false)
                            ->showOnUpdate(false),
            ]),
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
            ->dir('posts'),
            ImportAction::make('Import')->showInLine()
                ->disk('public')
                ->dir('buttons'),
        ];
    }
}
