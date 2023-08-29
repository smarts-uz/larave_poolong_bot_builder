<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TgGroup extends Model
{
    use SoftDeletes;

    protected $table = 'tg_groups';
}
