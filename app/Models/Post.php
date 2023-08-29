<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MoonShine\Models\MoonshineUser;

class Post extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'telegram_message_id',
        'user_id',
        'company_id'
    ];
    protected $table = 'tg_posts';

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function media()
    {
        return $this->hasOne(Media::class);
    }

    public function button()
    {
        return $this->hasMany(BotButton::class);
    }
    public function posts()
    {
        // Укажите соответствие между столбцами в модели и в базе данных.
        return $this->belongsToMany(PostUser::class, 'tg_post_users', 'tg_post_id', 'tg_user_id');
    }

}
