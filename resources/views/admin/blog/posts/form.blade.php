@extends('layouts.admin')
@section('page-title', $post ? 'Edit Blog Post' : 'New Blog Post')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <style>
        trix-editor { min-height: 400px; background: #fff; }
        .trix-button-group--file-tools { display: none !important; }
    </style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto pb-12">
    <a href="{{ route('admin.blog.posts.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-primary mb-6">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Posts
    </a>

    <form method="POST" action="{{ $post ? route('admin.blog.posts.update', $post) : route('admin.blog.posts.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if($post) @method('PUT') @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title & Slug -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Title *</label>
                            <input type="text" id="x_title" name="title" value="{{ old('title', $post->title ?? '') }}" required class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-lg font-semibold placeholder:font-normal">
                            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">URL Slug *</label>
                            <input type="text" id="x_slug" name="slug" value="{{ old('slug', $post->slug ?? '') }}" required class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none font-mono text-slate-600 bg-slate-50">
                            @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Rich Text Content -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Content *</label>
                    <input id="x_content" type="hidden" name="content" value="{{ old('content', $post->content ?? '') }}">
                    <trix-editor input="x_content" class="trix-content w-full border border-slate-200 rounded-xl prose max-w-none text-sm"></trix-editor>
                    @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Excerpt -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <label class="block text-sm font-bold text-slate-700 mb-1">Excerpt</label>
                    <p class="text-xs text-slate-500 mb-2">A short summary displayed on blog index pages and lists.</p>
                    <textarea id="x_excerpt" name="excerpt" rows="3" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
                    @error('excerpt') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- SEO Analysis (RankMath Style) -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="border-b border-slate-100 bg-slate-50/50 p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <h3 class="font-bold text-slate-900 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px] text-blue-500">travel_explore</span> 
                            SEO Settings & Analysis
                        </h3>
                        
                        <!-- Modern Circular Score -->
                        <div class="flex items-center gap-4 bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm">
                            <div class="flex flex-col text-right">
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Overall Score</span>
                                <span class="text-sm font-bold text-slate-700" id="seo-score-text">Needs Improvement</span>
                            </div>
                            <div class="relative w-12 h-12 flex items-center justify-center">
                                <svg class="w-full h-full -rotate-90 transform" viewBox="0 0 36 36">
                                    <path class="text-slate-100" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    <path id="seo-score-circle" class="text-red-500 transition-all duration-500 ease-out" stroke-dasharray="0, 100" stroke-width="3" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center font-bold text-[13px] text-slate-700" id="seo-score-number">
                                    {{ old('seo_score', $post->seo_score ?? 0) }}
                                </div>
                            </div>
                            <input type="hidden" name="seo_score" id="seo_score_input" value="{{ old('seo_score', $post->seo_score ?? 0) }}">
                        </div>
                    </div>
                    
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <label class="block text-sm font-bold text-slate-700 mb-1">Focus Keyword</label>
                                <input type="text" id="focus_keyword" name="focus_keyword" value="{{ old('focus_keyword', $post->focus_keyword ?? '') }}" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white" placeholder="e.g. Alibaug Villas">
                                <p class="text-[10px] text-slate-500 mt-2">Enter the main keyword you want to rank for.</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">SEO Meta Title</label>
                                <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $post->meta_title ?? '') }}" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="Leave blank to use post title">
                                <!-- Progress bar for title length -->
                                <div class="mt-2 h-1 w-full bg-slate-100 rounded-full overflow-hidden">
                                    <div id="title_progress" class="h-full bg-blue-500 transition-all w-0"></div>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <span class="text-[10px] text-slate-400">Aim for 40-60 chars</span>
                                    <span id="title_length" class="text-[10px] text-slate-500 font-medium">0 / 60</span>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">SEO Meta Description</label>
                                <textarea id="meta_description" name="meta_description" rows="3" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none" placeholder="Leave blank to use excerpt">{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
                                <!-- Progress bar for desc length -->
                                <div class="mt-2 h-1 w-full bg-slate-100 rounded-full overflow-hidden">
                                    <div id="desc_progress" class="h-full bg-blue-500 transition-all w-0"></div>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <span class="text-[10px] text-slate-400">Aim for 120-160 chars</span>
                                    <span id="desc_length" class="text-[10px] text-slate-500 font-medium">0 / 160</span>
                                </div>
                            </div>
                            
                            <div class="pt-2">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="is_indexable" value="1" {{ old('is_indexable', $post->is_indexable ?? true) ? 'checked' : '' }} class="rounded border-slate-300 text-primary focus:ring-primary">
                                    <span class="text-sm font-bold text-slate-700">Indexable (Allow Search Engines)</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <!-- SERP Preview -->
                            <div>
                                <h4 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2"><span class="material-symbols-outlined text-[18px] text-slate-400">devices</span> Google Snippet Preview</h4>
                                <div class="p-4 bg-white border border-slate-200 rounded-xl shadow-sm">
                                    <div class="text-[13px] text-slate-700 mb-1 flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-400">HA</div>
                                        <span class="text-slate-800">Hello Alibaug</span>
                                        <span class="text-slate-400">{{ url('/blog') }}/<span id="preview_slug">post-url</span></span>
                                    </div>
                                    <div id="preview_title" class="text-lg text-blue-700 font-medium hover:underline cursor-pointer mb-1 truncate">Post Title Goes Here</div>
                                    <div id="preview_desc" class="text-[13px] text-slate-600 line-clamp-2">This is where the meta description will appear in Google search results. It should be engaging and informative.</div>
                                </div>
                            </div>
                            
                            <!-- Analysis Tabs/Checklists -->
                            <div class="bg-slate-50 rounded-xl border border-slate-100 p-1">
                                <!-- Tabs -->
                                <div class="flex text-[13px] font-bold p-1 bg-slate-200/50 rounded-lg mb-3">
                                    <button type="button" class="flex-1 py-1.5 px-3 rounded-md bg-white shadow-sm text-slate-800" onclick="document.getElementById('seo-basic').classList.remove('hidden'); document.getElementById('seo-readability').classList.add('hidden'); this.classList.add('bg-white','shadow-sm','text-slate-800'); this.classList.remove('text-slate-500'); this.nextElementSibling.classList.remove('bg-white','shadow-sm','text-slate-800'); this.nextElementSibling.classList.add('text-slate-500');">Basic SEO</button>
                                    <button type="button" class="flex-1 py-1.5 px-3 rounded-md text-slate-500 hover:text-slate-700" onclick="document.getElementById('seo-readability').classList.remove('hidden'); document.getElementById('seo-basic').classList.add('hidden'); this.classList.add('bg-white','shadow-sm','text-slate-800'); this.classList.remove('text-slate-500'); this.previousElementSibling.classList.remove('bg-white','shadow-sm','text-slate-800'); this.previousElementSibling.classList.add('text-slate-500');">Readability</button>
                                </div>
                                
                                <div class="p-3 bg-white rounded-lg border border-slate-100 min-h-[220px]">
                                    <ul id="seo-basic" class="space-y-3 text-[13px]">
                                        <!-- Populated by JS -->
                                        <li class="flex items-center gap-2 text-slate-400"><span class="material-symbols-outlined text-[16px]">hourglass_empty</span> Enter a focus keyword to begin analysis...</li>
                                    </ul>
                                    <ul id="seo-readability" class="space-y-3 text-[13px] hidden">
                                        <!-- Populated by JS -->
                                        <li class="flex items-center gap-2 text-slate-400"><span class="material-symbols-outlined text-[16px]">hourglass_empty</span> Add content to analyze readability...</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info Sidebar -->
            <div class="space-y-6">
                <!-- Publishing & Status -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <h3 class="font-bold text-slate-900 border-b border-slate-100 pb-2 mb-4">Publishing</h3>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Status</label>
                        <select name="status" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none appearance-none bg-slate-50">
                            <option value="draft" {{ old('status', $post->status ?? '') === 'draft' ? 'selected' : '' }}>Draft - Hidden</option>
                            <option value="published" {{ old('status', $post->status ?? '') === 'published' ? 'selected' : '' }}>Published - Live</option>
                        </select>
                    </div>

                    <div class="pt-2">
                        <label class="flex items-start gap-3 cursor-pointer p-3 border border-slate-100 rounded-xl hover:bg-slate-50 transition-colors">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $post->is_featured ?? false) ? 'checked' : '' }} class="mt-0.5 rounded border-slate-300 text-primary w-4 h-4 focus:ring-primary">
                            <div>
                                <span class="block text-sm font-bold text-slate-900">Featured Post</span>
                                <span class="block text-xs text-slate-500 mt-0.5">Show this post in spotlight sections</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Meta & Taxonomy -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <h3 class="font-bold text-slate-900 border-b border-slate-100 pb-2 mb-4">Organization</h3>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Category</label>
                        <select name="category_id" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                            <option value="">-- No Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $post->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Tags</label>
                        <select name="tags[]" multiple class="w-full border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none min-h-[120px]">
                            @php $selectedTags = old('tags', $post ? $post->tags->pluck('id')->toArray() : []); @endphp
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" {{ in_array($tag->id, $selectedTags) ? 'selected' : '' }}>#{{ $tag->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-slate-400 mt-1">Hold Ctrl/Cmd to select multiple tags.</p>
                    </div>
                </div>

                <!-- Media -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <h3 class="font-bold text-slate-900 border-b border-slate-100 pb-2 mb-4">Media</h3>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Featured Image</label>
                        @if($post && $post->featured_image)
                            <div class="mb-3 relative rounded-xl overflow-hidden group">
                                <img src="{{ asset('storage/' . $post->featured_image) }}" class="w-full h-auto aspect-video object-cover" alt="Current Image">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                    <span class="text-white text-xs font-bold bg-black/50 px-3 py-1.5 rounded-full">Current Image</span>
                                </div>
                            </div>
                        @endif
                        <input type="file" name="featured_image" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all cursor-pointer">
                        <p class="text-[10px] text-slate-400 mt-2">Recommended: 1200x630px. Will be auto-compressed to WebP.</p>
                        @error('featured_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-2 border-t border-slate-100">
                        <label class="block text-sm font-bold text-slate-700 mb-1">Image Alt Text</label>
                        <input type="text" name="featured_image_alt" value="{{ old('featured_image_alt', $post->featured_image_alt ?? '') }}" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="Describe the image for SEO">
                        <p class="text-[10px] text-slate-400 mt-1">Leave blank to default to the post title. Important for accessibility and SEO formatting.</p>
                        @error('featured_image_alt') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Removed SEO block from sidebar as it is now in main column -->
                
                <!-- Related Listings -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <h3 class="font-bold text-slate-900 border-b border-slate-100 pb-2 mb-4">Related Listings</h3>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Attach Listings</label>
                        <select name="related_listings[]" multiple class="w-full border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none h-40">
                            @php $selectedListings = old('related_listings', $post ? $post->relatedListings->pluck('id')->toArray() : []); @endphp
                            @foreach($listings as $listing)
                                <option value="{{ $listing->id }}" {{ in_array($listing->id, $selectedListings) ? 'selected' : '' }}>{{ $listing->title }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-slate-400 mt-1">Cross-sell properties directly in the blog post body/sidebar.</p>
                    </div>
                </div>

            </div>
        </div>
        
        <div class="flex items-center gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm sticky bottom-6 z-10">
            <button type="submit" class="bg-slate-900 text-white px-8 py-3.5 rounded-xl font-bold hover:bg-black transition-colors shadow-lg shadow-black/10">
                {{ $post ? 'Update Post' : 'Publish Post' }}
            </button>
            <a href="{{ route('admin.blog.posts.index') }}" class="text-sm font-bold text-slate-500 hover:text-slate-700">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script>
        const titleInput = document.getElementById('x_title');
        const slugInput = document.getElementById('x_slug');
        const focusKeywordInput = document.getElementById('focus_keyword');
        const metaTitleInput = document.getElementById('meta_title');
        const metaDescInput = document.getElementById('meta_description');
        const contentInput = document.getElementById('x_content');
        const excerptInput = document.getElementById('x_excerpt');
        
        const previewTitle = document.getElementById('preview_title');
        const previewSlug = document.getElementById('preview_slug');
        const previewDesc = document.getElementById('preview_desc');
        const titleLength = document.getElementById('title_length');
        const descLength = document.getElementById('desc_length');
        const titleProgress = document.getElementById('title_progress');
        const descProgress = document.getElementById('desc_progress');
        
        const seoBasicList = document.getElementById('seo-basic');
        const seoReadabilityList = document.getElementById('seo-readability');
        const seoScoreNumber = document.getElementById('seo-score-number');
        const seoScoreText = document.getElementById('seo-score-text');
        const seoScoreCircle = document.getElementById('seo-score-circle');
        const seoScoreInput = document.getElementById('seo_score_input');

        // Auto-generate slug from title
        titleInput.addEventListener('input', function(e) {
            if (!'{{ $post ? 1 : 0 }}') {
                let slug = e.target.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
                slugInput.value = slug;
                updatePreview();
            }
        });

        function updateLengths() {
            let tLen = metaTitleInput.value.length;
            let dLen = metaDescInput.value.length;
            
            titleLength.textContent = `${tLen} / 60`;
            descLength.textContent = `${dLen} / 160`;

            // Title progress
            let tPct = Math.min((tLen / 60) * 100, 100);
            titleProgress.style.width = `${tPct}%`;
            if(tLen > 60) { titleProgress.className = 'h-full bg-red-500 transition-all'; titleLength.className = 'text-[10px] font-bold text-red-500'; }
            else if(tLen >= 40) { titleProgress.className = 'h-full bg-green-500 transition-all'; titleLength.className = 'text-[10px] font-bold text-green-600'; }
            else { titleProgress.className = 'h-full bg-blue-500 transition-all'; titleLength.className = 'text-[10px] text-slate-500 font-medium'; }

            // Desc progress
            let dPct = Math.min((dLen / 160) * 100, 100);
            descProgress.style.width = `${dPct}%`;
            if(dLen > 160) { descProgress.className = 'h-full bg-red-500 transition-all'; descLength.className = 'text-[10px] font-bold text-red-500'; }
            else if(dLen >= 120) { descProgress.className = 'h-full bg-green-500 transition-all'; descLength.className = 'text-[10px] font-bold text-green-600'; }
            else { descProgress.className = 'h-full bg-blue-500 transition-all'; descLength.className = 'text-[10px] text-slate-500 font-medium'; }
        }

        function updatePreview() {
            let title = metaTitleInput.value || titleInput.value || 'Post Title Goes Here';
            let desc = metaDescInput.value || excerptInput.value || 'This is where the meta description will appear in Google search results. It should be engaging and informative.';
            let slug = slugInput.value || 'post-url';

            previewTitle.textContent = title;
            previewDesc.textContent = desc;
            previewSlug.textContent = slug;
            
            updateLengths();
            analyzeSEO();
        }

        function countOccurrences(string, word) {
            if(!word || !string) return 0;
            return string.split(word).length - 1;
        }

        function analyzeSEO() {
            let keyword = focusKeywordInput.value.trim().toLowerCase();
            let basicHtml = '';
            let readHtml = '';
            let score = 0;
            let totalChecks = 9; // Max score 100 based on 9 checks
            let checkWeight = 100 / totalChecks;

            let titleStr = (metaTitleInput.value || titleInput.value).toLowerCase();
            let descStr = (metaDescInput.value || excerptInput.value).toLowerCase();
            let slugStr = slugInput.value.toLowerCase();
            
            // Content Extration
            let tempDiv = document.createElement("div");
            tempDiv.innerHTML = contentInput.value;
            let contentText = tempDiv.textContent || tempDiv.innerText || "";
            let wordCount = contentText.trim().split(/\s+/).length;
            if(contentText.trim() === "") wordCount = 0;

            const iconPass = '<span class="material-symbols-outlined text-[18px]">check_circle</span>';
            const iconFail = '<span class="material-symbols-outlined text-[18px]">cancel</span>';
            const iconWarn = '<span class="material-symbols-outlined text-[18px]">info</span>';

            const addCheck = (type, condition, successMsg, failMsg, warningMsg = null) => {
                if (condition === true) {
                    score += checkWeight;
                    let icon = type === 'b' ? basicHtml : readHtml;
                    let html = `<li class="flex items-start gap-2 text-green-600">${iconPass} <span>${successMsg}</span></li>`;
                    if(type === 'b') basicHtml += html; else readHtml += html;
                } else if (condition === 'warning' && warningMsg) {
                    score += (checkWeight * 0.5); // Half points for warning
                    let html = `<li class="flex items-start gap-2 text-yellow-600">${iconWarn} <span>${warningMsg}</span></li>`;
                    if(type === 'b') basicHtml += html; else readHtml += html;
                } else {
                    let html = `<li class="flex items-start gap-2 text-red-500">${iconFail} <span>${failMsg}</span></li>`;
                    if(type === 'b') basicHtml += html; else readHtml += html;
                }
            };

            // ---- BASIC SEO CHECKS ----
            if (!keyword) {
                basicHtml = `<li class="flex items-center gap-2 text-slate-400"><span class="material-symbols-outlined text-[16px]">hourglass_empty</span> Enter a focus keyword to reveal basic SEO checks...</li>`;
                readHtml = `<li class="flex items-center gap-2 text-slate-400"><span class="material-symbols-outlined text-[16px]">hourglass_empty</span> Enter a focus keyword to reveal readability checks...</li>`;
                setScore(0);
                seoBasicList.innerHTML = basicHtml;
                seoReadabilityList.innerHTML = readHtml;
                return;
            }

            // 1. Keyword in Title
            addCheck('b', titleStr.includes(keyword), 
                "Focus keyword found in the SEO title.", 
                "Focus keyword not found in the SEO title.");

            // 2. Keyword at beginning of title
            addCheck('b', titleStr.startsWith(keyword) ? true : (titleStr.includes(keyword) ? 'warning' : false),
                "Focus keyword appears at the beginning of SEO title.",
                "Keyword missing from title.",
                "Consider putting your focus keyword closer to the beginning of the title.");

            // 3. Keyword in Description
            addCheck('b', descStr.includes(keyword),
                "Focus keyword found in the meta description.",
                "Focus keyword not found in your meta description.");

            // 4. Keyword in URL
            addCheck('b', slugStr.includes(keyword.replace(/\s+/g, '-')),
                "Focus keyword found in the URL slug.",
                "Focus keyword not found in the URL slug.");

            // 5. Keyword in Content
            addCheck('b', contentText.toLowerCase().includes(keyword),
                "Focus keyword appears in the content body.",
                "Focus keyword does not appear in the content.");

            // 6. Title Length
            let tLen = titleStr.length;
            addCheck('b', tLen >= 40 && tLen <= 60 ? true : (tLen > 0 ? 'warning' : false),
                "Title length is perfect (40-60 characters).",
                "Title is empty.",
                `Title is ${tLen} chars. Recommended: 40-60 chars.`);

            // ---- READABILITY & ADVANCED CHECKS ----

            // 7. Content Length
            addCheck('r', wordCount >= 600 ? true : (wordCount >= 300 ? 'warning' : false),
                `Content is ${wordCount} words long. Good job!`,
                `Content is only ${wordCount} words. Extremely thin content.`,
                `Content is ${wordCount} words. Aim for 600+ words.`);

            // 8. Keyword Density
            let occurrences = countOccurrences(contentText.toLowerCase(), keyword);
            let density = wordCount > 0 ? ((occurrences * keyword.split(' ').length) / wordCount) * 100 : 0;
            let densityFormatted = density.toFixed(2);
            
            if (density >= 1.0 && density <= 2.5) {
                addCheck('r', true, `Keyword density is ${densityFormatted}%, which is great.`, "");
            } else if (density > 2.5) {
                addCheck('r', 'warning', "", "", `Keyword density is ${densityFormatted}%. That is too high (keyword stuffing).`);
            } else if (occurrences > 0) {
                addCheck('r', 'warning', "", "", `Keyword density is ${densityFormatted}%. That is quite low. Try using the keyword more.`);
            } else {
                addCheck('r', false, "", "Keyword does not appear in content for density check.");
            }

            // 9. Short Paragraphs Check
            // A simple proxy: check if there are many line breaks or multiple blocks
            let paragraphs = contentInput.value.split(/<\/div>|<br>|<\/p>/).filter(p => p.trim() !== '<br>' && p.trim().length > 10).length;
            // Average words per paragraph
            let wpp = paragraphs > 0 ? wordCount / paragraphs : wordCount;
            
            addCheck('r', wpp <= 80 ? true : 'warning',
                "You are using short paragraphs. Good for readability.",
                "",
                "Some of your paragraphs might be too long. Consider breaking them up.");

            seoBasicList.innerHTML = basicHtml;
            seoReadabilityList.innerHTML = readHtml;
            setScore(Math.round(score));
        }

        function setScore(score) {
            seoScoreNumber.textContent = score;
            seoScoreInput.value = score;
            
            // Stroke dash array trick for SVG circle
            // Circle length is 100 (stroke-dasharray="0, 100"). We set dasharray to "score, 100"
            seoScoreCircle.setAttribute('stroke-dasharray', `${score}, 100`);
            
            seoScoreCircle.classList.remove('text-red-500', 'text-yellow-500', 'text-green-500');
            seoScoreText.classList.remove('text-red-500', 'text-yellow-600', 'text-green-600');
            
            if (score >= 80) {
                seoScoreCircle.classList.add('text-green-500');
                seoScoreText.classList.add('text-green-600');
                seoScoreText.textContent = "Excellent";
            } else if (score >= 50) {
                seoScoreCircle.classList.add('text-yellow-500');
                seoScoreText.classList.add('text-yellow-600');
                seoScoreText.textContent = "Acceptable";
            } else {
                seoScoreCircle.classList.add('text-red-500');
                seoScoreText.classList.add('text-red-500');
                seoScoreText.textContent = "Needs Improvement";
        }
    </script>
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <script>
        // --- Custom Trix Configuration for SEO Headings ---
        document.addEventListener("trix-before-initialize", () => {
            // Add H2 and H3 to Trix's internal block attributes
            Trix.config.blockAttributes.heading2 = { tagName: "h2", terminal: true, breakOnReturn: true, group: false }
            Trix.config.blockAttributes.heading3 = { tagName: "h3", terminal: true, breakOnReturn: true, group: false }

            // Grab the default toolbar HTML
            let toolbar = Trix.config.toolbar.getDefaultHTML();
            
            // Find the generic "Heading" button and replace it with specific H1, H2, H3 buttons
            const newHeadings = `
                <button type="button" class="trix-button" data-trix-attribute="heading1" title="Heading 1" tabindex="-1">H1</button>
                <button type="button" class="trix-button" data-trix-attribute="heading2" title="Heading 2" tabindex="-1">H2</button>
                <button type="button" class="trix-button" data-trix-attribute="heading3" title="Heading 3" tabindex="-1">H3</button>
            `;
            
            // The default heading button looks like: <button type="button" class="trix-button trix-button--icon trix-button--icon-heading-1" data-trix-attribute="heading1" title="Heading" tabindex="-1">Heading</button>
            // We use string replacement to inject our new buttons.
            toolbar = toolbar.replace(
                /<button type="button" class="trix-button trix-button--icon trix-button--icon-heading-1"[^>]*>Heading<\/button>/,
                newHeadings
            );
            
            // Update the HTML config
            Trix.config.toolbar.getDefaultHTML = () => toolbar;
        });

        document.addEventListener("trix-file-accept", function(event) {
            event.preventDefault(); // Prevent direct file uploads inside trix for security, can be enabled later
            alert('Inline image uploads are currently disabled. Please use the featured image facility.');
        });
        
        // Listeners for SEO engine
        [titleInput, slugInput, focusKeywordInput, metaTitleInput, metaDescInput, excerptInput].forEach(el => {
            el.addEventListener('input', updatePreview);
        });

        // Listen for Trix content changes
        document.addEventListener("trix-change", () => {
            contentInput.value = document.querySelector('trix-editor').value;
            updatePreview();
        });

        // Initial setup on load
        window.addEventListener('DOMContentLoaded', () => {
            updatePreview();
            if(focusKeywordInput.value) analyzeSEO();
        });
    </script>
@endpush
