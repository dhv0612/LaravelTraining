<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    /**
     * Request screen login
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function get_login()
    {
        return view('user.login');
    }

    /**
     * Screen home
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return view('user.home');
    }

    /**
     * Screen list posts
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function posts()
    {
        $posts = Post::all();
        return view('user.post', compact('posts'));
    }

    /**
     * Screen detail post
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function view_post($id)
    {
        $post = Post::with('category')->find($id);
        return view('user.view-post', compact('post'));
    }
}
