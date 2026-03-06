@extends('layouts.app')

@push('title')
    <title>Blog &amp; Travel Guides - Hello Alibaug | Tips, Reviews &amp; Local Insights</title>
@endpush

@push('meta')
    <meta name="description" content="Explore Alibaug through our curated travel guides, villa reviews, food recommendations, and local insights. Plan your perfect coastal getaway with Hello Alibaug.">
    <link rel="canonical" href="{{ route('blog.index') }}">
    @if($posts->previousPageUrl())
        <link rel="prev" href="{{ $posts->previousPageUrl() }}">
    @endif
    @if($posts->nextPageUrl())
        <link rel="next" href="{{ $posts->nextPageUrl() }}">
    @endif
@endpush

@section('content')
<main class="min-h-screen bg-white">

    {{-- ===== HERO BANNER ===== --}}
    <section class="bg-charcoal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 sm:pt-32 pb-10 sm:pb-14">
            <h1 class="text-white text-3xl sm:text-4xl lg:text-5xl font-bold font-display leading-tight mb-3">
                Blog & Guides
            </h1>
            <p class="text-slate-400 text-base sm:text-lg max-w-xl">
                Curated travel guides, villa reviews, and local insights to help you discover the best of Alibaug.
            </p>
        </div>
    </section>

    <div class="border-b border-slate-200 bg-white sticky top-[72px] sm:top-[88px] z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 py-3">
                <div class="flex items-center gap-2 overflow-x-auto scrollbar-hide -mx-1 flex-1">
                    <a href="{{ route('blog.index') }}"
                       class="shrink-0 px-4 py-1.5 rounded-full text-sm font-semibold {{ !request('search') && !isset($category) && !isset($tag) ? 'bg-charcoal text-white' : 'text-slate-600 bg-slate-100 hover:bg-charcoal hover:text-white transition-colors' }}">
                        All
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('blog.category', $cat->slug) }}"
                           class="shrink-0 px-4 py-1.5 rounded-full text-sm font-semibold {{ isset($category) && $category->id === $cat->id ? 'bg-charcoal text-white' : 'text-slate-600 bg-slate-100 hover:bg-charcoal hover:text-white transition-colors' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
                
                {{-- Search Bar --}}
                <form action="{{ route('blog.index') }}" method="GET" class="relative shrink-0 w-full sm:w-auto">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-slate-400">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search articles..." 
                           class="w-full sm:w-64 pl-10 pr-4 py-2 bg-slate-100 border-none rounded-full text-sm focus:ring-2 focus:ring-primary/30 focus:bg-white outline-none transition-all placeholder:text-slate-400 text-slate-700 font-medium">
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ===== FEATURED POST ===== --}}
        @if($featuredPost)
        <section class="py-10 sm:py-14 border-b border-slate-200">
            <a href="{{ route('blog.show', $featuredPost->slug) }}" class="group grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-10 items-center">
                {{-- Image --}}
                <div class="relative rounded-xl overflow-hidden aspect-[16/10]">
                    @if($featuredPost->featured_image)
                        <img src="{{ asset('storage/' . $featuredPost->featured_image) }}"
                             alt="{{ $featuredPost->featured_image_alt ?: $featuredPost->title }}"
                             class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-slate-100 flex items-center justify-center">
                            <span class="material-symbols-outlined text-5xl text-slate-300">image</span>
                        </div>
                    @endif
                    <span class="absolute top-3 left-3 bg-primary text-white text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded">Featured</span>
                </div>

                {{-- Text --}}
                <div>
                    @if($featuredPost->category)
                        <span class="text-primary text-xs font-bold uppercase tracking-widest">{{ $featuredPost->category->name }}</span>
                    @endif
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold font-display text-charcoal leading-snug mt-2 mb-3 group-hover:text-primary transition-colors">
                        {{ $featuredPost->title }}
                    </h2>
                    <p class="text-slate-500 text-base leading-relaxed line-clamp-3 mb-5">
                        {{ $featuredPost->excerpt ?? Str::limit(strip_tags($featuredPost->content), 180) }}
                    </p>
                    <div class="flex items-center gap-3">
                        <img src="{{ $featuredPost->author->getAvatarUrl() }}" alt="{{ $featuredPost->author->name }}" class="w-8 h-8 rounded-full">
                        <span class="text-sm font-semibold text-charcoal">{{ $featuredPost->author->name }}</span>
                        <span class="text-slate-300">·</span>
                        <span class="text-sm text-slate-500">{{ $featuredPost->published_at->format('M d, Y') }}</span>
                        <span class="text-slate-300">·</span>
                        <span class="text-sm text-slate-500">{{ $featuredPost->reading_time }} min read</span>
                    </div>
                </div>
            </a>
        </section>
        @endif

        {{-- ===== POSTS GRID ===== --}}
        <section class="py-10 sm:py-14">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 lg:gap-10">
                @forelse($posts as $post)
                <article class="group relative bg-white border border-transparent rounded-2xl hover:border-slate-200 hover:shadow-lg transition-all p-3 -m-3">
                    <a href="{{ route('blog.show', $post->slug) }}" class="block">
                        {{-- Thumbnail --}}
                        <div class="relative rounded-xl overflow-hidden aspect-[16/10] mb-5">
                            @if($post->featured_image)
                                <img src="{{ asset('storage/' . $post->featured_image) }}"
                                     alt="{{ $post->featured_image_alt ?: $post->title }}"
                                     class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-slate-100 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-4xl text-slate-300">image</span>
                                </div>
                            @endif
                            @if($post->category)
                                <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-charcoal text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded">{{ $post->category->name }}</span>
                            @endif
                        </div>

                        {{-- Meta --}}
                        <div class="flex items-center gap-2 text-xs text-slate-400 font-medium mb-3">
                            <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('M d, Y') }}</time>
                            <span>·</span>
                            <span>{{ $post->reading_time }} min read</span>
                        </div>

                        {{-- Title --}}
                        <h3 class="text-xl font-bold font-display text-charcoal leading-tight mb-2 group-hover:text-primary transition-colors line-clamp-2">
                            {{ $post->title }}
                        </h3>

                        {{-- Excerpt --}}
                        <p class="text-sm text-slate-500 leading-relaxed line-clamp-2 mb-4">
                            {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 110) }}
                        </p>
                        
                        {{-- Read More Link --}}
                        <span class="inline-flex items-center gap-1 text-sm font-semibold text-primary/80 group-hover:text-primary transition-colors">
                            Read article <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </span>
                    </a>
                </article>
                @empty
                    @if(!$featuredPost)
                    <div class="col-span-full py-20 text-center">
                        <span class="material-symbols-outlined text-5xl text-slate-300 mb-3 block">article</span>
                        <p class="text-slate-500">No articles published yet. Check back soon!</p>
                    </div>
                    @endif
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($posts->hasPages())
            <div class="mt-12 pt-8 border-t border-slate-200 flex justify-center">
                {{ $posts->links() }}
            </div>
            @endif
        </section>
    </div>
</main>
@endsection
