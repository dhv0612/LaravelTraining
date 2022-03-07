<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_Posts extends Model
{
    use HasFactory;
    protected $table = 'detail_posts';

     public function post()
    {
        return $this->belongsTo(Post::class, 'id', 'post_id');
    }
}
