<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
    ];

    /**
     * Relation with category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function category()
    {
        return $this->belongsToMany(Category::class, 'detail_posts', 'post_id', 'category_id')->withTimestamps();
    }

    /**
     * Relation with detail posts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detail_posts()
    {
        return $this->hasMany(Detail_Posts::class, 'id', 'post_id');
    }


    /**
     * @param $query
     * @param $request
     * @return mixed
     */
    public function scopeCategory($query, $request)
    {
        if ($request->has('category') && !is_null($request->category)) {
            $post_id = Detail_Posts::select('post_id')->where('category_id', $request->category)->get()->toArray();
            $query->whereIn('id', $post_id);
        }
        return $query;
    }

    public function scopeTitle($query, $request)
    {
        if ($request->has('title') && !is_null($request->title)) {
            $query->where('title', 'LIKE', '%' . $request->title . '%');
        }
        return $query;
    }
}
