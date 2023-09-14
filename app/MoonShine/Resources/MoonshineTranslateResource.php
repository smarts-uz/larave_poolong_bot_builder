<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\MoonshineTranslate;

use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;
use VI\MoonShineSpatieTranslatable\Fields\Translatable;

class MoonshineTranslateResource extends Resource
{
	public static string $model = MoonshineTranslate::class;

	public static string $title = 'MoonshineTranslates';

	public function fields(): array
	{
		return [
		    ID::make()->sortable(),
            Translatable::make('bot_toke','bot_toke')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('base_url','base_url')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('bot_username','bot_username')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('bot_chat_title','bot_chat_title')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('group_id','group_id')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('bot','bot')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('group_language','group_language')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('group_on_off','group_on_off')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('first_action_message','first_action_message')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('repeated_action_message','repeated_action_message')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('unfollow_users_message','unfollow_users_message')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('bot_input_text','bot_input_text')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('bot_response_text','bot_response_text')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('user_name','user_name')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('user_lastname','user_lastname')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('language_code','language_code')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('bot_incoming_messages','bot_incoming_messages')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('bot_response_messages','bot_response_messages')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('post_title','post_title')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('tg_post_url_title','tg_post_url_title')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('post_content','post_content')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('media_content','media_content')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('add_media','add_media')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('post_buttons','post_buttons')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('add_button','add_button')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('buttons','buttons')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('action_count','action_count')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('file','file')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('user_info','user_info')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
            Translatable::make('button_title','button_title')
                ->priorityLanguages([config('app.fallback_locale'), config('app.locale'), 'en', 'ru', 'uz']),
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
