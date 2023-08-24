<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelegramUser extends Model
{
    use SoftDeletes;

    protected $fillable = ['telegram_id','first_name','last_name','username','user_status'];
}
