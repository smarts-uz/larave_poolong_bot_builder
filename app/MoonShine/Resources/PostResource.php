<?php

namespace App\MoonShine\Resources;


use App\Models\MoonshineTranslate;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\PoolingBot\Entities\BotButton;
use Modules\PoolingBot\Entities\Media;
use Modules\PoolingBot\Entities\Post;
use MoonShine\Actions\ExportAction;
use MoonShine\Actions\FiltersAction;
use MoonShine\Actions\ImportAction;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\BelongsTo;
use MoonShine\Fields\File;
use MoonShine\Fields\HasMany;
use MoonShine\Fields\HasOne;
use MoonShine\Fields\ID;
use MoonShine\Fields\SwitchBoolean;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Resources\Resource;

class PostResource extends Resource
{
	public static string $model = Post::class;

	public static string $title = 'Posts';
    public string $titleField = 'id';
//    public static bool $withPolicy = true;

	public function fields(): array
	{
        $text = MoonshineTranslate::query()->where('id',1)->first();
		return [
            Grid::make([
               Column::make([
                   Block::make(trans('moonshine::ui.custom.basic_info'),[
                       Text::make($text->getTranslation('post_title',app()->getLocale(),false),'title')->required()->showOnExport()->useOnImport(),
                       Text::make($text->getTranslation('tg_post_url_title',app()->getLocale(),false),'url_title')->required()->showOnExport()->useOnImport()->hideOnIndex(),
                       Textarea::make($text->getTranslation('post_content',app()->getLocale(),false),'content')->required()->showOnExport()->useOnImport(),
                   ]),
               ]),
                       Column::make([
                           Block::make($text->getTranslation('media_content',app()->getLocale(),false),[
                               HasOne::make($text->getTranslation('add_media',app()->getLocale(),false),'media')->fields([
                                   ID::make()->sortable()->showOnExport(),
                                   File::make($text->getTranslation('file',app()->getLocale(),false))
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
                           Block::make($text->getTranslation('post_buttons',app()->getLocale(),false),[
                               HasMany::make($text->getTranslation('add_button',app()->getLocale(),false),'button')->fields([
                                   ID::make(),
                                   Text::make($text->getTranslation('buttons',app()->getLocale(),false),'title'),
                                   Text::make($text->getTranslation('action_count',app()->getLocale(),false),'count')->hidden()
                               ])->hideOnIndex()->fullPage()->required(),
                           ]),
                       ])->columnSpan(6),

                        Column::make([
                            Block::make($text->getTranslation('bot',app()->getLocale(),false),[
                                BelongsTo::make($text->getTranslation('bot',app()->getLocale(),false),'bot','bot_username')->required(),
//                                BelongsTo::make('Group','group',new TgGroupResource())
                            ])
                        ]),

                        SwitchBoolean::make(trans('moonshine::ui.custom.publish_post'), 'is_published')
                            ->default(false)
                            ->showOnCreate(false)
                            ->showOnDetail(false)
                            ->showOnUpdate(false),
            ]),
        ];
	}
    public function getActiveActions(): array
    {
        if (auth()->id() != $this->getItem()?->user_id) {
            return array_merge(static::$activeActions, ['delete']);
        }
        return static::$activeActions;
    }


    public function rules(Model $item): array
	{
	    return [];
    }

    public function search(): array
    {
        return ['id'];
    }
    public function query(): Builder
    {
        return parent::query()->where('user_id', auth()->user()->id);
    }

    public function filters(): array
    {
        return [];
    }
    public function beforeCreating(Model $item)
    {
        $item->user_id = request()->user()->id;
    }
    protected function afterDeleted(Model $item)
    {
        $media = Media::where('post_id', $item->id)->delete();
        $buttons = BotButton::where('post_id',$item->id)->delete();
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
