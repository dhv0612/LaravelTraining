<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\App;

class CategoryController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->getMyRole() !== $this->user['role_admin']) {
            return redirect(route('screen_home'));
        }
        $categories = Category::all();
        return view('admin.add-category', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->getMyRole() !== $this->user['role_admin']) {
            return redirect(route('screen_home'));
        }
        $categories = Category::all();
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
        return view('admin.category', compact('categories', 'tree'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($this->getMyRole() !== $this->user['role_admin']) {
            return redirect(route('screen_home'));
        }
        $category = Category::create([
            'name' => $request->category
        ]);

        if ($request->parent && $request->parent !== null) {
            //  Here we define the parent for new created category
            $node = Category::find($request->parent);

            $node->appendNode($category);
        }

        return redirect(route('screen_list_categories'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
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
