<nav class="flex items-center gap-1.5 text-sm text-slate-400 mb-5">
    <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
    <span class="material-symbols-outlined text-[13px]">chevron_right</span>
    <a href="{{ route('category.show', $listing->category) }}" class="hover:text-primary transition-colors">{{ $listing->category->name }}</a>
    <span class="material-symbols-outlined text-[13px]">chevron_right</span>
    <span class="text-slate-600 truncate max-w-[200px]">{{ $listing->title }}</span>
</nav>
