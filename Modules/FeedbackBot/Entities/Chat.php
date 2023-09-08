<?php

namespace Modules\FeedbackBot\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'bot_chats';
    protected $fillable = [
        'chat_id',
        'title',
        'telegram_bot_id',
        'language_code',
    ];
}
