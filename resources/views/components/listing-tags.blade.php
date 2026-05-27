{{--
  "Best For" smart-tag chips for a listing.
  Props:
    $listing  — the Listing model
    $limit    — max chips to show (default 4)
    $linkable — render chips as links to the tag landing page (default true).
                Set false inside listing cards, where an outer <a> overlay
                already wraps the whole card (nested anchors are invalid).
--}}
@props(['listing', 'limit' => 4, 'linkable' => true])

@php
    // Avoid an extra query when the relation is already eager-loaded.
    $tags = $listing->relationLoaded('tags')
        ? $listing->tags
        : $listing->tags()->orderBy('sort_order')->get();
    $tags = $tags->take($limit);
@endphp

@if($tags->isNotEmpty())
    <div {{ $attributes->merge(['class' => 'flex flex-wrap gap-1.5']) }}>
        @foreach($tags as $tag)
            @if($linkable)
                <a href="{{ route('tag.show', $tag) }}"
                   class="inline-flex items-center gap-1 bg-primary/10 text-primary hover:bg-primary hover:text-white px-2.5 py-1 rounded-full text-[11px] font-semibold transition-colors">
                    @if($tag->icon)<span class="material-symbols-outlined text-[12px]">{{ $tag->icon }}</span>@endif
                    {{ $tag->name }}
                </a>
            @else
                <span class="inline-flex items-center gap-1 bg-primary/10 text-primary px-2 py-0.5 rounded-full text-[10px] font-semibold">
                    @if($tag->icon)<span class="material-symbols-outlined text-[11px]">{{ $tag->icon }}</span>@endif
                    {{ $tag->name }}
                </span>
            @endif
        @endforeach
    </div>
@endif
