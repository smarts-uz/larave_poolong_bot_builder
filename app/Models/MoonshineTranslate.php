<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MoonshineTranslate extends Model
{
    use HasTranslations;

    protected $translatable = [
        'bot_toke',
        'base_url',
        'bot_username',
        'bot_chat_title',
        'group_id',
        'bot',
        'group_language',
        'group_on_off',
        'first_action_message',
        'repeated_action_message',
        'unfollow_users_message',
        'bot_input_text',
        'bot_response_text',
        'user_name',
        'user_lastname',
        'language_code',
        'bot_incoming_messages',
        'bot_response_messages',
        'post_title',
        'tg_post_url_title',
        'post_content',
        'media_content',
        'add_media',
        'post_buttons',
        'add_button',
        'buttons',
        'action_count',
        'file',
        'user_info',
        'button_title',
    ];
}
