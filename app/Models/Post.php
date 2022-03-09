<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * @return BelongsToMany
     */
    public function category()
    {
        return $this->belongsToMany(Category::class, 'detail_posts', 'post_id', 'category_id')->withTimestamps();
    }

    /**
     * Relation with detail posts
     *
     * @return HasMany
     */
    public function detail_posts()
    {
        return $this->hasMany(Detail_Posts::class, 'id', 'post_id');
    }

    /**
     * Relation with user
     *
     * @return
     */
    public function user()
    {
        return $this->belongsToMany(User::class, 'read_posts', 'post_id', 'user_id')->withTimestamps();
    }

    /**
     * Relation with read posts
     *
     * @return HasMany
     */
    public function read_posts()
    {
        return $this->hasMany(Read_Posts::class, 'id', 'post_id');
    }

    /**
     * Filter with category
     *
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

    /**
     * Filter with title
     *
     * @param $query
     * @param $request
     * @return mixed
     */
    public function scopeTitle($query, $request)
    {
        if ($request->has('title') && !is_null($request->title)) {
            $query->where('title', 'LIKE', '%' . $request->title . '%');
        }
        return $query;
    }
}
