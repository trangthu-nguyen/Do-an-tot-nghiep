<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('category_id', 'desc')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|max:100',
            'description' => 'nullable|max:255',
        ]);

        Category::create([
            'category_name' => $request->category_name,
            'description' => $request->description
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required|max:100',
            'description' => 'nullable|max:255',
        ]);

        $category = Category::findOrFail($id);

        $category->update([
            'category_name' => $request->category_name,
            'description' => $request->description
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->services()->count() > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'Không thể xóa danh mục này vì vẫn còn dịch vụ bên trong.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Xóa danh mục thành công!');
    }
}