<?php

namespace Modules\FeedbackBot\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TelegramUser extends Model
{
    use HasFactory;

    protected $table = 'telegram_users';

    protected $guarded = [];
}
