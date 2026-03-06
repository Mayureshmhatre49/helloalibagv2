<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::withCount('posts')->get();
        return view('admin.blog.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.blog.categories.form', ['category' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_categories',
        ]);

        BlogCategory::create($validated);

        return redirect()->route('admin.blog.categories.index')->with('success', 'Blog Category created.');
    }

    public function edit(BlogCategory $category)
    {
        return view('admin.blog.categories.form', compact('category'));
    }

    public function update(Request $request, BlogCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_categories,slug,' . $category->id,
        ]);

        $category->update($validated);

        return redirect()->route('admin.blog.categories.index')->with('success', 'Blog Category updated.');
    }

    public function destroy(BlogCategory $category)
    {
        if ($category->posts()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete a category that has posts.');
        }

        $category->delete();
        return redirect()->route('admin.blog.categories.index')->with('success', 'Blog Category deleted.');
    }
}
