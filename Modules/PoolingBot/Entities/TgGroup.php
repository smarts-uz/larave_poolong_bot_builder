<?php

namespace Modules\PoolingBot\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TgGroup extends Model
{
    use SoftDeletes;

    protected $fillable = ['group_id','title','tg_user_id','tg_bot_id','tg_bot_on','is_channel'];
    protected $table = 'tg_groups';


    public function bots()
    {
        return $this->belongsTo(TgBot::class);
    }
}
