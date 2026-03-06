<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\BlogPost;

class BlogSeeder extends Seeder
{
    public function run()
    {
        $author = User::first() ?? User::factory()->create();
        
        $category = BlogCategory::firstOrCreate(['slug' => 'travel'], ['name' => 'Travel']);
        $tag = BlogTag::firstOrCreate(['slug' => 'luxury'], ['name' => 'Luxury']);
        
        $post = BlogPost::create([
            'title' => 'The Ultimate Guide to Alibaug',
            'slug' => 'ultimate-guide-to-alibaug',
            'content' => '<div><h2>Discover Alibaug</h2><p>Here are the top villas in Alibaug for your weekend getaway. Visit Kihim for great beaches.</p></div>',
            'excerpt' => 'A curated list of the most stunning luxury homes for rent.',
            'author_id' => $author->id,
            'category_id' => $category->id,
            'status' => 'published',
            'is_featured' => true,
            'reading_time' => 5,
            'published_at' => now(),
        ]);
        
        $post->tags()->attach($tag->id);
    }
}
