<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private function checkAdmin()
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function index()
    {
        $this->checkAdmin();
        $categories = Category::withCount('products')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $this->checkAdmin();
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($request->only(['name', 'icon', 'description']));
        
        ActivityLog::log('created', "Created category: {$category->name}", $category);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function edit($id)
    {
        $this->checkAdmin();
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $this->checkAdmin();
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string',
        ]);

        $category->update($request->only(['name', 'icon', 'description']));
        
        ActivityLog::log('updated', "Updated category: {$category->name}", $category);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy($id)
    {
        $this->checkAdmin();
        $category = Category::findOrFail($id);

        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category with products.');
        }

        $categoryName = $category->name;
        $category->delete();
        
        ActivityLog::log('deleted', "Deleted category: {$categoryName}");

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}
