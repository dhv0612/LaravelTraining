<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Read_Posts;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserController extends Controller
{

    /**
     * Request screen login
     *
     * @return Application|Factory|View
     */
    public function get_login()
    {
        return view('user.login');
    }

    /**
     * Request login with email & password
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function login(Request $request)
    {
        try {
            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return redirect(route('screen_user_login'))->with("error", "Email or password is wrong");
            }
            $user = User::where('email', $request->email)->first();

            if (!Hash::check($request->password, $user->password, [])) {
                throw new Exception('Error in Login');
            }

            $user->createToken('authToken')->plainTextToken;

            return redirect(route('screen_user_home'));
        } catch (Exception $e) {
            return redirect(route('screen_user_login'))->with('error', $e->getMessage());
        }
    }

    /**
     * Screen home
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('user.home');
    }

    /**
     * Screen list posts
     *
     * @return Application|Factory|View
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
     * @return Application|Factory|View
     */
    public function view_post($id)
    {
        $user = new User();
        $user->check_user_read_post($id);
        $post = Post::with('category')->find($id);
        $time_read = '';
        if (Auth::check()){
            $time_read = Read_Posts::select('times')->where('user_id', Auth::id())->where('post_id', $id)->first();
        }
        return view('user.view-post', compact('post', 'time_read'));
    }

    /**
     * Function logout
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function logout(){
        $now = Date::now()->toDateTime();
        User::where('id', Auth::id())->update(['last_active_datetime' => $now]);
        Auth::guard('web')->logout();
        return redirect(route('screen_user_home'));
    }
}
