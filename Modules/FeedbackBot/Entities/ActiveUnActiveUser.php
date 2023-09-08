<?php

namespace Modules\FeedbackBot\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActiveUnActiveUser extends Model
{
    use HasFactory;

    protected $table = 'active_unactive_user';

    protected $guarded = [];
}
