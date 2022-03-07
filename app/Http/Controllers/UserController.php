<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private array $user;

    /**
     * Constructor
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct()
    {
        $this->user = app('config')->get('auth.auth');
    }

    /**
     * Get all user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return view('admin.home');
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
