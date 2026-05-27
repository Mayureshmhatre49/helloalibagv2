@extends('layouts.admin')
@section('page-title', $guide ? 'Edit Guide' : 'New Guide')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <style>
        trix-editor { min-height: 420px; background: #fff; }
        .trix-button-group--file-tools { display: none !important; }
    </style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto pb-12">
    <a href="{{ route('admin.guides.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-primary mb-6">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Guides
    </a>

    <form method="POST"
          action="{{ $guide ? route('admin.guides.update', $guide) : route('admin.guides.store') }}"
          enctype="multipart/form-data"
          class="space-y-6"
          x-data="guideForm({
              initialIds: @js(array_keys($attached)),
              listings: @js($listings->mapWithKeys(fn ($l) => [$l->id => $l->title])->all()),
              initialBlurbs: @js(collect($attached)->mapWithKeys(fn ($v, $k) => [$k => $v['blurb']])->all()),
          })">
        @csrf
        @if ($guide) @method('PUT') @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- ── MAIN ── --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Title + slug --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Title *</label>
                        <input type="text" id="x_title" name="title" required
                               value="{{ old('title', $guide->title ?? '') }}"
                               class="w-full border border-slate-200 rounded-xl px-4 py-3 text-lg font-semibold focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">URL Slug *</label>
                        <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl overflow-hidden focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/20">
                            <span class="px-3 py-3 text-xs text-text-secondary font-mono border-r border-slate-200">/guides/</span>
                            <input type="text" id="x_slug" name="slug" required
                                   value="{{ old('slug', $guide->slug ?? '') }}"
                                   class="flex-1 px-3 py-3 text-sm font-mono text-slate-700 bg-slate-50 border-0 focus:ring-0 outline-none">
                        </div>
                        @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Intro / TL;DR</label>
                        <p class="text-xs text-text-secondary mb-2">A 1–2 sentence summary shown under the title and in cards (max 500 chars).</p>
                        <textarea name="intro" rows="3" maxlength="500"
                                  class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none">{{ old('intro', $guide->intro ?? '') }}</textarea>
                        @error('intro') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Content --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Body *</label>
                    <p class="text-xs text-text-secondary mb-3">Use H2 headings — they automatically build the table of contents on the published guide.</p>
                    <input id="x_content" type="hidden" name="content" value="{{ old('content', $guide->content ?? '') }}">
                    <trix-editor input="x_content" class="trix-content w-full border border-slate-200 rounded-xl prose max-w-none text-sm"></trix-editor>
                    @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Listing picker --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-slate-900 flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px] text-primary">collections_bookmark</span>
                                Curated Listings
                            </h3>
                            <p class="text-xs text-text-secondary mt-1">Pick the listings featured in this guide. They appear in order with optional editorial blurbs.</p>
                        </div>
                        <span class="text-xs font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-full" x-text="`${selected.length} selected`"></span>
                    </div>

                    <div class="p-6 space-y-5">
                        {{-- Search + add --}}
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-text-secondary mb-2">Add a listing</label>
                            <div class="flex gap-2">
                                <div class="relative flex-1">
                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
                                    <input type="text" x-model="search" placeholder="Type to filter…"
                                           class="w-full pl-9 pr-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                </div>
                                <select x-model="addCandidate"
                                        class="flex-1 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                    <option value="">Choose listing…</option>
                                    <template x-for="(title, id) in filteredOptions" :key="id">
                                        <option :value="id" x-text="title"></option>
                                    </template>
                                </select>
                                <button type="button" @click="addListing()"
                                        :disabled="!addCandidate"
                                        :class="addCandidate ? 'bg-primary text-white hover:bg-primary-dark' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                                        class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-bold transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">add</span> Add
                                </button>
                            </div>
                        </div>

                        {{-- Selected list (draggable order via up/down) --}}
                        <div class="space-y-2">
                            <template x-for="(item, idx) in selected" :key="item.id">
                                <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 flex items-start gap-3">
                                    <div class="flex flex-col gap-1 mt-1">
                                        <button type="button" @click="moveUp(idx)" :disabled="idx === 0"
                                                :class="idx === 0 ? 'text-slate-300 cursor-not-allowed' : 'text-slate-500 hover:text-primary'"
                                                title="Move up">
                                            <span class="material-symbols-outlined text-[16px]">expand_less</span>
                                        </button>
                                        <span class="text-xs font-bold text-slate-700 text-center tabular-nums" x-text="idx + 1"></span>
                                        <button type="button" @click="moveDown(idx)" :disabled="idx === selected.length - 1"
                                                :class="idx === selected.length - 1 ? 'text-slate-300 cursor-not-allowed' : 'text-slate-500 hover:text-primary'"
                                                title="Move down">
                                            <span class="material-symbols-outlined text-[16px]">expand_more</span>
                                        </button>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-slate-900 truncate" x-text="item.title"></p>
                                        <textarea x-model="item.blurb"
                                                  placeholder="Optional editorial blurb shown on this listing in the guide…"
                                                  rows="2" maxlength="500"
                                                  class="mt-2 w-full text-xs border border-slate-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none bg-white"></textarea>
                                        {{-- Hidden inputs for posting --}}
                                        <input type="hidden" name="listing_ids[]" :value="item.id">
                                        <input type="hidden" :name="`listing_positions[${item.id}]`" :value="idx">
                                        <input type="hidden" :name="`listing_blurbs[${item.id}]`" :value="item.blurb">
                                    </div>
                                    <button type="button" @click="remove(idx)"
                                            class="text-text-secondary hover:text-red-600 transition-colors p-1"
                                            title="Remove">
                                        <span class="material-symbols-outlined text-[18px]">close</span>
                                    </button>
                                </div>
                            </template>
                            <template x-if="selected.length === 0">
                                <div class="text-center text-text-secondary text-sm py-6 border-2 border-dashed border-slate-200 rounded-xl">
                                    No listings added yet.
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- SEO --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <h3 class="font-bold text-slate-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px] text-blue-500">travel_explore</span>
                        SEO
                    </h3>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-text-secondary mb-1">Focus Keyword</label>
                        <input type="text" name="focus_keyword" value="{{ old('focus_keyword', $guide->focus_keyword ?? '') }}"
                               placeholder="e.g. best beaches in alibaug"
                               class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-text-secondary mb-1">Meta Title</label>
                        <input type="text" name="meta_title" maxlength="255" value="{{ old('meta_title', $guide->meta_title ?? '') }}"
                               class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                        <p class="text-xs text-text-secondary mt-1">Defaults to the guide title if blank.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-text-secondary mb-1">Meta Description</label>
                        <textarea name="meta_description" rows="3" maxlength="500"
                                  class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none">{{ old('meta_description', $guide->meta_description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ── SIDEBAR ── --}}
            <div class="space-y-6">
                {{-- Status --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <h3 class="font-bold text-slate-900">Publish</h3>
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $guide->is_published ?? false) ? 'checked' : '' }}
                               class="mt-1 rounded text-primary focus:ring-primary">
                        <span>
                            <span class="text-sm font-bold text-slate-900 block">Publish this guide</span>
                            <span class="text-xs text-text-secondary block mt-0.5">Visible at <code class="bg-slate-100 px-1 py-0.5 rounded text-[10px]">/guides/{slug}</code></span>
                        </span>
                    </label>
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $guide->is_featured ?? false) ? 'checked' : '' }}
                               class="mt-1 rounded text-primary focus:ring-primary">
                        <span>
                            <span class="text-sm font-bold text-slate-900 block">Mark as featured</span>
                            <span class="text-xs text-text-secondary block mt-0.5">Appears in the top-of-hub editor's picks.</span>
                        </span>
                    </label>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-text-secondary mb-1">Publish date (optional)</label>
                        <input type="date" name="published_at"
                               value="{{ old('published_at', optional($guide?->published_at)->format('Y-m-d') ?? '') }}"
                               class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                        <p class="text-xs text-text-secondary mt-1">Leave blank to publish immediately.</p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex flex-col gap-2">
                        <button type="submit" class="w-full bg-primary text-white py-3 rounded-xl text-sm font-bold hover:bg-primary-dark transition-colors inline-flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            {{ $guide ? 'Update Guide' : 'Create Guide' }}
                        </button>
                        @if ($guide)
                            <a href="{{ route('guides.show', $guide) }}" target="_blank"
                               class="w-full text-center text-sm font-bold text-text-secondary hover:text-primary transition-colors py-2 inline-flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">open_in_new</span> Preview
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Hero image --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <h3 class="font-bold text-slate-900 mb-3">Hero Image</h3>
                    @if ($guide && $guide->heroImageUrl())
                        <img src="{{ $guide->heroImageUrl() }}" alt="Current hero"
                             class="w-full aspect-[16/9] object-cover rounded-xl mb-3">
                    @endif
                    <input type="file" name="hero_image" accept="image/jpeg,image/png,image/webp"
                           class="w-full text-xs file:bg-primary file:text-white file:font-bold file:text-xs file:border-0 file:rounded-lg file:px-3 file:py-2 file:mr-3 file:cursor-pointer">
                    <p class="text-xs text-text-secondary mt-2">Recommended 1600×900 px. JPG, PNG, or WebP. Auto-cropped and converted to WebP.</p>
                    @error('hero_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <div class="mt-3">
                        <label class="block text-xs font-bold uppercase tracking-wider text-text-secondary mb-1">Alt text</label>
                        <input type="text" name="hero_image_alt" value="{{ old('hero_image_alt', $guide->hero_image_alt ?? '') }}"
                               class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
<script>
    // Auto-fill slug from title (only when slug is empty/untouched)
    (function () {
        const title = document.getElementById('x_title');
        const slug = document.getElementById('x_slug');
        let slugTouched = !!slug.value;
        slug.addEventListener('input', () => { slugTouched = true; });
        title.addEventListener('input', () => {
            if (!slugTouched) {
                slug.value = title.value
                    .toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .trim()
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }
        });
    })();

    document.addEventListener('alpine:init', () => {
        Alpine.data('guideForm', (config) => ({
            listings: config.listings,             // { id: title }
            selected: [],                          // [{ id, title, blurb }]
            search: '',
            addCandidate: '',

            init() {
                const ids = config.initialIds || [];
                const blurbs = config.initialBlurbs || {};
                this.selected = ids.map(id => ({
                    id: String(id),
                    title: this.listings[id] || `Listing #${id}`,
                    blurb: blurbs[id] || '',
                }));
            },

            get filteredOptions() {
                const q = this.search.toLowerCase().trim();
                const selectedIds = new Set(this.selected.map(s => String(s.id)));
                const out = {};
                for (const [id, title] of Object.entries(this.listings)) {
                    if (selectedIds.has(String(id))) continue;
                    if (q && !title.toLowerCase().includes(q)) continue;
                    out[id] = title;
                }
                return out;
            },

            addListing() {
                if (!this.addCandidate) return;
                const id = String(this.addCandidate);
                if (this.selected.find(s => String(s.id) === id)) return;
                this.selected.push({ id, title: this.listings[id] || `Listing #${id}`, blurb: '' });
                this.addCandidate = '';
                this.search = '';
            },

            remove(idx) {
                this.selected.splice(idx, 1);
            },

            moveUp(idx) {
                if (idx === 0) return;
                [this.selected[idx - 1], this.selected[idx]] = [this.selected[idx], this.selected[idx - 1]];
            },

            moveDown(idx) {
                if (idx === this.selected.length - 1) return;
                [this.selected[idx + 1], this.selected[idx]] = [this.selected[idx], this.selected[idx + 1]];
            },
        }));
    });
</script>
@endpush
