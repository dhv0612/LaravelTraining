<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthController extends Controller
{
    private const STATUS_OK = 200;
    private const STATUS_ERROR = 500;

    private const ADMIN = 'admin';

    private const ERROR_LOGIN = 'Error in Login';

    /**
     * Request screen register
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register(Request $request)
    {
        try {
            $roles = Role::all();
            $data = $request->validate([
                'email' => 'email|required',
                'password' => 'required',
                'name' => 'required|max:255',
                'role' => 'required|max:255',
            ]);
            $user = User::create([
                'email' => $data['email'],
                'name' => $data['name'],
                'password' => Hash::make($data['password']),
                'role_id' => $data['role'],
            ]);
            $token = $user->createToken('authToken')->plainTextToken;

            return redirect('admin/login');
        } catch (Exception $e) {
            return redirect('/admin/register')
                ->with('error', $e->getMessage())
                ->with('roles', $roles);
        }
    }

    /**
     * Request screen login
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getLogin()
    {
        if (Auth::check()) {
            if ($this->getMyRole() === self::ADMIN) {
                return redirect('/admin/home');
            }
            return redirect('/admin/login')->with('error', "Don't have role");
        }
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
                return redirect('/admin/login')->with("error", "Email or password is wrong");
            }

            $user = User::where('email', $request->email)->first();

            if (!Hash::check($request->password, $user->password, [])) {
                throw new Exception(self::ERROR_LOGIN);
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return redirect('/admin/home')->with('success', 'Login success!');
        } catch (Exception $e) {
            return redirect('/admin/login')->with('error', $e->getMessage());
        }
    }

    /**
     * Function  Logout
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect('/admin/login');
    }

    /**
     * Get role auth
     *
     * @return mixed
     */
    private function getMyRole()
    {
        return Auth::user()->role->name;
    }
}
