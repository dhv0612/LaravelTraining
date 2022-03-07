<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthController extends Controller
{
    /**
     * Request screen register
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function get_register()
    {
        $roles = Role::all();
        return view('admin.register')->with('roles', $roles);
    }

    /**
     * Request function register
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register(Request $request)
    {
        try {
            $roles = Role::all();
            $user = User::create([
                'email' => $request->email,
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'role_id' => $request->role,
            ]);
            $user->createToken('authToken')->plainTextToken;

            return redirect(route('screen_admin_login'));
        } catch (Exception $e) {

            return redirect(route('screen_admin_register'))
                ->with('error', $e->getMessage())
                ->with('roles', $roles);
        }
    }

    /**
     * Request screen login
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function get_login()
    {
        return view('admin.login');
    }

    /**
     * Request login with email & password
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return redirect(route('screen_admin_login'))->with("error", "Email or password is wrong");
            }

            $user = User::where('email', $request->email)->first();

            if (!Hash::check($request->password, $user->password, [])) {
                throw new Exception('Error in Login');
            }

            $user->createToken('authToken')->plainTextToken;

            return redirect(route('screen_admin_home'))->with('success', 'Login success!');
        } catch (Exception $e) {
            return redirect(route('get_login'))->with('error', $e->getMessage());
        }
    }

    /**
     * Function  Logout
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        $now = Date::now()->toDateTime();
        User::where('id', Auth::id())->update(['last_active_datetime' => $now]);
        Auth::guard('web')->logout();
        return redirect(route('screen_admin_login'));
    }

    /**
     * Screen home
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return view('admin.home');
    }
}
