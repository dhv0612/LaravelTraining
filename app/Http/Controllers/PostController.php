<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    private array $url_post;

    private array $user;

    /**
     * Constructor
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct()
    {
        $this->url_post = app('config')->get('appication.post');
        $this->user = app('config')->get('auth.auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($this->get_my_role() !== $this->user['role_admin']) {
            return redirect(route('screen_home'));
        }
        $categories = Category::all();
        $posts = Post::query()->category($request)->title($request)->orderBy('id', 'DESC')->paginate(5);
        return view('admin.post', compact('posts', 'categories'));
    }

    /**
     * Display add post
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        if ($this->get_my_role() !== $this->user['role_admin']) {
            return redirect(route('screen_home'));
        }
        $categories = Category::all();
        return view('admin.add-post', compact('categories'));
    }

    /**
     * Add post
     *
     * @param PostRequest $request
     * @return void
     */
    public function store(PostRequest $request)
    {
        if ($this->get_my_role() !== $this->user['role_admin']) {
            return redirect(route('screen_home'));
        }
        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
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

        return redirect(route('screen_list_posts'));
    }

    /**
     * Screen edit post
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        if ($this->get_my_role() !== $this->user['role_admin']) {
            return redirect(route('screen_home'));
        }
        $post = Post::with('category')->find($id);
        $detail_post = [];
        foreach ($post->category as $key => $post_cateID) {
            $detail_post[] = $post_cateID->id;
        }
        $categories = Category::all();
        return view('admin.edit-post', compact('post', 'categories', 'detail_post'));
    }

    /**
     * Update post
     *
     * @param PostRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(PostRequest $request, $id)
    {
        if ($this->get_my_role() !== $this->user['role_admin']) {
            return redirect(route('screen_home'));
        }
        $post = Post::find($id);
        $post->title = $request->title;
        $post->description = $request->description;
        if ($request->hasFile('image')) {
            $get_image = $request->file('image');

            $new_image = date('Ymdhis') . '.' . $get_image->getClientOriginalExtension();
            $post->image = $this->url_post['url'] . $new_image;
            $get_image->move($this->url_post['url'], $new_image);
        }

        $post->save();
        $list_categories = $request->category;
        $post->category()->sync($list_categories);

        return redirect(route('screen_list_posts'));
    }

    /**
     * Delete post
     *
     * @param $id
     * @return void
     */
    public function delete($id)
    {
        if ($this->get_my_role() !== $this->user['role_admin']) {
            return redirect(route('screen_home'));
        }
        $post = Post::find($id);
        $post->category()->detach();
        $post->delete();
        return redirect()->back();
    }

    /**
     * Get role auth
     *
     * @return mixed
     */
    private function get_my_role()
    {
        return Auth::user()->role->name;
    }
}
