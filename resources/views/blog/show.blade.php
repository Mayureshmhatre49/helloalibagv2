@extends('layouts.app')

@push('title')
    <title>{{ $post->meta_title ?? $post->title . ' - Hello Alibaug Blog' }}</title>
@endpush

@push('meta')
    @php
        $description = $post->meta_description ?? $post->excerpt ?? Str::limit(strip_tags($post->content), 150);
        $imageUrl = $post->featured_image ? asset('storage/' . $post->featured_image) : route('blog.og-image', $post->id);
    @endphp
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ route('blog.show', $post->slug) }}">
    @if(!$post->is_indexable)
        <meta name="robots" content="noindex, nofollow">
    @endif

    <meta property="og:title" content="{{ $post->meta_title ?? $post->title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ route('blog.show', $post->slug) }}">
    <meta property="og:image" content="{{ $imageUrl }}">
    <meta property="article:published_time" content="{{ $post->published_at?->toIso8601String() }}">
    @if($post->category)
        <meta property="article:section" content="{{ $post->category->name }}">
    @endif

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Article",
      "headline": "{{ $post->title }}",
      "image": ["{{ $imageUrl }}"],
      "datePublished": "{{ $post->published_at ? $post->published_at->toIso8601String() : '' }}",
      "author": [{"@type": "Person", "name": "{{ $post->author->name }}"}],
      "publisher": {"@type": "Organization", "name": "Hello Alibaug", "logo": {"@type": "ImageObject", "url": "{{ asset('images/logo.png') }}"}}
    }
    </script>
@endpush

