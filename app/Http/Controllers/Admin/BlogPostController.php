<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\Listing;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with(['author', 'category'])->latest()->paginate(20);
        return view('admin.blog.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = BlogCategory::all();
        $tags = BlogTag::all();
        $listings = Listing::approved()->select('id', 'title')->get();
        return view('admin.blog.posts.form', ['post' => null, 'categories' => $categories, 'tags' => $tags, 'listings' => $listings]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_posts',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:1000',
            'category_id' => 'nullable|exists:blog_categories,id',
            'status' => 'required|in:draft,published',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'featured_image_alt' => 'nullable|string|max:255',
            'focus_keyword' => 'nullable|string|max:255',
            'seo_score' => 'nullable|integer|min:0|max:100',
        ]);

        $validated['author_id'] = auth()->id();
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_indexable'] = $request->has('is_indexable');
        
        $wordCount = str_word_count(strip_tags($request->input('content')));
        $validated['reading_time'] = max(1, ceil($wordCount / 200));

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $filename = 'blog/' . uniqid() . '.webp';
            $path = storage_path('app/public/' . $filename);
            
            // Ensure directory exists
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }

            \Image::make($image)
                ->fit(1200, 630)
                ->encode('webp', 90)
                ->save($path);
                
            $validated['featured_image'] = $filename;
        }

        $post = BlogPost::create($validated);

        if ($request->has('tags')) {
            $post->tags()->sync($request->input('tags'));
        }

        if ($request->has('related_listings')) {
            $post->relatedListings()->sync($request->input('related_listings'));
        }

        return redirect()->route('admin.blog.posts.index')->with('success', 'Blog Post created successfully.');
    }

    public function edit(BlogPost $post)
    {
        $categories = BlogCategory::all();
        $tags = BlogTag::all();
        $listings = Listing::approved()->select('id', 'title')->get();
        return view('admin.blog.posts.form', compact('post', 'categories', 'tags', 'listings'));
    }

    public function update(Request $request, BlogPost $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_posts,slug,' . $post->id,
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:1000',
            'category_id' => 'nullable|exists:blog_categories,id',
            'status' => 'required|in:draft,published',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'featured_image_alt' => 'nullable|string|max:255',
            'focus_keyword' => 'nullable|string|max:255',
            'seo_score' => 'nullable|integer|min:0|max:100',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_indexable'] = $request->has('is_indexable');
        
        $wordCount = str_word_count(strip_tags($request->input('content')));
        $validated['reading_time'] = max(1, ceil($wordCount / 200));

        if ($validated['status'] === 'published' && !$post->published_at) {
            $validated['published_at'] = now();
        } elseif ($validated['status'] === 'draft') {
            $validated['published_at'] = null;
        }

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $filename = 'blog/' . uniqid() . '.webp';
            $path = storage_path('app/public/' . $filename);
            
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }

            \Image::make($image)
                ->fit(1200, 630)
                ->encode('webp', 90)
                ->save($path);
                
            $validated['featured_image'] = $filename;
            
            // Delete old image
            if ($post->featured_image && file_exists(storage_path('app/public/' . $post->featured_image))) {
                unlink(storage_path('app/public/' . $post->featured_image));
            }
        }

        $post->update($validated);

        if ($request->has('tags')) {
            $post->tags()->sync($request->input('tags'));
        } else {
            $post->tags()->detach();
        }

        if ($request->has('related_listings')) {
            $post->relatedListings()->sync($request->input('related_listings'));
        } else {
            $post->relatedListings()->detach();
        }

        return redirect()->route('admin.blog.posts.index')->with('success', 'Blog Post updated successfully.');
    }

    public function destroy(BlogPost $post)
    {
        if ($post->featured_image && file_exists(storage_path('app/public/' . $post->featured_image))) {
            unlink(storage_path('app/public/' . $post->featured_image));
        }
        
        $post->delete();
        return redirect()->route('admin.blog.posts.index')->with('success', 'Blog Post deleted.');
    }
}
