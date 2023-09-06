<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelegramUser extends Model
{
    use SoftDeletes;

    protected $fillable = ['telegram_id','first_name','last_name','username','user_status','bot_id'];
    protected $table = 'tg_users';

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'tg_post_users','tg_user_id','tg_post_id');
    }

    public function buttons()
    {
        // Укажите соответствие между столбцами в модели и в базе данных.
        return $this->belongsToMany(BotButton::class, 'tg_post_users', 'tg_user_id', 'tg_button_id');
    }
}
