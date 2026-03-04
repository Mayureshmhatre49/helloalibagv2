@extends('layouts.app')

@section('title', 'Add Your Listing — ' . $category->name)

@section('content')
<div class="min-h-screen bg-slate-50 py-10" x-data="wizard()" x-init="init()">
    <div class="max-w-4xl mx-auto px-4 w-full">

        {{-- Header: Category badge --}}
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary">{{ $category->icon ?? 'storefront' }}</span>
            </div>
            <div>
                <p class="text-xs font-bold text-primary uppercase tracking-widest">{{ $category->name }}</p>
                <h1 class="text-2xl font-bold text-slate-900">Create Your Listing</h1>
            </div>
            <a href="{{ route('owner.onboarding.start') }}" class="ml-auto text-xs text-slate-400 hover:text-primary flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">arrow_back</span> Change Category
            </a>
        </div>

        {{-- Progress Header --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <template x-for="(stepName, index) in visibleStepNames" :key="index">
                    <div class="flex-1 flex items-center">
                        <div class="flex flex-col items-center flex-1 relative z-10 text-center">
                            <div class="size-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300"
                                 :class="currentVisibleIndex > index ? 'bg-primary text-white' : (currentVisibleIndex === index ? 'bg-primary text-white ring-4 ring-primary/20' : 'bg-slate-200 text-slate-500')">
                                <span x-show="currentVisibleIndex > index" class="material-symbols-outlined text-[20px]">check</span>
                                <span x-show="currentVisibleIndex <= index" x-text="index + 1"></span>
                            </div>
                            <span class="text-[11px] font-bold mt-2 transition-colors hidden sm:block"
                                  :class="currentVisibleIndex >= index ? 'text-slate-900' : 'text-slate-400'"
                                  x-text="stepName"></span>
                        </div>
                        <div class="h-1 flex-1 bg-slate-200 rounded-full overflow-hidden mx-[-0.5rem] relative z-0" x-show="index < visibleStepNames.length - 1">
                            <div class="h-full bg-primary transition-all duration-500" :style="`width:${currentVisibleIndex > index ? 100 : 0}%`"></div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Validation Errors --}}
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl px-5 py-4 mb-6 flex items-start gap-3">
            <span class="material-symbols-outlined text-red-500 mt-0.5">error</span>
            <div>
                <p class="font-bold text-sm mb-1">Please fix the following:</p>
                <ul class="text-sm space-y-0.5 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        {{-- Form Content --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
            <form id="onboardingForm" action="{{ route('owner.onboarding.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="category_id" value="{{ $category->id }}">

                <div class="p-8 sm:p-12">

                    {{-- ═══ STEP 1: Basic Info ════════════════════════════════════ --}}
                    <div x-show="step === 1" x-transition.opacity.duration.300ms data-step="1">
                        <div class="mb-8">
                            <h2 class="text-2xl font-serif font-bold text-slate-900 mb-1">Basic Information</h2>
                            <p class="text-slate-500">Start with the key details about your {{ strtolower($category->name) }} listing.</p>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">
                                    Listing Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" x-model="formData.title" required maxlength="100"
                                    class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3"
                                    placeholder="{{ match($category->slug) { 'stay' => 'e.g., Sunrise Sea View Villa, Alibaug', 'eat' => 'e.g., The Coastal Kitchen — Seafood & Grills', 'real-estate' => 'e.g., Premium Beachside Villa Plot — 3000 sq.ft', default => 'e.g., Professional ' . $category->name . ' Service in Alibaug' } }}">
                                <p class="text-xs text-slate-400 mt-1">Keep it descriptive and specific — good titles get 3x more clicks.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Area / Location <span class="text-red-500">*</span></label>
                                    <select name="area_id" x-model="formData.area_id" required class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3">
                                        <option value="">Select Area in Alibaug</option>
                                        @foreach($areas as $area)
                                            <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">
                                        @if($category->slug === 'stay') Starting Price (₹ per night)
                                        @elseif($category->slug === 'eat') Avg Cost for Two (₹)
                                        @elseif($category->slug === 'real-estate') Asking Price (₹)
                                        @else Price (₹) @endif
                                        <span class="text-slate-400 font-normal">(Optional)</span>
                                    </label>
                                    <input type="number" name="price" x-model="formData.price" min="0"
                                        class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3"
                                        placeholder="{{ $category->slug === 'stay' ? 'e.g., 8000' : ($category->slug === 'eat' ? 'e.g., 800' : 'e.g., 50,00,000') }}">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Full Address <span class="text-slate-400 font-normal">(Optional)</span></label>
                                <input type="text" name="address" x-model="formData.address"
                                    class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3"
                                    placeholder="Street / landmark, Village, Alibaug...">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Description <span class="text-red-500">*</span></label>
                                <textarea name="description" x-model="formData.description" required rows="6"
                                    class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3"
                                    placeholder="{{ match($category->slug) {
                                        'stay' => 'Describe the property — its setting, unique features, what\'s nearby, and the experience guests will have...',
                                        'eat' => 'Tell visitors about your restaurant — the vibe, signature dishes, sourcing, and what makes dining here special...',
                                        'real-estate' => 'Describe the property — construction status, views, connectivity to Mandwa/Mumbai ferry, plot dimensions...',
                                        default => 'Tell visitors what you offer, your expertise, and why they should choose you...'
                                    } }}"></textarea>
                                <p class="text-xs text-slate-400 mt-1">Minimum 20 characters. Write for your customer — what will excite them?</p>
                            </div>

                            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                                <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary text-[18px]">contact_phone</span>
                                    Contact Details
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Contact Name</label>
                                        <input type="text" value="{{ auth()->user()->name }}" readonly class="w-full bg-slate-100 rounded-xl border-transparent text-slate-500 py-3 cursor-not-allowed">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">WhatsApp / Phone</label>
                                        <input type="tel" name="phone" x-model="formData.phone"
                                            class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3"
                                            placeholder="+91 98xxx xxxxx">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Business Email</label>
                                        <input type="email" name="email" x-model="formData.email"
                                            class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3"
                                            placeholder="contact@yourbusiness.com">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ═══ STEP 2: Category-Specific Details ══════════════════════ --}}
                    <div x-show="step === 2 && hasAttributes" x-transition.opacity.duration.300ms style="display: none;" data-step="2">
                        <div class="mb-8">
                            <h2 class="text-2xl font-serif font-bold text-slate-900 mb-1">{{ $category->name }} Details</h2>
                            <p class="text-slate-500">These specific details help visitors find exactly what they're looking for.</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($category->attributes->sortBy('sort_order') as $attribute)
                            <div class="{{ in_array($attribute->field_type, ['textarea']) ? 'md:col-span-2' : '' }}">
                                <label class="block text-sm font-bold text-slate-700 mb-2">
                                    {{ $attribute->name }}
                                    {!! $attribute->is_required ? '<span class="text-red-500">*</span>' : '<span class="text-slate-400 font-normal">(Optional)</span>' !!}
                                </label>

                                @if($attribute->field_type === 'select' && $attribute->values->count() > 0)
                                    <select name="dynamic_attributes[{{ $attribute->id }}]"
                                        {{ $attribute->is_required ? 'required' : '' }}
                                        class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3">
                                        <option value="">Select {{ $attribute->name }}</option>
                                        @foreach($attribute->values as $val)
                                            <option value="{{ $val->value }}">{{ $val->label }}</option>
                                        @endforeach
                                    </select>
                                @elseif($attribute->field_type === 'number')
                                    <input type="number" name="dynamic_attributes[{{ $attribute->id }}]"
                                        {{ $attribute->is_required ? 'required' : '' }} min="0"
                                        class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3"
                                        placeholder="{{ $attribute->name }}">
                                @else
                                    <input type="text" name="dynamic_attributes[{{ $attribute->id }}]"
                                        {{ $attribute->is_required ? 'required' : '' }}
                                        class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3"
                                        placeholder="{{ $attribute->name }}">
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ═══ STEP 3: Amenities ══════════════════════════════════════ --}}
                    <div x-show="(step === 3 && hasAmenities) || (step === 2 && !hasAttributes && hasAmenities)" x-transition.opacity.duration.300ms style="display: none;" data-step="3">
                        <div class="mb-8">
                            <h2 class="text-2xl font-serif font-bold text-slate-900 mb-1">Amenities & Features</h2>
                            <p class="text-slate-500">Select everything that applies — this helps visitors filter and find you.</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($amenities as $amenity)
                            <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 cursor-pointer hover:border-primary/50 transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" class="rounded text-primary focus:ring-primary/20 size-5 border-slate-300">
                                <span class="material-symbols-outlined text-slate-500 text-[20px]" style="font-variation-settings:'FILL' 0">{{ $amenity->icon }}</span>
                                <span class="font-medium text-slate-800 text-sm">{{ $amenity->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- ═══ STEP 4: Photos ═════════════════════════════════════════ --}}
                    <div x-show="step === totalSteps - 2" x-transition.opacity.duration.300ms style="display: none;" data-step="4">
                        <div class="mb-8">
                            <h2 class="text-2xl font-serif font-bold text-slate-900 mb-1">Photos</h2>
                            <p class="text-slate-500">Upload high-quality photos. The first photo will be your cover image. (JPEG / PNG, max 8 MB each)</p>
                        </div>

                        <div class="border-2 border-dashed border-slate-300 rounded-3xl p-10 text-center transition-colors"
                             :class="imagesCount >= 1 ? 'border-green-300 bg-green-50' : 'bg-slate-50 hover:border-primary/40'">
                            <span class="material-symbols-outlined text-5xl mb-4" :class="imagesCount >= 1 ? 'text-green-500' : 'text-slate-400'" style="font-variation-settings:'FILL' 1">
                                <span x-text="imagesCount >= 1 ? 'check_circle' : 'add_photo_alternate'"></span>
                            </span>
                            <h3 class="font-bold text-slate-800 text-lg mb-2">Upload Listing Photos</h3>
                            <p class="text-sm text-slate-500 mb-6">Select one or more photos from your device.</p>

                            <input type="file" name="images[]" id="imageInput" multiple accept="image/jpeg,image/png,image/webp"
                                   @change="updateImageCount" class="hidden">

                            <label for="imageInput" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-xl font-bold shadow-md cursor-pointer hover:bg-primary/90 transition-colors">
                                <span class="material-symbols-outlined text-[18px]">upload</span>
                                Browse & Upload
                            </label>

                            <div class="mt-5" x-show="imagesCount > 0">
                                <p class="font-bold text-green-700 flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                    <span x-text="imagesCount + ' photo' + (imagesCount > 1 ? 's' : '') + ' selected'"></span>
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 bg-blue-50 rounded-2xl p-4 border border-blue-100 flex items-start gap-3">
                            <span class="material-symbols-outlined text-blue-400 text-[20px] mt-0.5">tips_and_updates</span>
                            <div class="text-sm text-blue-800">
                                <strong>Pro Tips:</strong>
                                @if($category->slug === 'stay')
                                    Use photos of the entrance, pool/garden, bedrooms, bathrooms, kitchen, and the view. Natural daylight works best.
                                @elseif($category->slug === 'eat')
                                    Show the entrance, seating area, best dishes, ambiance shots, and any outdoor space.
                                @else
                                    Show what visitors will experience — your space, team, services in action.
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ═══ STEP 5: SEO ════════════════════════════════════════════ --}}
                    <div x-show="step === totalSteps - 1" x-transition.opacity.duration.300ms style="display: none;" data-step="5">
                        <div class="mb-8">
                            <h2 class="text-2xl font-serif font-bold text-slate-900 mb-1">Search Optimization</h2>
                            <p class="text-slate-500">Help Google and our search show your listing to the right people. (Optional but recommended)</p>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">SEO Title <span class="text-slate-400 font-normal">(leave blank to use your listing title)</span></label>
                                <input type="text" name="meta_title" x-model="formData.meta_title" maxlength="70"
                                    class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3"
                                    placeholder="e.g., Luxury Sea View Villa in Alibaug — Book Now">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">SEO Description</label>
                                <textarea name="meta_description" x-model="formData.meta_description" rows="3" maxlength="160"
                                    class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3"
                                    placeholder="A 1-2 line summary for search results..."></textarea>
                            </div>
                            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Google Preview</p>
                                <p class="text-xs text-green-600 mb-0.5">helloalibaug.com › {{ $category->slug }} › <span x-text="slugPreview"></span></p>
                                <p class="text-lg text-blue-600 font-medium hover:underline cursor-pointer truncate" x-text="formData.meta_title || formData.title || 'Your Listing Title'"></p>
                                <p class="text-sm text-slate-600 line-clamp-2" x-text="formData.meta_description || formData.description || 'Your description will appear here...'"></p>
                            </div>
                        </div>
                    </div>

                    {{-- ═══ STEP 6: Review & Submit ════════════════════════════════ --}}
                    <div x-show="step === totalSteps" x-transition.opacity.duration.300ms style="display: none;" data-step="6">
                        <div class="mb-8">
                            <h2 class="text-2xl font-serif font-bold text-slate-900 mb-1">Review & Submit</h2>
                            <p class="text-slate-500">Almost there! Check your info below before submitting.</p>
                        </div>

                        <div class="space-y-3 mb-8">
                            <div class="flex items-center justify-between bg-slate-50 rounded-xl px-5 py-4 border border-slate-100">
                                <div>
                                    <p class="text-xs text-slate-400 mb-0.5">Category</p>
                                    <p class="font-bold text-slate-900">{{ $category->name }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between bg-slate-50 rounded-xl px-5 py-4 border border-slate-100">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-slate-400 mb-0.5">Listing Title</p>
                                    <p class="font-bold text-slate-900 truncate" x-text="formData.title || '—'"></p>
                                </div>
                                <button type="button" @click="goToStep(1)" class="ml-4 text-primary text-sm font-bold hover:underline flex-shrink-0">Edit</button>
                            </div>
                            <div class="flex items-center justify-between bg-slate-50 rounded-xl px-5 py-4 border border-slate-100">
                                <div>
                                    <p class="text-xs text-slate-400 mb-0.5">Photos</p>
                                    <p class="font-bold" :class="imagesCount >= 1 ? 'text-green-700' : 'text-red-500'" x-text="imagesCount > 0 ? imagesCount + ' photo(s) attached' : 'No photos — please go back!'"></p>
                                </div>
                                <button type="button" @click="goToStep(totalSteps - 2)" class="ml-4 text-primary text-sm font-bold hover:underline">Edit</button>
                            </div>
                        </div>

                        <div class="bg-green-50 border border-green-200 rounded-2xl p-5 flex gap-4 items-start">
                            <span class="material-symbols-outlined text-green-500 text-2xl">verified</span>
                            <div>
                                <h4 class="font-bold text-green-900 mb-1">Ready to Submit!</h4>
                                <p class="text-sm text-green-800">Your listing will be manually reviewed by our team. Approval typically takes 24 hours — we'll notify you by email once it's live.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer Navigation --}}
                <div class="bg-slate-50 border-t border-slate-100 p-6 sm:p-8 flex items-center justify-between">
                    <button type="button" x-show="step > 1" @click="prevStep"
                        class="px-6 py-3 rounded-xl font-bold text-slate-600 hover:bg-white hover:text-slate-900 transition-colors border border-transparent hover:border-slate-200 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back
                    </button>
                    <div x-show="step === 1"></div>

                    <div class="flex items-center gap-3">
                        <p class="text-xs text-slate-400 hidden sm:block" x-text="`Step ${currentVisibleIndex + 1} of ${visibleStepNames.length}`"></p>
                        <button type="button" x-show="step < totalSteps" @click="nextStep"
                            class="bg-slate-900 hover:bg-black text-white px-8 py-3 rounded-xl font-bold transition-all shadow-lg shadow-slate-900/20 flex items-center gap-2">
                            Next <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </button>
                        <button type="submit" x-show="step === totalSteps" :disabled="imagesCount < 1"
                            class="bg-primary hover:bg-primary/90 text-white px-10 py-3 rounded-xl font-bold transition-all shadow-xl shadow-primary/30 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="material-symbols-outlined text-[20px]">rocket_launch</span> Submit Listing
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('wizard', () => ({
        step: 1,
        // Dynamically set based on whether details/amenities steps exist
        hasAttributes: {{ $category->attributes->count() > 0 ? 'true' : 'false' }},
        hasAmenities:  {{ $amenities->count() > 0 ? 'true' : 'false' }},
        imagesCount: 0,
        formData: {
            title: '{{ old("title", "") }}',
            area_id: '{{ old("area_id", "") }}',
            price: '{{ old("price", "") }}',
            description: '{{ old("description", "") }}',
            address: '{{ old("address", "") }}',
            email: '{{ auth()->user()->email }}',
            phone: '{{ auth()->user()->phone ?? "" }}',
            meta_title: '',
            meta_description: '',
        },

        get totalSteps() {
            // Always: Basic Info + (Details if has attrs) + (Amenities if has amenities) + Photos + SEO + Review
            let total = 4; // Basic + Photos + SEO + Review (minimum)
            if (this.hasAttributes) total++;
            if (this.hasAmenities) total++;
            return total;
        },

        get visibleStepNames() {
            const names = ['Basic Info'];
            if (this.hasAttributes) names.push('Details');
            if (this.hasAmenities) names.push('Amenities');
            names.push('Photos', 'SEO', 'Review');
            return names;
        },

        get currentVisibleIndex() {
            // Map the internal step number to a visible index
            if (this.step === 1) return 0;
            let idx = 1;
            if (this.hasAttributes) {
                if (this.step === 2) return idx;
                idx++;
            }
            if (this.hasAmenities) {
                const amenStep = this.hasAttributes ? 3 : 2;
                if (this.step === amenStep) return idx;
                idx++;
            }
            if (this.step === this.totalSteps - 2) return idx; // Photos
            if (this.step === this.totalSteps - 1) return idx + 1; // SEO
            if (this.step === this.totalSteps) return idx + 2; // Review
            return idx;
        },

        get slugPreview() {
            if (!this.formData.title) return 'your-listing-slug';
            return this.formData.title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
        },

        init() {
            // If no attributes AND no amenities, we have only 4 steps: Basic / Photos / SEO / Review
            // Adjust step display logic accordingly (handled by x-show conditions)
        },

        updateImageCount(e) {
            this.imagesCount = e.target.files.length;
        },

        goToStep(s) {
            this.step = Math.max(1, Math.min(s, this.totalSteps));
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        nextStep() {
            if (this.validateStep(this.step)) {
                // Skip step 2 (Details) if no attributes
                if (this.step === 1 && !this.hasAttributes) {
                    if (!this.hasAmenities) { this.step = this.totalSteps - 2; } // jump to Photos
                    else { this.step = 3; } // has amenities at step 3
                } else if (this.step === 2 && this.hasAttributes && !this.hasAmenities) {
                    this.step = this.totalSteps - 2; // skip Amenities, jump to Photos
                } else {
                    this.step++;
                }
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        prevStep() {
            if (this.step <= 1) return;
            // Skip step 2 (Details) if no attributes going backwards
            if (this.step === (this.totalSteps - 2) && !this.hasAttributes && !this.hasAmenities) {
                this.step = 1;
            } else if (this.step === (this.totalSteps - 2) && !this.hasAttributes && this.hasAmenities) {
                this.step = 3;
            } else if (this.step === 3 && this.hasAmenities && !this.hasAttributes) {
                this.step = 1;
            } else {
                this.step--;
            }
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        validateStep(s) {
            const stepEl = document.querySelector(`[data-step="${s}"]`);
            if (!stepEl?.checkVisibility()) return true;

            const inputs = stepEl.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;
            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    isValid = false;
                }
            });

            if (s === this.totalSteps - 2 && this.imagesCount < 1) {
                alert('Please upload at least 1 photo before continuing.');
                return false;
            }
            return isValid;
        }
    }));
});
</script>
@endpush
@endsection
