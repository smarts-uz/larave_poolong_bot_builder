<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use MoonShine\Models\MoonshineUser;

class TgBot extends Model
{
    use SoftDeletes;

    protected $table = 'tg_bots';

//    public function user(): HasOne
//    {
//        return $this->hasOne(MoonshineUser::class);
//    }
}
