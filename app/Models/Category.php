<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory, NodeTrait;

    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Relation with post
     *
     * @return BelongsToMany
     */
    public function post()
    {
        return $this->belongsToMany(Post::class, 'detail_posts', 'category_id', 'post_id')->withTimestamps();
    }

    /**
     * Traverse
     *
     * @return string[]
     */
    public function traverse()
    {
        $nodes = Category::get()->toTree();
        $tree = '';
        $traverse = function ($categories, $prefix = '-') use (&$traverse, &$tree) {
            foreach ($categories as $category) {
                $tree .= PHP_EOL . $prefix . ' ' . $category->name . "/";
                $traverse($category->children, $prefix . '-');
            }
        };

        $traverse($nodes);
        $tree = explode('/', $tree);

        return $tree;
    }
}
