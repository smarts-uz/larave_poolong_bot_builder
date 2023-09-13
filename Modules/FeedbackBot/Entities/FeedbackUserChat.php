<?php

namespace Modules\FeedbackBot\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeedbackUserChat extends Model
{
    use HasFactory;
    protected static $unguarded = true;
    protected $table = 'fb_bot_users';
    protected $fillable =['botid'];
    public $timestamps = false;
    public $incrementing = false;
}
