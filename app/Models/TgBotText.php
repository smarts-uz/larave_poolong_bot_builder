<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class TgBotText extends Model
{
    use SoftDeletes;

    protected $fillable = ['first_action_msg', 'repeated_action_msg', 'follow_msg','bot_id'];

    public function bots()
    {
        return $this->hasOne(TgBot::class,'id','bot_id');
    }
}
