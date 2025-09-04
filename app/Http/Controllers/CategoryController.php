<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Category::class);

        $categories = Auth::user()->categories()->paginate(10);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Category::class);

        return view('categories.create');
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
        $this->authorize('update', $category);

        return view('categories.edit', compact('category'));
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
        try {
            $this->authorize('delete', $category);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->route('categories.index')->with('toast', ['message' => $e->getMessage(), 'type' => 'error']);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('toast', ['message' => 'Categoria Removida com sucesso!', 'type' => 'success']);
    }
}
