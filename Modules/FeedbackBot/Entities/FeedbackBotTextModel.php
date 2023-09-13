<?php

namespace Modules\FeedbackBot\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class FeedbackBotTextModel extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'fb_bot_text';
    protected $translatable =['feedback_input','feedback_response','cancel', 'cancel_button'];
}
