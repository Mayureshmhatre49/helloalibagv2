<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Route;

class CheckInternalLinks extends Command
{
    protected $signature = 'check:internal-links';
    protected $description = 'Scans all published blog posts for dead or 404 internal links';

    public function handle(): int
    {
        $posts = BlogPost::where('status', 'published')->get();
        $errorCount = 0;

        $this->info("Checking internal links across " . $posts->count() . " published posts...");

        foreach ($posts as $post) {
            preg_match_all('/href="(\/[^"#]*)"/i', $post->content, $matches);
            $paths = array_unique($matches[1] ?? []);

            foreach ($paths as $path) {
                $hasRoute = false;
                try {
                    $request = \Illuminate\Http\Request::create($path, 'GET');
                    $route = Route::getRoutes()->match($request);
                    $hasRoute = (bool) $route;
                } catch (\Exception $e) {
                    $hasRoute = false;
                }

                if (!$hasRoute) {
                    $this->error("Post #{$post->id} ({$post->title}) -> broken internal link: {$path}");
                    $errorCount++;
                }
            }
        }

        if ($errorCount === 0) {
            $this->info("All internal links are valid!");
            return 0;
        }

        $this->error("Found {$errorCount} broken internal link(s).");
        return 1;
    }
}
