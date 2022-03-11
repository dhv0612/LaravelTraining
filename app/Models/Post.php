<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\File;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

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
        'voucher_enabled',
        'voucher_quantity',
    ];

    private array $url_post;
    private array $user;

    /**
     * Constructor
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct()
    {
        $this->url_post = app('config')->get('appication.post');
        $this->user = app('config')->get('auth.auth');
    }

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

    /**
     * Function add post
     *
     * @param $request
     * @return void
     */
    public function add_post($request)
    {
        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
        $post->voucher_enabled = false;
        if (!is_null($request->voucher_enabled)) {
            $post->voucher_enabled = true;
        }
        if ($request->voucher_quantity > 0) {
            $post->voucher_quantity = $request->voucher_quantity;
        }

        if ($request->hasFile('image')) {
            $get_image = $request->file('image');

            $new_image = date('Ymdhis') . '.' . $get_image->getClientOriginalExtension();
            $post->image = $this->url_post['url'] . $new_image;
            $get_image->move($this->url_post['url'], $new_image);
        }

        $post->save();

        $list_categories = $request->category;
        foreach ($list_categories as $category) {
            $post->category()->attach($category);
        }
    }

    /**
     * Function update post
     *
     * @param $request
     * @param $id
     * @return void
     */
    public function update_post($request, $id)
    {
        $post = Post::find($id);
        $post->title = $request->title;
        $post->description = $request->description;
        $post->voucher_enabled = false;

        if (!is_null($request->voucher_enabled)) {
            $post->voucher_enabled = true;
        }
        if ($request->add_voucher_quantity > 0) {
            $post->voucher_quantity = $post->voucher_quantity + $request->add_voucher_quantity;
        }
        if ($request->hasFile('image')) {

            if (File::exists($post->image)) {
                File::delete($post->image);
            }

            $get_image = $request->file('image');

            $new_image = date('Ymdhis') . '.' . $get_image->getClientOriginalExtension();
            $post->image = $this->url_post['url'] . $new_image;
            $get_image->move($this->url_post['url'], $new_image);
        }

        $post->save();
        $list_categories = $request->category;
        $post->category()->sync($list_categories);
    }

    /**
     * Function delete post
     *
     * @param $id
     * @return void
     */
    public function delete_post($id)
    {
        $post = Post::find($id);
        $post->category()->detach();
        $post->user()->detach();
        if (File::exists($post->image)) {
            File::delete($post->image);
        }
        $post->delete();
    }
}
