<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Category::class);

        $categories = Auth::user()->categories()->paginate(10);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Category::class);

        return view('categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        Auth::user()->categories()->create($request->validated());

        return redirect()->route('categories.index')->with('toast', ['message' => 'Categoria Criada com sucesso!', 'type' => 'success']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        Gate::authorize('update', $category);

        return view('categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()->route('categories.index')->with('toast', ['message' => 'Categoria Atualizada com sucesso!', 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $response = Gate::inspect('delete', $category);

        if ($response->denied()) {
            return redirect()->route('categories.index')->with('toast', ['message' => $response->message(), 'type' => 'error']);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('toast', ['message' => 'Categoria Removida com sucesso!', 'type' => 'success']);
    }
}
