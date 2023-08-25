<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelegramUser extends Model
{
    use SoftDeletes;

    protected $fillable = ['telegram_id','first_name','last_name','username','user_status'];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_users','user_id','post_id');
    }

    public function buttons()
    {
        return $this->belongsToMany(BotButton::class, 'post_users','user_id','button_id');
    }
}
