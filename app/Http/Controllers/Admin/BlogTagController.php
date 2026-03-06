<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogTag;
use Illuminate\Http\Request;

class BlogTagController extends Controller
{
    public function index()
    {
        $tags = BlogTag::withCount('posts')->get();
        return view('admin.blog.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.blog.tags.form', ['tag' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_tags',
        ]);

        BlogTag::create($validated);

        return redirect()->route('admin.blog.tags.index')->with('success', 'Blog Tag created.');
    }

    public function edit(BlogTag $tag)
    {
        return view('admin.blog.tags.form', compact('tag'));
    }

    public function update(Request $request, BlogTag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_tags,slug,' . $tag->id,
        ]);

        $tag->update($validated);

        return redirect()->route('admin.blog.tags.index')->with('success', 'Blog Tag updated.');
    }

    public function destroy(BlogTag $tag)
    {
        if ($tag->posts()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete a tag that has posts.');
        }

        $tag->delete();
        return redirect()->route('admin.blog.tags.index')->with('success', 'Blog Tag deleted.');
    }
}
