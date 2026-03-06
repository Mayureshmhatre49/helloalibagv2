@extends('layouts.app')

@push('title')
    <title>{{ $category->name }} | Hello Alibaug Blog</title>
@endpush

@push('meta')
    <meta name="description" content="Discover stories, guides, and insights about {{ $category->name }} in Alibaug.">
    @if($posts->previousPageUrl())
        <link rel="prev" href="{{ $posts->previousPageUrl() }}">
    @endif
    @if($posts->nextPageUrl())
        <link rel="next" href="{{ $posts->nextPageUrl() }}">
    @endif
@endpush

@section('content')
<main class="bg-slate-50 min-h-screen pb-20 pt-32 sm:pt-40">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        
        <!-- Breadcrumbs & Header -->
        <div class="mb-12 text-center">
            <nav class="flex justify-center text-sm text-slate-500 mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span>
                            <a href="{{ route('blog.index') }}" class="hover:text-primary transition-colors">Blog</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span>
                            <span class="text-charcoal font-semibold">{{ $category->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="font-display font-light text-4xl lg:text-5xl text-charcoal mb-4">{{ $category->name }}</h1>
            <p class="text-slate-500 max-w-2xl mx-auto">Showing latest articles and curated insights filed under {{ $category->name }}.</p>
        </div>

        <!-- Grid of Posts -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-12">
            @forelse($posts as $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="bg-white rounded-3xl overflow-hidden group border border-slate-100 hover:shadow-xl hover:shadow-black/5 transition-all duration-300 flex flex-col h-full hover:-translate-y-1">
                    <div class="aspect-[4/3] relative overflow-hidden">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->featured_image_alt ?: $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-slate-100"></div>
                        @endif
                    </div>
                    <div class="p-6 flex flex-col flex-1">
                        <h3 class="font-display text-xl font-bold text-charcoal leading-snug mb-3 group-hover:text-primary transition-colors line-clamp-2">{{ $post->title }}</h3>
                        <p class="text-sm text-slate-600 line-clamp-2 mb-6 flex-1">{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 100) }}</p>
                        <div class="flex items-center justify-between border-t border-slate-100 pt-4 mt-auto">
                            <span class="text-xs font-semibold text-slate-500">{{ $post->published_at->format('M d, Y') }}</span>
                            <span class="text-xs font-bold text-primary flex items-center gap-1">{{ $post->reading_time }} min read <span class="material-symbols-outlined text-[14px]">arrow_forward</span></span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-4xl text-slate-400">category</span>
                    </div>
                    <h3 class="text-xl font-bold text-charcoal mb-2">No articles found</h3>
                    <p class="text-slate-500">There are currently no published articles in this category.</p>
                </div>
            @endforelse
        </div>

        @if($posts->hasPages())
            <div class="mt-16 flex justify-center">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</main>
@endsection
