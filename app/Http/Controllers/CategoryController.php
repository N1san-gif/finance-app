<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    // Список категорий пользователя
    public function index()
    {
        $categories = Auth::user()->categories()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
        ]);

        Auth::user()->categories()->create($request->only('name', 'type'));

        return redirect()->route('categories.index')->with('success', 'Категория создана.');
    }

    public function edit(Category $category)
    {
        $this->authorize('update', $category);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
        ]);

        $category->update($request->only('name', 'type'));

        return redirect()->route('categories.index')->with('success', 'Категория обновлена.');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Категория перемещена в корзину.');
    }

    public function trashed()
{
    $categories = Category::onlyTrashed()
        ->where('user_id', auth()->id())
        ->paginate(10);

    return view('categories.trashed', compact('categories'));
}


    public function restore($id)
    {
        $category = Category::withTrashed()
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $this->authorize('restore', $category);

        $category->restore();

        return redirect()->route('categories.trashed')->with('success', 'Категория успешно восстановлена.');
    }

    public function forceDelete($id)
    {
        $category = Category::withTrashed()
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $this->authorize('forceDelete', $category);

        $category->forceDelete();

        return redirect()->route('categories.trashed')->with('success', 'Категория окончательно удалена.');
    }
    public function show(Category $category)
{
    $this->authorize('view', $category); // Если используется политика доступа

    return view('categories.show', compact('category'));
}

}
