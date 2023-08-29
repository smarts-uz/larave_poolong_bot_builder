<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostUser extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['tg_user_id','tg_post_id','tg_button_id'];
    protected $table = 'tg_post_users';

    public function posts(): BelongsTo
    {
        return $this->belongsTo(Post::class,'tg_post_id');
    }
    public function users(): BelongsTo
    {
        return $this->belongsTo(TelegramUser::class,'tg_user_id');
    }
    public function buttons(): BelongsTo
    {
        // Укажите соответствие между столбцами в модели и в базе данных.
        return $this->belongsTo(BotButton::class, 'tg_button_id');
    }
}