@push('styles')
<style>
    /* Premium article typography */
    .article-body h2 {
        font-size: 1.625rem; font-weight: 800; margin: 2.5rem 0 0.75rem; color: #1a1a2e;
        font-family: 'Manrope', sans-serif; line-height: 1.3; letter-spacing: -0.02em;
    }
    .article-body h3 {
        font-size: 1.25rem; font-weight: 700; margin: 2rem 0 0.5rem; color: #1a1a2e;
        font-family: 'Manrope', sans-serif; line-height: 1.35;
    }
    .article-body p {
        margin-bottom: 1.375rem; line-height: 1.9; color: #374151; font-size: 1.0625rem;
    }
    .article-body > p:first-child { font-size: 1.1875rem; color: #1f2937; line-height: 1.8; }
    .article-body a { color: #8B9A46; font-weight: 600; text-decoration: underline; text-underline-offset: 3px; transition: color 0.2s; }
    .article-body a:hover { color: #6d7a36; }
    .article-body img { border-radius: 0.75rem; margin: 1.75rem 0; width: 100%; height: auto; }
    .article-body ul, .article-body ol { padding-left: 1.5rem; margin-bottom: 1.375rem; color: #374151; font-size: 1.0625rem; line-height: 1.9; }
    .article-body ul { list-style-type: disc; }
    .article-body ol { list-style-type: decimal; }
    .article-body li { margin-bottom: 0.375rem; }
    .article-body strong { font-weight: 700; color: #1a1a2e; }
    .article-body blockquote {
        border-left: 3px solid #8B9A46; padding: 1.25rem 1.5rem; font-style: italic;
        color: #1f2937; margin: 2rem 0; background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 0 0.75rem 0.75rem 0; font-size: 1.0625rem; line-height: 1.7;
    }

    /* Sticky share sidebar animation */
    .share-sidebar { opacity: 0; transform: translateY(20px); transition: all 0.4s ease; }
    .share-sidebar.visible { opacity: 1; transform: translateY(0); }

    /* Dark Mode specific typography overrides */
    html.dark .article-body h2, html.dark .article-body h3, html.dark .article-body strong { color: #f8fafc; }
    html.dark .article-body p, html.dark .article-body ul, html.dark .article-body ol, html.dark .article-body > p:first-child { color: #cbd5e1; }
    html.dark .article-body blockquote { background: linear-gradient(135deg, #1e293b, #0f172a); color: #e2e8f0; border-left-color: #8B9A46; }

    /* Print Styles */
    @media print {
        @page { margin: 1in; }
        header, footer, .share-sidebar, #readingProgress, #reactionContainer, .newsletter-cta-block, .material-symbols-outlined, .border-b, section.bg-charcoal, section.bg-white:not(:has(#articleContent)), nav[aria-label="Breadcrumb"], button, a.bg-primary {
            display: none !important;
        }
        body, main { background: white !important; color: black !important; }
        #articleContent { max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
        .article-body p, .article-body ul, .article-body ol { font-family: Georgia, serif; font-size: 12pt; line-height: 1.6; color: black !important; }
        .article-body h2 { font-size: 18pt; margin-top: 24pt; color: black !important; }
        .article-body h3 { font-size: 14pt; margin-top: 18pt; color: black !important; }
        .article-body img { max-width: 100% !important; page-break-inside: avoid; }
        a { text-decoration: none !important; color: black !important; }
        a[href]:after { content: " (" attr(href) ")"; font-size: 90%; color: #666; }
    }
</style>
@endpush

@section('content')
{{-- Reading Progress Bar --}}
<div id="readingProgress" class="fixed top-[72px] sm:top-[88px] left-0 h-[3px] bg-gradient-to-r from-primary to-primary/70 z-50 transition-all duration-100 shadow-sm shadow-primary/20" style="width:0%"></div>

<main x-data="{ darkMode: localStorage.getItem('blogDarkMode') === 'true' }" 
      x-init="$watch('darkMode', val => localStorage.setItem('blogDarkMode', val))"
      :class="{ 'dark': darkMode }" 
      class="bg-white dark:bg-slate-900 transition-colors duration-500 min-h-screen">

    {{-- ===== HERO SECTION — Full-bleed image with overlay ===== --}}
    @if($post->featured_image)
    <section class="relative w-full h-[50vh] sm:h-[55vh] lg:h-[60vh] overflow-hidden">
        <img src="{{ asset('storage/' . $post->featured_image) }}"
             alt="{{ $post->featured_image_alt ?: $post->title }}"
             class="absolute inset-0 w-full h-full object-cover">
        {{-- Gradient overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/10"></div>

        {{-- Content on image --}}
        <div class="absolute inset-0 flex flex-col justify-end">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-10 sm:pb-14 w-full">
                {{-- Breadcrumb --}}
                <nav aria-label="Breadcrumb" class="mb-4">
                    <ol class="flex items-center gap-1.5 text-xs text-white/70 font-medium">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a></li>
                        <li><span class="material-symbols-outlined text-[13px]">chevron_right</span></li>
                        <li><a href="{{ route('blog.index') }}" class="hover:text-white transition-colors">Blog</a></li>
                        @if($post->category)
                        <li><span class="material-symbols-outlined text-[13px]">chevron_right</span></li>
                        <li><a href="{{ route('blog.category', $post->category->slug) }}" class="hover:text-white transition-colors">{{ $post->category->name }}</a></li>
                        @endif
                    </ol>
                </nav>

                @if($post->category)
                    <a href="{{ route('blog.category', $post->category->slug) }}"
                       class="inline-block bg-primary text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded mb-4 hover:bg-primary/90 transition-colors">
                        {{ $post->category->name }}
                    </a>
                @endif

                <h1 class="text-white text-3xl sm:text-4xl lg:text-5xl font-bold font-display leading-tight max-w-3xl">
                    {{ $post->title }}
                </h1>
            </div>
        </div>
    </section>
    @else
    {{-- Fallback: No image —  simple header --}}
    <section class="bg-charcoal pt-28 sm:pt-32 pb-10 sm:pb-14">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav aria-label="Breadcrumb" class="mb-4">
                <ol class="flex items-center gap-1.5 text-xs text-white/60 font-medium">
                    <li><a href="{{ route('home') }}" class="hover:text-white">Home</a></li>
                    <li><span class="material-symbols-outlined text-[13px]">chevron_right</span></li>
                    <li><a href="{{ route('blog.index') }}" class="hover:text-white">Blog</a></li>
                    @if($post->category)
                    <li><span class="material-symbols-outlined text-[13px]">chevron_right</span></li>
                    <li><a href="{{ route('blog.category', $post->category->slug) }}" class="hover:text-white">{{ $post->category->name }}</a></li>
                    @endif
                </ol>
            </nav>
            @if($post->category)
                <span class="inline-block bg-primary text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded mb-4">{{ $post->category->name }}</span>
            @endif
            <h1 class="text-white text-3xl sm:text-4xl lg:text-5xl font-bold font-display leading-tight">{{ $post->title }}</h1>
        </div>
    </section>
    @endif

    {{-- ===== AUTHOR BAR & TOGGLES ===== --}}
    <div class="border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 transition-colors duration-500">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-4">
                {{-- Left: Author info --}}
                <div class="flex items-center gap-3">
                    <img src="{{ $post->author->getAvatarUrl() }}" alt="{{ $post->author->name }}"
                         class="w-10 h-10 rounded-full ring-2 ring-slate-100">
                    <div>
                        <p class="text-sm font-bold text-charcoal dark:text-white leading-tight">{{ $post->author->name }}</p>
                        <div class="flex items-center gap-2 text-xs text-slate-400 dark:text-slate-500">
                            <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('M d, Y') }}</time>
                            <span>·</span>
                            <span>{{ $post->reading_time }} min read</span>
                        </div>
                    </div>
                </div>

                {{-- Right: Actions (Share + Dark Mode) --}}
                <div class="flex items-center gap-2 sm:gap-3">
                    {{-- Dark Mode Toggle --}}
                    <button @click="darkMode = !darkMode" 
                            class="w-9 h-9 rounded-full border border-slate-200 dark:border-slate-700 hover:border-slate-400 dark:hover:border-slate-500 text-slate-400 dark:text-slate-300 flex items-center justify-center transition-all bg-white dark:bg-slate-800 shadow-sm"
                            :title="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
                        <span class="material-symbols-outlined text-[18px]" x-show="!darkMode">dark_mode</span>
                        <span class="material-symbols-outlined text-[18px]" x-show="darkMode" style="display: none;">light_mode</span>
                    </button>

                    <div class="w-px h-6 bg-slate-200 dark:bg-slate-800 mx-1 hidden sm:block"></div>

                {{-- Right: Share buttons --}}
                <div class="flex items-center gap-2">
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}"
                       target="_blank" rel="noopener" title="Share on X"
                       class="w-9 h-9 rounded-full border border-slate-200 hover:border-slate-400 text-slate-400 hover:text-charcoal flex items-center justify-center transition-all">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.008 5.96H5.078z"/></svg>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                       target="_blank" rel="noopener" title="Share on Facebook"
                       class="w-9 h-9 rounded-full border border-slate-200 hover:border-slate-400 text-slate-400 hover:text-charcoal flex items-center justify-center transition-all">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/></svg>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . request()->url()) }}"
                       target="_blank" rel="noopener" title="Share on WhatsApp"
                       class="w-9 h-9 rounded-full border border-slate-200 hover:border-slate-400 text-slate-400 hover:text-charcoal flex items-center justify-center transition-all">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984a9.964 9.964 0 001.333 4.976L2 22l5.163-1.338a9.954 9.954 0 004.849 1.258h.004c5.505 0 9.988-4.477 9.989-9.984 0-2.669-1.037-5.176-2.92-7.062A9.935 9.935 0 0012.012 2zm5.826 14.122c-.244.686-1.4 1.341-1.928 1.405-.488.06-1.109.13-3.235-3.085-2.126-3.215-2.164-6.142-2.115-6.385.049-.244.305-1.098.976-1.708l.305-.244c.427-.366.976-.366 1.403-.366.427 0 .976 0 1.22.488.244.488.976 2.378 1.037 2.5.06.122.122.366 0 .61-.122.244-.244.427-.427.61l-.305.305c-.183.183-.366.427-.122.854.244.427 1.098 1.768 2.256 2.805 1.524 1.341 2.866 1.768 3.232 1.951.366.183.732.122.976-.06l.732-.854c.305-.366.671-.427 1.037-.244 1.707.854 2.5 1.22 2.622 1.402.122.183.183.61-.06 1.297z" clip-rule="evenodd"/></svg>
                    </a>
                    <button onclick="navigator.clipboard.writeText(window.location.href).then(() => { this.querySelector('span').textContent = 'done'; setTimeout(() => this.querySelector('span').textContent = 'link', 1500); })"
                            title="Copy link"
                            class="w-9 h-9 rounded-full border border-slate-200 hover:border-slate-400 text-slate-400 hover:text-charcoal flex items-center justify-center transition-all">
                        <span class="material-symbols-outlined text-[16px]">link</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ARTICLE CONTENT ===== --}}
    <div class="relative">
        {{-- Sticky Share Sidebar (desktop only) --}}
        <div id="shareSidebar" class="share-sidebar hidden xl:flex fixed left-[calc(50%-480px-80px)] top-1/2 -translate-y-1/2 flex-col gap-3 z-40">
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}"
               target="_blank" rel="noopener" title="Share on X"
               class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-primary text-slate-400 dark:text-slate-300 hover:text-primary flex items-center justify-center transition-all">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.008 5.96H5.078z"/></svg>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
               target="_blank" rel="noopener" title="Share on Facebook"
               class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-primary text-slate-400 dark:text-slate-300 hover:text-primary flex items-center justify-center transition-all">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/></svg>
            </a>
            <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . request()->url()) }}"
               target="_blank" rel="noopener" title="Share on WhatsApp"
               class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-primary text-slate-400 dark:text-slate-300 hover:text-primary flex items-center justify-center transition-all">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984a9.964 9.964 0 001.333 4.976L2 22l5.163-1.338a9.954 9.954 0 004.849 1.258h.004c5.505 0 9.988-4.477 9.989-9.984 0-2.669-1.037-5.176-2.92-7.062A9.935 9.935 0 0012.012 2zm5.826 14.122c-.244.686-1.4 1.341-1.928 1.405-.488.06-1.109.13-3.235-3.085-2.126-3.215-2.164-6.142-2.115-6.385.049-.244.305-1.098.976-1.708l.305-.244c.427-.366.976-.366 1.403-.366.427 0 .976 0 1.22.488.244.488.976 2.378 1.037 2.5.06.122.122.366 0 .61-.122.244-.244.427-.427.61l-.305.305c-.183.183-.366.427-.122.854.244.427 1.098 1.768 2.256 2.805 1.524 1.341 2.866 1.768 3.232 1.951.366.183.732.122.976-.06l.732-.854c.305-.366.671-.427 1.037-.244 1.707.854 2.5 1.22 2.622 1.402.122.183.183.61-.06 1.297z" clip-rule="evenodd"/></svg>
            </a>
            <div class="w-6 border-t border-slate-200 dark:border-slate-700 mx-auto"></div>
            <button onclick="navigator.clipboard.writeText(window.location.href).then(() => { this.querySelector('span').textContent = 'done'; setTimeout(() => this.querySelector('span').textContent = 'link', 1500); })"
                    title="Copy link"
                    class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-primary text-slate-400 dark:text-slate-300 hover:text-primary flex items-center justify-center transition-all">
                <span class="material-symbols-outlined text-[18px]">link</span>
            </button>
        </div>

        {{-- Article body --}}
        <article id="articleContent" class="max-w-[720px] mx-auto px-4 sm:px-6 lg:px-8 pt-10 sm:pt-14 pb-10">
            @if(isset($toc) && count($toc) > 0)
                <div class="mb-10 p-6 sm:p-8 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl shadow-sm">
                    <h3 class="font-display font-bold text-lg text-charcoal dark:text-white mb-4 flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-3">
                        <span class="material-symbols-outlined text-[20px] text-primary">format_list_bulleted</span>
                        Table of Contents
                    </h3>
                    <ul class="space-y-2.5 text-sm">
                        @foreach($toc as $item)
                            <li class="{{ $item['level'] == 3 ? 'ml-5 pl-3 border-l-2 border-slate-200 dark:border-slate-700' : 'font-medium' }}">
                                <a href="#{{ $item['id'] }}" class="text-slate-600 dark:text-slate-300 hover:text-primary transition-colors hover:underline underline-offset-4 decoration-primary/30">
                                    {{ $item['text'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="article-body">
                {!! str_replace(
                    ['Kihim', 'Nagaon', 'Awas'],
                    [
                        '<a href="/search?location=kihim">Kihim</a>',
                        '<a href="/search?location=nagaon">Nagaon</a>',
                        '<a href="/search?location=awas">Awas</a>'
                    ],
                    $post->content
                ) !!}
            </div>

            {{-- Was this helpful? --}}
            <div class="mt-12 mb-8 py-8 border-t border-slate-200 dark:border-slate-800 text-center" id="reactionContainer">
                <h4 class="font-display font-bold text-lg text-charcoal dark:text-white mb-4">Was this article helpful?</h4>
                <div class="flex items-center justify-center gap-4">
                    <button onclick="submitReaction('upvote', '{{ route('blog.vote', $post->id) }}')" id="btn-upvote"
                            class="flex items-center gap-2 px-6 py-2.5 rounded-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:border-primary hover:text-primary transition-all text-sm font-semibold shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">thumb_up</span>
                        Yes <span id="upvotes-count" class="text-xs opacity-70">{{ $post->upvotes > 0 ? "({$post->upvotes})" : '' }}</span>
                    </button>
                    <button onclick="submitReaction('downvote', '{{ route('blog.vote', $post->id) }}')" id="btn-downvote"
                            class="flex items-center gap-2 px-6 py-2.5 rounded-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:border-charcoal dark:hover:border-white hover:text-charcoal dark:hover:text-white transition-all text-sm font-semibold shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">thumb_down</span>
                        No
                    </button>
                </div>
                <p id="reaction-thanks" class="text-sm text-green-600 dark:text-green-400 font-medium mt-4 hidden">Thank you for your feedback!</p>
            </div>
            
            {{-- Newsletter CTA --}}
            @include('components.newsletter-cta')
        </article>
    </div>

    {{-- ===== BOTTOM BAR: Tags + Share ===== --}}
    <div class="max-w-[720px] mx-auto px-4 sm:px-6 lg:px-8 pb-10">
        <div class="border-t border-slate-200 dark:border-slate-800 pt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            @if($post->tags->count() > 0)
            <div class="flex flex-wrap items-center gap-2">
                @foreach($post->tags as $tag)
                    <a href="{{ route('blog.tag', $tag->slug) }}"
                       class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-full text-xs font-semibold hover:bg-primary hover:text-white transition-colors border border-transparent dark:border-slate-700 hover:border-primary">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
            @else
            <div></div>
            @endif

            <div class="flex items-center gap-2 text-xs text-slate-400 dark:text-slate-500">
                <span class="material-symbols-outlined text-[14px]">visibility</span>
                <span>{{ $post->reading_time }} min read</span>
                <span>·</span>
                <time>{{ $post->published_at->format('M d, Y') }}</time>
            </div>
        </div>
    </div>

    {{-- ===== AUTHOR CARD ===== --}}
    <div class="max-w-[720px] mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="bg-slate-50 dark:bg-slate-800 border border-transparent dark:border-slate-700/50 rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row gap-5 items-start sm:items-center shadow-sm">
            <img src="{{ $post->author->getAvatarUrl() }}" alt="{{ $post->author->name }}"
                 class="w-16 h-16 rounded-full ring-4 ring-white dark:ring-slate-700 shadow-sm shrink-0">
            <div>
                <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mb-1">Written by</p>
                <p class="text-lg font-bold text-charcoal dark:text-white">{{ $post->author->name }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">Sharing curated guides and insider tips about luxury stays, dining, and experiences in Alibaug.</p>
            </div>
        </div>
    </div>

    {{-- ===== RELATED LISTINGS ===== --}}
    @if($post->relatedListings->count() > 0)
    <section class="max-w-[720px] mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="border-t border-slate-200 dark:border-slate-800 pt-8">
            <h3 class="text-lg font-bold font-display text-charcoal dark:text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[20px]">location_on</span>
                Places Mentioned
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($post->relatedListings as $listing)
                <a href="{{ route('listing.show', [$listing->category->slug, $listing->slug]) }}"
                   class="group flex gap-4 p-3 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-primary/50 dark:hover:border-primary/50 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all bg-white dark:bg-slate-900 shadow-sm">
                    <div class="w-16 h-16 rounded-lg overflow-hidden shrink-0 bg-slate-100 dark:bg-slate-800">
                        @if($url = $listing->getPrimaryImageUrl())
                            <img src="{{ asset('storage/' . $url) }}" alt="{{ $listing->title }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex flex-col justify-center min-w-0">
                        <h4 class="font-semibold text-charcoal dark:text-white text-sm leading-snug group-hover:text-primary transition-colors truncate">{{ $listing->title }}</h4>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $listing->location->name ?? 'Alibaug' }}</p>
                        <span class="text-primary font-bold text-sm mt-1">₹{{ number_format($listing->price) }}<span class="text-slate-400 dark:text-slate-500 font-normal text-xs"> / night</span></span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

</main>

{{-- ===== RELATED ARTICLES ===== --}}
@if($relatedPosts->count() > 0)
<section class="bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 transition-colors duration-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 sm:py-20">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-2xl sm:text-3xl font-bold font-display text-charcoal dark:text-white">
                You might also like
            </h2>
            <a href="{{ route('blog.index') }}" class="hidden sm:flex items-center gap-1 text-sm font-semibold text-primary hover:underline group">
                All articles <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @foreach($relatedPosts as $rel)
            <a href="{{ route('blog.show', $rel->slug) }}" class="group">
                <div class="aspect-[16/10] rounded-xl overflow-hidden mb-4 border border-slate-100 dark:border-slate-800">
                    @if($rel->featured_image)
                        <img src="{{ asset('storage/' . $rel->featured_image) }}" alt="{{ $rel->title }}"
                             class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center">
                            <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600">image</span>
                        </div>
                    @endif
                </div>
                <div class="flex items-center gap-2 text-xs text-slate-400 dark:text-slate-500 font-medium mb-2">
                    @if($rel->category)
                        <span class="text-primary font-bold uppercase tracking-wider">{{ $rel->category->name }}</span>
                        <span>·</span>
                    @endif
                    <time>{{ $rel->published_at->format('M d, Y') }}</time>
                </div>
                <h3 class="text-lg font-bold font-display text-charcoal dark:text-white leading-snug group-hover:text-primary dark:group-hover:text-primary transition-colors line-clamp-2">{{ $rel->title }}</h3>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@push('scripts')
<script>
    // Reading progress bar
    window.addEventListener('scroll', () => {
        const s = document.documentElement.scrollTop || document.body.scrollTop;
        const h = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        document.getElementById('readingProgress').style.width = Math.min(s / h * 100, 100) + '%';
    });

    // Show/hide sticky share sidebar based on article visibility
    const sidebar = document.getElementById('shareSidebar');
    const article = document.getElementById('articleContent');
    if (sidebar && article) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                sidebar.classList.toggle('visible', entry.isIntersecting);
            });
        }, { threshold: 0.1 });
        observer.observe(article);
    }

    // Reaction submission
    function submitReaction(type, url) {
        const upBtn = document.getElementById('btn-upvote');
        const downBtn = document.getElementById('btn-downvote');
        
        upBtn.disabled = true; downBtn.disabled = true;
        upBtn.classList.add('opacity-50'); downBtn.classList.add('opacity-50');
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ type: type })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('reaction-thanks').classList.remove('hidden');
                if (data.upvotes > 0) {
                    document.getElementById('upvotes-count').textContent = `(${data.upvotes})`;
                }
                if (type === 'upvote') {
                    upBtn.classList.add('bg-primary/10', 'border-primary', 'text-primary');
                    upBtn.classList.remove('opacity-50');
                } else {
                    downBtn.classList.add('bg-charcoal/10', 'border-charcoal', 'text-charcoal');
                    downBtn.classList.remove('opacity-50');
                }
            } else if(data.message) {
                alert(data.message);
                upBtn.classList.remove('opacity-50'); downBtn.classList.remove('opacity-50');
                upBtn.disabled = false; downBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            upBtn.classList.remove('opacity-50'); downBtn.classList.remove('opacity-50');
            upBtn.disabled = false; downBtn.disabled = false;
        });
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.4.1/index.min.js"></script>
@endpush
