<?php

namespace Modules\FeedbackBot\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BotFatherChat extends Model
{
    use HasFactory;
    protected $table = 'fb_bot_father_chats';

    protected $fillable = [
        'chat_id',
        'title',
        'telegram_bot_id',
        'language_code',
    ];
}
