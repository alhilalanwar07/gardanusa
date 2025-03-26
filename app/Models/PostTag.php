<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    protected $table = 'post_tags';

    protected $fillable = [
        'post_id',
        'tag_id',
    ];
}
