<?php

namespace Modules\FeedbackBot\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class Newslatter extends Model
{
    use HasFactory;

    use HasFactory, HasTranslations;

    protected $table = 'fb_newslatter_translate';

    protected $translatable = [
        'main_menu_text',
        'main_menu_all_button',
        'main_menu_ru_button',
        'main_menu_en_button',
        'main_menu_uz_button',
        'cancel_button',
        'save_message_text',
        'preview_button',
        'start_newslatter_button',
        'newslatter_preview_text',
        'all_menu_text',
        'ru_menu_text',
        'en_menu_text',
        'uz_menu_text',
    ];
}
