<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private const ADMIN = 'admin';

    /**
     * Get all user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if ($this->getMyRole() === self::ADMIN) {
            return view('admin.home');
        }
        return redirect('admin/login')->with('error', "Don't have role");
    }

    /**
     * Get role auth
     *
     * @return mixed
     */
    public function getMyRole()
    {
        return Auth::user()->role->name;
    }
}
