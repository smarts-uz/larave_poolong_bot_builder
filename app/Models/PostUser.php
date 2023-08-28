<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostUser extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['user_id','post_id','button_id'];

    public function posts()
    {
        return $this->belongsTo(Post::class);
    }
    public function users()
    {
        return $this->belongsTo(TelegramUser::class);
    }
    public function buttons()
    {
        return $this->belongsTo(BotButton::class);
    }
}
