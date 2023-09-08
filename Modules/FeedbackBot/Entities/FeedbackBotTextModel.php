<?php

namespace Modules\FeedbackBot\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeedbackBotTextModel extends Model
{
    use HasFactory, Translatable;

    protected $table = 'feedback_bot_text';
    protected $translatable =['feedback_input','feedback_response','cancel', 'cancel_button'];
}
