<?php

namespace Modules\FeedbackBot\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class TelegramBot extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'fb_tg_bots';
//    protected static $unguarded = true;
    protected $guarded = [];

    public $translatable = [
        'default_bot_input_text',
        'user_bot_input_text',
        'default_bot_response_text',
        'user_bot_response_text',
    ];
}
