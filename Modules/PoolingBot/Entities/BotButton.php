<?php

namespace Modules\PoolingBot\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BotButton extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['title','post_id'];

    protected $table = 'tg_buttons';

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function users()
    {
        // Укажите соответствие между столбцами в модели и в базе данных.
        return $this->belongsToMany(TelegramUser::class, 'tg_post_users', 'tg_button_id', 'tg_user_id');
    }
}
