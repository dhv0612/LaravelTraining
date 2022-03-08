<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CategoryController extends Controller
{
    private array $user;

    /**
     * Constructor
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct()
    {
        $this->user = app('config')->get('auth.auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function index()
    {
        if ($this->get_my_role() !== $this->user['role_admin']) {
            return redirect(route('screen_home'));
        }
        $categories = Category::all();
        return view('admin.add-category', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function create()
    {
        if ($this->get_my_role() !== $this->user['role_admin']) {
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
     * @param CategoryRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(CategoryRequest $request)
    {
        if ($this->get_my_role() !== $this->user['role_admin']) {
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
     * @param Category $category
     * @return void
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Category $category
     * @return void
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Category $category
     * @return void
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return void
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
    private function get_my_role()
    {
        return Auth::user()->role->name;
    }
}
