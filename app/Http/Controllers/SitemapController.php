<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function blog()
    {
        $posts = \App\Models\BlogPost::where('status', 'published')
                    ->where('is_indexable', true)
                    ->orderBy('published_at', 'desc')
                    ->get();

        return response()->view('blog.sitemap', compact('posts'))
                         ->header('Content-Type', 'text/xml');
    }
}
