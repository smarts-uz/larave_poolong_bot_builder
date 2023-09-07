<?php

namespace Modules\PoolingBot\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $table = 'user_roles';

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
