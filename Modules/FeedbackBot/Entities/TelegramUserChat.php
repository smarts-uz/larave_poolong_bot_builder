<?php

namespace Modules\FeedbackBot\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TelegramUserChat extends Model
{
    use HasFactory;


    protected static $unguarded = true;
    protected $table = 'fb_tg_user_chat';
    protected $primaryKey = 'chat_id';
    public $timestamps = false;
    public $incrementing = false;
}
