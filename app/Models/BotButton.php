<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BotButton extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['title','post_id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function posts()
    {
        return $this->belongsToMany(TelegramUser::class, 'post_users','button_id','user_id');
    }
}
