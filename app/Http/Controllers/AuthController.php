<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Http\JsonResponse;
use Exception;

class AuthController extends Controller
{
    /**
     * Request screen register
     *
     * @return Application|Factory|View
     */
    public function getRegister()
    {
        $roles = Role::all();
        return view('admin.register')->with('roles', $roles);
    }

    /**
     * Request function register
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
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
                'last_active_datetime' => Date::now()->toDateTime(),
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
     * @return Application|Factory|View
     */
    public function getLogin()
    {
        return view('admin.login');
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
                return redirect(route('screen_admin_login'))->with("error", "Email or password is wrong");
            }

            $user = User::where('email', $request->email)->first();

            if (!Hash::check($request->password, $user->password, [])) {
                throw new Exception('Error in Login');
            }

            $user->createToken('authToken')->plainTextToken;
            $now = Date::now()->toDateTime();
            User::where('id', Auth::id())->update(['last_active_datetime' => $now]);
            return redirect(route('screen_admin_home'))->with('success', 'Login success!');
        } catch (Exception $e) {
            return redirect(route('screen_admin_login'))->with('error', $e->getMessage());
        }
    }

    /**
     * Function logout
     *
     * @return Application|RedirectResponse|Redirector
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
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('admin.home');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function loginApi(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!Hash::check($request->password, $user->password, [])) {
            throw new Exception('Error in Login');
        }

        $token = $user->createToken('authToken')->plainTextToken;
        return response()->json([
            'token' => $token,
        ]);
    }
}
