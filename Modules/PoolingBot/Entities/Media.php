<?php

namespace Modules\PoolingBot\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['file_name','post_id'];
    protected $table = 'tg_media';

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
