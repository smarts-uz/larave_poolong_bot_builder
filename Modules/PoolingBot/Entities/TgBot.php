<?php

namespace Modules\PoolingBot\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TgBot extends Model
{
    use SoftDeletes;

    protected $table = 'tg_bots';
    protected $fillable = ['bot_token','bot_username','tg_user_id','user_id','executed_time','process_id'];


    public function groups(): HasMany
    {
        return $this->hasMany(TgGroup::class);
    }

    public function text()
    {
        return $this->belongsTo(TgBotText::class);
    }
}
