<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use MoonShine\Models\MoonshineUser;

class TgBot extends Model
{
    use SoftDeletes;

    protected $table = 'tg_bots';
    protected $fillable = ['bot_token','bot_username','tg_user_id','user_id','executed_time','process_id'];

    public function groups(): BelongsTo
    {
        return $this->belongsTo(TgGroup::class);
    }
}
