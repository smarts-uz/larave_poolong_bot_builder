<?php

namespace Modules\PoolingBot\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TgBotText extends Model
{
    use SoftDeletes;

    protected $fillable = ['first_action_msg', 'repeated_action_msg', 'follow_msg','bot_id'];

    public function bots()
    {
        return $this->hasOne(TgBot::class,'id','bot_id');
    }
}
