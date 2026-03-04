@extends('layouts.dashboard')
@section('page-title', 'Edit Listing')

@section('content')
<div class="max-w-6xl mx-auto" x-data="{
    activeTab: 'basic',
    selectedCategory: '{{ old('category_id', $listing->category_id) }}',
    categorySlug: '',
    priceLabel: 'Price (₹)',
    categoryMap: { @foreach($categories as $cat)'{{ $cat->id }}': '{{ $cat->slug }}',@endforeach },
    priceLabels: { 'stay': 'Price per Night (₹)', 'eat': 'Average Cost for 2 (₹)', 'events': 'Starting Price (₹)', 'explore': 'Price per Person (₹)', 'services': 'Service Charge (₹)', 'real-estate': 'Price (₹)' },
    init() { if (this.selectedCategory) { this.onCategoryChange(); } },
    onCategoryChange() { this.categorySlug = this.categoryMap[this.selectedCategory] || ''; this.priceLabel = this.priceLabels[this.categorySlug] || 'Price (₹)'; }
}">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-serif font-bold text-slate-900">Edit Listing</h1>
            <p class="text-slate-500 text-sm mt-1">Manage details and photos for "{{ $listing->title }}"</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('listing.show', [$listing->category->slug, $listing->slug]) }}" target="_blank" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors shadow-sm text-sm font-bold">
                <span class="material-symbols-outlined text-[18px]">visibility</span>
                View Live
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 text-green-700 border border-green-200 rounded-xl p-4 flex items-center gap-3 font-medium">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-50 text-red-700 border border-red-200 rounded-xl p-4 flex items-center gap-3 font-medium">
            <span class="material-symbols-outlined">error</span>
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 bg-red-50 text-red-700 border border-red-200 rounded-xl p-4">
            <div class="flex items-center gap-2 font-bold mb-2">
                <span class="material-symbols-outlined text-[20px]">warning</span> Please fix the following:
            </div>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex flex-col md:flex-row gap-8">
        {{-- Left Sidebar: Tabs --}}
        <div class="w-full md:w-64 flex-shrink-0">
            <nav class="sticky top-24 flex flex-col gap-1.5 p-2 bg-white rounded-2xl border border-slate-200 shadow-sm">
                <button @click="activeTab = 'basic'" type="button" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-left"
                    :class="activeTab === 'basic' ? 'bg-primary/10 text-primary' : 'text-slate-600 hover:bg-slate-50'">
                    <span class="material-symbols-outlined text-[20px]" :class="activeTab === 'basic' ? 'text-primary' : 'text-slate-400'">description</span>
                    Basic Info
                </button>
                <button @click="activeTab = 'details'" type="button" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-left"
                    :class="activeTab === 'details' ? 'bg-primary/10 text-primary' : 'text-slate-600 hover:bg-slate-50'">
                    <span class="material-symbols-outlined text-[20px]" :class="activeTab === 'details' ? 'text-primary' : 'text-slate-400'">tune</span>
                    Details & Amenities
                </button>
                <button @click="activeTab = 'photos'" type="button" class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-bold transition-all text-left group"
                    :class="activeTab === 'photos' ? 'bg-primary/10 text-primary' : 'text-slate-600 hover:bg-slate-50'">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px]" :class="activeTab === 'photos' ? 'text-primary' : 'text-slate-400'">photo_library</span>
                        Photos
                    </div>
                    <span class="bg-slate-100 text-slate-500 text-[10px] px-2 py-0.5 rounded-full font-bold group-hover:bg-slate-200">{{ $listing->images->count() }}</span>
                </button>
                <button @click="activeTab = 'contact'" type="button" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-left"
                    :class="activeTab === 'contact' ? 'bg-primary/10 text-primary' : 'text-slate-600 hover:bg-slate-50'">
                    <span class="material-symbols-outlined text-[20px]" :class="activeTab === 'contact' ? 'text-primary' : 'text-slate-400'">location_on</span>
                    Location & Contact
                </button>
            </nav>
        </div>

        {{-- Right Content Area --}}
        <div class="flex-1 min-w-0">
            <form action="{{ route('owner.listings.update', ['listing' => $listing->slug]) }}" method="POST" enctype="multipart/form-data" id="editForm">
                @csrf
                @method('PUT')

                {{-- ══ TAB 1: BASIC INFO ══════════════════════════════════════ --}}
                <div x-show="activeTab === 'basic'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm overflow-visible">
                        <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">description</span> Basic Information
                        </h2>
                        <div class="space-y-5">
                            <div>
                                <label for="title" class="block text-sm font-bold text-slate-700 mb-1.5">Listing Title <span class="text-red-500">*</span></label>
                                <input type="text" name="title" id="title" value="{{ old('title', $listing->title) }}" required class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 px-4 bg-slate-50/50 hover:bg-white transition-colors" placeholder="e.g., Luxury Beachfront Villa with Pool">
                                @error('title') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="category_id" class="block text-sm font-bold text-slate-700 mb-1.5">Category <span class="text-red-500">*</span></label>
                                    <select name="category_id" id="category_id" required x-model="selectedCategory" @change="onCategoryChange()" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 px-4 bg-slate-50/50 cursor-pointer">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="price" class="block text-sm font-bold text-slate-700 mb-1.5" x-text="priceLabel">Price (₹)</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-slate-400 font-bold">₹</span>
                                        </div>
                                        <input type="number" name="price" id="price" value="{{ old('price', $listing->price) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 pl-8 pr-4 bg-slate-50/50" placeholder="0" step="0.01">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-bold text-slate-700 mb-1.5">Description</label>
                                <textarea name="description" id="description" rows="6" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary p-4 bg-slate-50/50" placeholder="Describe what makes your offering special...">{{ old('description', $listing->description) }}</textarea>
                                @error('description') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ══ TAB 2: DETAILS & AMENITIES ═════════════════════════════ --}}
                <div x-show="activeTab === 'details'" x-cloak style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    
                    {{-- Category Specific Attributes --}}
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm overflow-visible" x-show="categorySlug !== ''">
                        <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">tune</span> Specific Details
                        </h2>
                        
                        {{-- STAY --}}
                        <div x-show="categorySlug === 'stay'" class="grid grid-cols-2 sm:grid-cols-3 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Property Type</label>
                                <select name="attributes[property_type]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                                    <option value="">Select</option>
                                    @php $pt = $listing->listingAttributes->where('attribute_key', 'property_type')->first()?->attribute_value; @endphp
                                    @foreach(['villa'=>'Villa', 'apartment'=>'Apartment', 'cottage'=>'Cottage', 'resort'=>'Resort', 'homestay'=>'Homestay'] as $val => $label)
                                        <option value="{{ $val }}" {{ $pt == $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Bedrooms</label>
                                <input type="number" name="attributes[bedrooms]" value="{{ $listing->listingAttributes->where('attribute_key', 'bedrooms')->first()?->attribute_value }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Bathrooms</label>
                                <input type="number" name="attributes[bathrooms]" value="{{ $listing->listingAttributes->where('attribute_key', 'bathrooms')->first()?->attribute_value }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Max Guests</label>
                                <input type="number" name="attributes[max_guests]" value="{{ $listing->listingAttributes->where('attribute_key', 'max_guests')->first()?->attribute_value }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Check-in Time</label>
                                <input type="time" name="attributes[check_in]" value="{{ $listing->listingAttributes->where('attribute_key', 'check_in')->first()?->attribute_value ?: '14:00' }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Check-out Time</label>
                                <input type="time" name="attributes[check_out]" value="{{ $listing->listingAttributes->where('attribute_key', 'check_out')->first()?->attribute_value ?: '11:00' }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                            </div>
                        </div>

                        {{-- EAT --}}
                        <div x-show="categorySlug === 'eat'" class="grid grid-cols-2 sm:grid-cols-3 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Cuisine Type</label>
                                <select name="attributes[cuisine]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                                    <option value="">Select</option>
                                    @php $cu = $listing->listingAttributes->where('attribute_key', 'cuisine')->first()?->attribute_value; @endphp
                                    @foreach(['indian'=>'Indian', 'coastal'=>'Coastal/Konkan', 'continental'=>'Continental', 'seafood'=>'Seafood', 'cafe'=>'Cafe/Bakery'] as $val => $label)
                                        <option value="{{ $val }}" {{ $cu == $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Food Type</label>
                                <select name="attributes[food_type]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                                    <option value="">Select</option>
                                    @php $ft = $listing->listingAttributes->where('attribute_key', 'food_type')->first()?->attribute_value; @endphp
                                    <option value="veg" {{ $ft == 'veg' ? 'selected' : '' }}>Pure Veg</option>
                                    <option value="non-veg" {{ $ft == 'non-veg' ? 'selected' : '' }}>Non-Veg</option>
                                    <option value="both" {{ $ft == 'both' ? 'selected' : '' }}>Veg & Non-Veg</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Seating Capacity</label>
                                <input type="number" name="attributes[seating_capacity]" value="{{ $listing->listingAttributes->where('attribute_key', 'seating_capacity')->first()?->attribute_value }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Average Cost (for 2)</label>
                                <input type="number" name="attributes[avg_cost_for_two]" value="{{ $listing->listingAttributes->where('attribute_key', 'avg_cost_for_two')->first()?->attribute_value }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Opens At</label>
                                <input type="time" name="attributes[opens_at]" value="{{ $listing->listingAttributes->where('attribute_key', 'opens_at')->first()?->attribute_value ?: '10:00' }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Closes At</label>
                                <input type="time" name="attributes[closes_at]" value="{{ $listing->listingAttributes->where('attribute_key', 'closes_at')->first()?->attribute_value ?: '23:00' }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                            </div>
                        </div>
                        
                        {{-- Generic Fallback text for others --}}
                        <div x-show="!['stay', 'eat'].includes(categorySlug)">
                            <p class="text-sm text-slate-500 italic">No special details required for this category. You can skip to Amenities.</p>
                        </div>
                    </div>

                    {{-- Amenities Grid --}}
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">local_florist</span> Amenities & Features
                        </h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($amenities as $amenity)
                                <label class="flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 cursor-pointer hover:border-primary/40 hover:bg-white has-[:checked]:bg-primary/5 has-[:checked]:border-primary transition-all">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" {{ in_array($amenity->id, old('amenities', $listing->amenities->pluck('id')->toArray() ?? [])) ? 'checked' : '' }} class="peer size-5 rounded border-slate-300 text-primary shadow-sm focus:ring-primary focus:ring-offset-0">
                                    </div>
                                    <span class="material-symbols-outlined text-slate-400 text-[20px] peer-checked:text-primary transition-colors">{{ $amenity->icon }}</span>
                                    <span class="text-sm text-slate-700 font-bold select-none">{{ $amenity->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ══ TAB 3: LOCATION & CONTACT ══════════════════════════════ --}}
                <div x-show="activeTab === 'contact'" x-cloak style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">pin_drop</span> Location & Contact
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-bold text-slate-700 mb-1.5">Full Address</label>
                                <input type="text" name="address" id="address" value="{{ old('address', $listing->address) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 px-4 bg-slate-50/50" placeholder="e.g., Kihim Beach Road, Alibaug 402201">
                            </div>
                            <div>
                                <label for="area_id" class="block text-sm font-bold text-slate-700 mb-1.5">Local Area in Alibaug</label>
                                <select name="area_id" id="area_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 px-4 bg-slate-50/50 cursor-pointer">
                                    <option value="">Select Area</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}" {{ old('area_id', $listing->area_id) == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Contact Phone</label>
                                <input type="tel" name="phone" value="{{ old('phone', $listing->phone) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 px-4 bg-slate-50/50" placeholder="+91">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">WhatsApp Number</label>
                                <input type="tel" name="whatsapp" value="{{ old('whatsapp', $listing->whatsapp) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 px-4 bg-slate-50/50" placeholder="+91">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $listing->email) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 px-4 bg-slate-50/50" placeholder="hello@example.com">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ══ TAB 4: PHOTOS ══════════════════════════════════════════ --}}
                <div x-show="activeTab === 'photos'" x-cloak style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    
                    {{-- Upload New Photos --}}
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm" x-data="{
                        selectedFiles: [],
                        handleFileSelect(e) {
                            this.selectedFiles = [];
                            const files = e.target.files;
                            for (let i = 0; i < files.length; i++) {
                                this.selectedFiles.push({
                                    name: files[i].name,
                                    size: (files[i].size / 1024 / 1024).toFixed(2),
                                    url: URL.createObjectURL(files[i])
                                });
                            }
                        }
                    }">
                        <h2 class="text-xl font-bold text-slate-900 mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">add_photo_alternate</span> Upload New Photos
                        </h2>
                        <p class="text-sm text-slate-500 mb-5">Add high-quality photos to make your listing stand out. You can upload multiple files at once.</p>
                        
                        <div class="border-2 border-dashed rounded-xl p-8 transition-all group relative cursor-pointer text-center"
                             :class="selectedFiles.length > 0 ? 'border-green-400 bg-green-50/50' : 'border-slate-300 hover:border-primary/60 hover:bg-primary/5'">
                            <input type="file" name="images[]" id="images" multiple accept="image/*" @change="handleFileSelect($event)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div x-show="selectedFiles.length === 0" class="flex flex-col items-center justify-center space-y-3 pointer-events-none">
                                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center text-primary group-hover:scale-110 group-hover:bg-primary/20 transition-all duration-300">
                                    <span class="material-symbols-outlined text-3xl">upload</span>
                                </div>
                                <div>
                                    <p class="text-slate-700 font-bold text-base mb-1">Click to browse or drag & drop here</p>
                                    <p class="text-slate-400 text-sm">JPG, PNG up to 5MB each</p>
                                </div>
                            </div>
                            
                            {{-- Selected Files Preview --}}
                            <div x-show="selectedFiles.length > 0" class="pointer-events-none">
                                <div class="flex items-center justify-center gap-2 text-green-600 mb-4">
                                    <span class="material-symbols-outlined text-[22px]">check_circle</span>
                                    <span class="font-bold text-base" x-text="selectedFiles.length + ' photo(s) selected — Click Save to upload'"></span>
                                </div>
                                <div class="grid grid-cols-3 sm:grid-cols-5 gap-3">
                                    <template x-for="(file, i) in selectedFiles" :key="i">
                                        <div class="relative rounded-lg overflow-hidden border border-green-200 aspect-square bg-white">
                                            <img :src="file.url" class="w-full h-full object-cover">
                                            <div class="absolute bottom-0 inset-x-0 bg-black/50 py-1 px-1.5">
                                                <p class="text-white text-[9px] truncate font-bold" x-text="file.name"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <p class="text-green-500 text-xs mt-3 font-medium">Click area again to change selection</p>
                            </div>
                        </div>
                    </div>

                    {{-- Existing Photos Grid --}}
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-5">
                            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">grid_view</span> Existing Photos ({{ $listing->images->count() }})
                            </h2>
                            @if($listing->images->count() <= 1)
                                <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-md border border-amber-200">Min 1 photo required</span>
                            @endif
                        </div>
                        
                        @if($listing->images->count() > 0)
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($listing->images as $image)
                                    <div class="relative group rounded-xl overflow-hidden border border-slate-200 aspect-[4/3] bg-slate-100">
                                        <img src="{{ $image->path }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                        
                                        {{-- Image Overlays --}}
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        
                                        @if($image->is_primary)
                                            <div class="absolute top-2 left-2 bg-primary text-white text-[10px] font-bold px-2 py-1 rounded-md shadow-sm">Cover</div>
                                        @endif
                                        
                                        {{-- Delete Button (Form outside to prevent nesting form tags) --}}
                                        <button type="button" 
                                                @click="if(confirm('Are you sure you want to delete this photo?')) { document.getElementById('delete-img-{{ $image->id }}').submit(); }"
                                                class="absolute bottom-3 right-3 w-8 h-8 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center shadow-lg transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300"
                                                title="Delete photo">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-10 bg-slate-50 border border-slate-200 border-dashed rounded-xl">
                                <span class="material-symbols-outlined text-slate-300 text-4xl mb-2">imagesmode</span>
                                <p class="text-slate-500 font-medium">No photos uploaded yet.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Sticky Save Bar --}}
                <div class="sticky bottom-0 z-40 bg-white border-t border-slate-200 p-4 mt-8 flex items-center justify-between rounded-b-2xl shadow-[0_-4px_20px_rgba(0,0,0,0.05)]">
                    <p class="text-sm text-slate-500 font-medium hidden sm:block">Remember to save your changes before leaving.</p>
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <a href="{{ route('owner.listings.index') }}" class="flex-1 sm:flex-none text-center px-6 py-3 rounded-xl font-bold text-sm text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Cancel</a>
                        <button type="submit" class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 text-white px-8 py-3 rounded-xl font-bold text-sm transition-all shadow-md shadow-primary/20">
                            <span class="material-symbols-outlined text-[20px]">save</span>
                            Save All Changes
                        </button>
                    </div>
                </div>

            </form>
            
            {{-- Hidden Forms for Image Deletion (Outside the main form so nested forms don't break HTML) --}}
            @foreach($listing->images as $image)
                <form id="delete-img-{{ $image->id }}" action="{{ route('owner.listings.images.destroy', $image->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
            
        </div>
    </div>
</div>
@endsection
