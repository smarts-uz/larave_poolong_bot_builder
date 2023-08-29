<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class TgBotText extends Model
{
    use HasTranslations;

    protected $fillable = ['first_action_msg', 'repeated_action_msg', 'follow_msg'];
    public $translatable = ['first_action_msg','repeated_action_msg','follow_msg'];
}
