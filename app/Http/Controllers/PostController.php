<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PostController extends Controller
{
    private array $url_post;
    private array $user;
    private Post $post_model;

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
        $this->post_model = new Post();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function index(Request $request)
    {
        if ($this->get_my_role() !== $this->user['role_admin']) {
            return redirect(route('screen_admin_home'));
        }
        $categories = Category::all();
        $posts = Post::query()->category($request)->title($request)->orderBy('id', 'DESC')->paginate(5);

        return view('admin.post', compact('posts', 'categories'));
    }

    /**
     * Display add post
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function create(Request $request)
    {
        if ($this->get_my_role() !== $this->user['role_admin']) {
            return redirect(route('screen_admin_home'));
        }
        $categories = Category::all();

        return view('admin.add-post', compact('categories'));
    }

    /**
     * Add post
     *
     * @param PostRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(PostRequest $request)
    {
        if ($this->get_my_role() !== $this->user['role_admin']) {
            return redirect(route('screen_admin_home'));
        }
        $this->post_model->add_post($request);

        return redirect(route('screen_list_posts'));
    }

    /**
     * Screen edit post
     *
     * @param $id
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function edit($id)
    {
        if ($this->get_my_role() !== $this->user['role_admin']) {
            return redirect(route('screen_admin_home'));
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
     * @return Application|RedirectResponse|Redirector
     */
    public function update(PostRequest $request, $id)
    {
        if ($this->get_my_role() !== $this->user['role_admin']) {
            return redirect(route('screen_admin_home'));
        }
        $this->post_model->update_post($request, $id);

        return redirect(route('screen_list_posts'));
    }

    /**
     * Delete post
     *
     * @param $id
     * @return Application|RedirectResponse|Redirector
     */
    public function delete($id)
    {
        if ($this->get_my_role() !== $this->user['role_admin']) {
            return redirect(route('screen_admin_home'));
        }
        $this->post_model->delete_post($id);

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
