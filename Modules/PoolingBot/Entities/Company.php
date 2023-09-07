<?php

namespace Modules\PoolingBot\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use  HasFactory,SoftDeletes;

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function media()
    {
        return $this->hasManyThrough(Media::class, Post::class);
    }
}
