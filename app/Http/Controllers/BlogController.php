<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $query = BlogPost::with(['author', 'category'])
            ->where('status', 'published')
            ->latest('published_at');
            
        if (request('search')) {
            $searchTerm = request('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('excerpt', 'like', "%{$searchTerm}%");
            });
        }
            
        $featuredPost = (clone $query)->where('is_featured', true)->first() ?? (clone $query)->first();
        
        // Exclude featured post from the main grid if it exists and we're not searching
        if ($featuredPost && !request('search')) {
            $query->where('id', '!=', $featuredPost->id);
        }
        
        // Hide featured section if searching
        if (request('search')) {
            $featuredPost = null;
        }

        $posts = $query->paginate(12);
        
        // Load categories for sidebar/filter
        $categories = BlogCategory::withCount(['posts' => function($q) {
            $q->where('status', 'published');
        }])->having('posts_count', '>', 0)->get();

        return view('blog.index', compact('posts', 'featuredPost', 'categories'));
    }

    public function show($slug)
    {
        $post = BlogPost::with(['author', 'category', 'tags', 'relatedListings.primaryImage'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment views
        $post->increment('views_count');

        $postTags = $post->tags->pluck('id')->toArray();
        
        $relatedPostsQuery = BlogPost::with(['author', 'category'])
            ->where('status', 'published')
            ->where('id', '!=', $post->id);
            
        // If post has tags, prioritize posts with matching tags
        if (!empty($postTags)) {
            $relatedPosts = (clone $relatedPostsQuery)
                ->whereHas('tags', function ($q) use ($postTags) {
                    $q->whereIn('blog_tags.id', $postTags);
                })
                ->latest('published_at')
                ->take(3)
                ->get();
                
            // If less than 3 tag matches, backfill with same category
            if ($relatedPosts->count() < 3) {
                $needed = 3 - $relatedPosts->count();
                $excludeIds = $relatedPosts->pluck('id')->push($post->id);
                
                $categoryFallback = (clone $relatedPostsQuery)
                    ->whereNotIn('id', $excludeIds)
                    ->where('category_id', $post->category_id)
                    ->latest('published_at')
                    ->take($needed)
                    ->get();
                    
                $relatedPosts = $relatedPosts->concat($categoryFallback);
            }
        } else {
            // No tags, just use category
            $relatedPosts = (clone $relatedPostsQuery)
                ->where('category_id', $post->category_id)
                ->latest('published_at')
                ->take(3)
                ->get();
        }

        $parsed = $this->generateTocAndInjectIds($post->content);
        $contentWithToc = $parsed['html'];
        $toc = $parsed['toc'];

        // Implement Lightbox and Lazy Loading for inline images
        $post->content = $this->enhanceInlineImages($contentWithToc, $post->title);

        return view('blog.show', compact('post', 'relatedPosts', 'toc'));
    }

    private function generateTocAndInjectIds($content)
    {
        $toc = [];
        $html = preg_replace_callback('/<h([23])>(.*?)<\/h\1>/is', function ($matches) use (&$toc) {
            $level = $matches[1];
            $text = trim(strip_tags($matches[2]));
            // Str::slug() needs Illuminate\Support\Str
            $id = \Illuminate\Support\Str::slug($text) ?: 'section-' . count($toc);
            
            // Ensure uniqueness
            $originalId = $id;
            $counter = 1;
            $existingIds = array_column($toc, 'id');
            while (in_array($id, $existingIds)) {
                $id = $originalId . '-' . $counter;
                $counter++;
            }
            
            $toc[] = [
                'level' => $level,
                'text' => $text,
                'id' => $id,
            ];
            
            return "<h{$level} id=\"{$id}\" class=\"scroll-mt-24\">{$matches[2]}</h{$level}>";
        }, $content);

        return ['html' => $html, 'toc' => $toc];
    }

    private function enhanceInlineImages($content, $postTitle)
    {
        if (empty($content)) {
            return $content;
        }

        // Use regex to find all <img> tags injected by Trix
        $html = preg_replace_callback('/<img(.*?)\/?>/is', function ($matches) use ($postTitle) {
            $imgTag = $matches[0];
            $attributes = $matches[1];

            // Add loading="lazy" if not present
            if (strpos($imgTag, 'loading=') === false) {
                $imgTag = str_replace('<img', '<img loading="lazy"', $imgTag);
            }

            // Add alt text if missing or empty, using post title as fallback
            if (strpos($imgTag, 'alt=""') !== false || strpos($imgTag, 'alt=') === false) {
                if (strpos($imgTag, 'alt=""') !== false) {
                    $imgTag = str_replace('alt=""', 'alt="' . htmlentities($postTitle) . '"', $imgTag);
                } else {
                    $imgTag = str_replace('<img', '<img alt="' . htmlentities($postTitle) . '"', $imgTag);
                }
            }

            // Extract the src URL for the lightbox anchor
            preg_match('/src="([^"]+)"/i', $imgTag, $srcMatches);
            $src = $srcMatches[1] ?? '#';

            // Wrap the image in an fslightbox anchor
            return '<a data-fslightbox="article-gallery" href="' . $src . '" class="block cursor-zoom-in rounded-2xl overflow-hidden hover:opacity-95 transition-opacity">' . $imgTag . '</a>';
        }, $content);

        return $html;
    }

    public function vote(Request $request, BlogPost $post)
    {
        $request->validate([
            'type' => 'required|in:upvote,downvote'
        ]);

        // Simple cookie-based rate limiting to prevent spamming
        $cookieName = 'voted_post_' . $post->id;
        if ($request->cookie($cookieName)) {
            return response()->json([
                'success' => false,
                'message' => 'You have already voted on this article.'
            ], 429);
        }

        if ($request->type === 'upvote') {
            $post->increment('upvotes');
        } else {
            $post->increment('downvotes');
        }

        return response()->json([
            'success' => true,
            'upvotes' => $post->upvotes,
            'downvotes' => $post->downvotes
        ])->cookie($cookieName, true, 60 * 24 * 365); // 1 year cookie
    }

    public function feed()
    {
        $posts = BlogPost::where('status', 'published')
            ->latest('published_at')
            ->take(20)
            ->get();

        $content = view('blog.feed', compact('posts'))->render();
        return response($content, 200)->header('Content-Type', 'text/xml');
    }

    public function ogImage(BlogPost $post)
    {
        // If it has a featured image, just use that
        if ($post->featured_image && \Storage::disk('public')->exists($post->featured_image)) {
            return response()->file(storage_path('app/public/' . $post->featured_image));
        }

        // Generate dynamic OG image
        $img = \Image::canvas(1200, 630, '#101828'); // Dark slate background

        // Draw Hello Alibaug Text top center
        $img->text('Hello Alibaug Blog', 600, 100, function($font) {
            $font->file(public_path('fonts/PlayfairDisplay-Bold.ttf')); // Assuming you have this, otherwise system default
            $font->size(48);
            $font->color('rgba(255, 255, 255, 0.5)');
            $font->align('center');
            $font->valign('middle');
        });

        // Split title into lines manually for simple wrapping
        $words = explode(' ', $post->title);
        $lines = [];
        $currentLine = '';
        foreach ($words as $word) {
            if (strlen($currentLine . ' ' . $word) > 50) {
                $lines[] = trim($currentLine);
                $currentLine = $word;
            } else {
                $currentLine .= ' ' . $word;
            }
        }
        if (!empty($currentLine)) {
            $lines[] = trim($currentLine);
        }

        $y = 315 - (count($lines) * 40 / 2); // Center vertically
        foreach ($lines as $line) {
            $img->text($line, 600, $y, function($font) {
                // Remove specific font path if not guaranteed to exist to avoid 500 errors
                $font->file(5); // Built-in GD font 5 (largest)
                $font->size(72);
                $font->color('#ffffff');
                $font->align('center');
                $font->valign('middle');
            });
            $y += 80;
        }

        return $img->response('jpg', 90);
    }

    public function category($slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();
        
        $posts = BlogPost::with(['author', 'category'])
            ->where('category_id', $category->id)
            ->where('status', 'published')
            ->latest('published_at')
            ->paginate(12);

        return view('blog.category', compact('category', 'posts'));
    }

    public function tag($slug)
    {
        $tag = BlogTag::where('slug', $slug)->firstOrFail();
        
        $posts = $tag->posts()
            ->with(['author', 'category'])
            ->where('status', 'published')
            ->latest('published_at')
            ->paginate(12);

        return view('blog.tag', compact('tag', 'posts'));
    }

    public function author($slug)
    {
        // Actually, we don't have author slugs natively in User unless added earlier.
        // Let's use ID for now or find by email/name if slug wasn't implemented system-wide.
        // To be safe against DB schema mismatches, we'll try to support a basic author page or fail cleanly.
        abort(404, 'Author pages are currently disabled.');
    }
}
