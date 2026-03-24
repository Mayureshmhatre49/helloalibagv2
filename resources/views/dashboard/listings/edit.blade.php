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
                    <span class="bg-slate-100 text-slate-500 text-[10px] px-2 py-0.5 rounded-full font-bold group-hover:bg-slate-200">{{ $galleryImages->count() }}</span>
                </button>
                <button @click="activeTab = 'contact'" type="button" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-left"
                    :class="activeTab === 'contact' ? 'bg-primary/10 text-primary' : 'text-slate-600 hover:bg-slate-50'">
                    <span class="material-symbols-outlined text-[20px]" :class="activeTab === 'contact' ? 'text-primary' : 'text-slate-400'">location_on</span>
                    Location & Contact
                </button>
            </nav>

            {{-- Quality Score Card --}}
            @php
                $score = $listing->getQualityScore();
                $label = $listing->getQualityLabel();
                $color = $listing->getQualityColor();
                $checks = [
                    ['label' => 'Add photos',        'done' => $listing->images->count() > 0,     'points' => 20],
                    ['label' => 'Write description', 'done' => strlen($listing->description ?? '') > 50, 'points' => 20],
                    ['label' => 'Add phone number',  'done' => !empty($listing->phone),            'points' => 15],
                    ['label' => 'Add amenities',     'done' => $listing->amenities->count() > 0,  'points' => 15],
                    ['label' => 'Set local area',    'done' => !empty($listing->area_id),          'points' => 10],
                    ['label' => 'Set price',         'done' => ($listing->price ?? 0) > 0,        'points' => 10],
                    ['label' => 'Add WhatsApp',      'done' => !empty($listing->whatsapp),         'points' => 10],
                ];
            @endphp
            <div class="mt-4 bg-white rounded-2xl border border-slate-200 shadow-sm p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Listing Score</span>
                    <span class="text-lg font-bold {{ $color }}">{{ $score }}<span class="text-xs text-slate-400">/100</span></span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2 mb-3">
                    <div class="h-2 rounded-full transition-all {{ str_contains($color, 'emerald') ? 'bg-emerald-500' : (str_contains($color, 'blue') ? 'bg-blue-500' : (str_contains($color, 'amber') ? 'bg-amber-400' : 'bg-red-400')) }}" style="width: {{ $score }}%"></div>
                </div>
                <p class="text-xs font-bold {{ $color }} mb-3">{{ $label }}</p>
                <ul class="space-y-1.5">
                    @foreach($checks as $check)
                        <li class="flex items-center gap-2 text-xs {{ $check['done'] ? 'text-slate-400 line-through' : 'text-slate-600' }}">
                            <span class="material-symbols-outlined text-[14px] {{ $check['done'] ? 'text-emerald-500' : 'text-slate-300' }}">{{ $check['done'] ? 'check_circle' : 'radio_button_unchecked' }}</span>
                            {{ $check['label'] }}
                            @if(!$check['done'])
                                <span class="ml-auto text-[10px] text-slate-400 font-bold">+{{ $check['points'] }}pts</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
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
                        <div x-show="categorySlug === 'eat'" class="space-y-7">
                            @php
                                $cu   = $listing->listingAttributes->where('attribute_key', 'cuisine')->first()?->attribute_value;
                                $ft   = $listing->listingAttributes->where('attribute_key', 'food_type')->first()?->attribute_value;
                                $eatDiningOpts   = explode(',', $listing->listingAttributes->where('attribute_key', 'dining_options')->first()?->attribute_value ?? '');
                                $eatFacilities   = explode(',', $listing->listingAttributes->where('attribute_key', 'dining_facilities')->first()?->attribute_value ?? '');
                                $eatAmbiance     = explode(',', $listing->listingAttributes->where('attribute_key', 'dining_ambiance')->first()?->attribute_value ?? '');
                                $eatPayment      = explode(',', $listing->listingAttributes->where('attribute_key', 'payment_methods')->first()?->attribute_value ?? '');
                                $eatDietary      = explode(',', $listing->listingAttributes->where('attribute_key', 'dietary_options')->first()?->attribute_value ?? '');
                                $eatClosedOn     = explode(',', $listing->listingAttributes->where('attribute_key', 'closed_on')->first()?->attribute_value ?? '');
                                $eatResvPolicy   = $listing->listingAttributes->where('attribute_key', 'reservation_policy')->first()?->attribute_value;
                            @endphp

                            {{-- Basic Info --}}
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Basic Info</p>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Cuisine Type</label>
                                        <select name="attributes[cuisine]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                                            <option value="">Select</option>
                                            @foreach(['malvani'=>'Malvani', 'konkan'=>'Konkan / Coastal', 'aagri'=>'Aagri', 'koli'=>'Koli (Fisher Folk)', 'seafood'=>'Seafood Specialist', 'fish_thali'=>'Fish Thali House', 'farm_to_table'=>'Farm-to-Table', 'fusion'=>'Fusion / Modern', 'indian'=>'Indian', 'continental'=>'Continental', 'cafe'=>'Cafe / Bakery', 'multi_cuisine'=>'Multi-Cuisine', 'chinese'=>'Chinese', 'italian'=>'Italian', 'beach_shack'=>'Beach Shack'] as $val => $lbl)
                                                <option value="{{ $val }}" {{ $cu == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Food Type</label>
                                        <select name="attributes[food_type]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                                            <option value="">Select</option>
                                            <option value="veg" {{ $ft == 'veg' ? 'selected' : '' }}>Pure Veg</option>
                                            <option value="non-veg" {{ $ft == 'non-veg' ? 'selected' : '' }}>Non-Veg</option>
                                            <option value="both" {{ $ft == 'both' ? 'selected' : '' }}>Veg & Non-Veg</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Seating Capacity</label>
                                        <input type="number" name="attributes[seating_capacity]" value="{{ $listing->listingAttributes->where('attribute_key', 'seating_capacity')->first()?->attribute_value }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50" placeholder="e.g., 40">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Avg Cost for 2 (₹)</label>
                                        <input type="number" name="attributes[avg_cost_for_two]" value="{{ $listing->listingAttributes->where('attribute_key', 'avg_cost_for_two')->first()?->attribute_value }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50" placeholder="e.g., 800">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Opens At</label>
                                        <input type="time" name="attributes[opens_at]" value="{{ $listing->listingAttributes->where('attribute_key', 'opens_at')->first()?->attribute_value ?: '10:00' }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Closes At</label>
                                        <input type="time" name="attributes[closes_at]" value="{{ $listing->listingAttributes->where('attribute_key', 'closes_at')->first()?->attribute_value ?: '23:00' }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Reservation Policy</label>
                                        <select name="attributes[reservation_policy]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                                            <option value="">Select</option>
                                            @foreach(['required'=>'Reservation Required','recommended'=>'Recommended','not_needed'=>'Walk-ins Welcome'] as $v => $l)
                                                <option value="{{ $v }}" {{ $eatResvPolicy === $v ? 'selected' : '' }}>{{ $l }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Dining Options --}}
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Dining Options</p>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    @foreach(['dine_in'=>['restaurant','Dine In'],'takeaway'=>['takeout_dining','Takeaway'],'delivery'=>['delivery_dining','Home Delivery'],'outdoor_seating'=>['deck','Outdoor Seating'],'live_counter'=>['storefront','Live Counter'],'private_dining'=>['meeting_room','Private Dining']] as $val => [$icon, $lbl])
                                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50/50 cursor-pointer hover:border-primary/40 hover:bg-white has-[:checked]:bg-primary/5 has-[:checked]:border-primary transition-all">
                                            <input type="checkbox" name="attributes[dining_options][]" value="{{ $val }}" {{ in_array($val, $eatDiningOpts) ? 'checked' : '' }} class="size-4 rounded border-slate-300 text-primary focus:ring-primary">
                                            <span class="material-symbols-outlined text-slate-400 text-[18px]">{{ $icon }}</span>
                                            <span class="text-xs font-bold text-slate-700">{{ $lbl }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Dining Facilities --}}
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Facilities & Atmosphere</p>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    @foreach(['pet_friendly'=>['pets','Pet Friendly'],'kid_friendly'=>['child_care','Kid Friendly'],'wheelchair_accessible'=>['accessible','Wheelchair OK'],'wifi_available'=>['wifi','Free WiFi'],'live_music'=>['music_note','Live Music'],'air_conditioned'=>['ac_unit','Air Conditioned'],'alcohol_served'=>['local_bar','Alcohol Served'],'rooftop'=>['roofing','Rooftop'],'sea_view'=>['waves','Sea View'],'garden_seating'=>['park','Garden Seating']] as $val => [$icon, $lbl])
                                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50/50 cursor-pointer hover:border-primary/40 hover:bg-white has-[:checked]:bg-primary/5 has-[:checked]:border-primary transition-all">
                                            <input type="checkbox" name="attributes[dining_facilities][]" value="{{ $val }}" {{ in_array($val, $eatFacilities) ? 'checked' : '' }} class="size-4 rounded border-slate-300 text-primary focus:ring-primary">
                                            <span class="material-symbols-outlined text-slate-400 text-[18px]">{{ $icon }}</span>
                                            <span class="text-xs font-bold text-slate-700">{{ $lbl }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Dietary Options --}}
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Dietary Accommodations</p>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    @foreach(['veg_options'=>['eco','Veg Options'],'vegan_options'=>['spa','Vegan Options'],'jain_options'=>['self_improvement','Jain Options'],'halal_options'=>['verified','Halal Options'],'gluten_free'=>['grain','Gluten Free'],'nut_free'=>['no_food','Nut Free']] as $val => [$icon, $lbl])
                                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50/50 cursor-pointer hover:border-primary/40 hover:bg-white has-[:checked]:bg-primary/5 has-[:checked]:border-primary transition-all">
                                            <input type="checkbox" name="attributes[dietary_options][]" value="{{ $val }}" {{ in_array($val, $eatDietary) ? 'checked' : '' }} class="size-4 rounded border-slate-300 text-primary focus:ring-primary">
                                            <span class="material-symbols-outlined text-slate-400 text-[18px]">{{ $icon }}</span>
                                            <span class="text-xs font-bold text-slate-700">{{ $lbl }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Payment & Closed On row --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-7">
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Payment Methods</p>
                                    <div class="grid grid-cols-2 gap-3">
                                        @foreach(['cash'=>['payments','Cash'],'card'=>['credit_card','Card'],'upi'=>['qr_code_2','UPI'],'online'=>['smartphone','Online Payment']] as $val => [$icon, $lbl])
                                            <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50/50 cursor-pointer hover:border-primary/40 hover:bg-white has-[:checked]:bg-primary/5 has-[:checked]:border-primary transition-all">
                                                <input type="checkbox" name="attributes[payment_methods][]" value="{{ $val }}" {{ in_array($val, $eatPayment) ? 'checked' : '' }} class="size-4 rounded border-slate-300 text-primary focus:ring-primary">
                                                <span class="material-symbols-outlined text-slate-400 text-[18px]">{{ $icon }}</span>
                                                <span class="text-xs font-bold text-slate-700">{{ $lbl }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Closed On</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(['mon'=>'Mon','tue'=>'Tue','wed'=>'Wed','thu'=>'Thu','fri'=>'Fri','sat'=>'Sat','sun'=>'Sun'] as $val => $lbl)
                                            <label class="flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 bg-slate-50/50 cursor-pointer hover:border-red-300 hover:bg-red-50/50 has-[:checked]:bg-red-50 has-[:checked]:border-red-400 transition-all">
                                                <input type="checkbox" name="attributes[closed_on][]" value="{{ $val }}" {{ in_array($val, $eatClosedOn) ? 'checked' : '' }} class="size-4 rounded border-slate-300 text-red-500 focus:ring-red-400">
                                                <span class="text-xs font-bold text-slate-600">{{ $lbl }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Specialty & Vibe --}}
                            @php
                                $eatSpecialty = explode(',', $listing->listingAttributes->where('attribute_key', 'specialty')->first()?->attribute_value ?? '');
                                $eatVibe      = explode(',', $listing->listingAttributes->where('attribute_key', 'dining_vibe')->first()?->attribute_value ?? '');
                            @endphp
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-7">
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Specialty</p>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach(['fish_thali'=>'Fish Thali','a_la_carte'=>'A La Carte','seafood_platter'=>'Seafood Platter','malvani_thali'=>'Malvani Thali','farm_dining'=>'Farm Dining','tasting_menu'=>'Tasting Menu','beach_shack'=>'Beach Shack','khanawal'=>'Khanawal'] as $val => $lbl)
                                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-slate-50/50 cursor-pointer hover:border-primary/40 hover:bg-white has-[:checked]:bg-primary/5 has-[:checked]:border-primary transition-all">
                                                <input type="checkbox" name="attributes[specialty][]" value="{{ $val }}" {{ in_array($val, $eatSpecialty) ? 'checked' : '' }} class="size-4 rounded border-slate-300 text-primary focus:ring-primary">
                                                <span class="text-xs font-bold text-slate-700">{{ $lbl }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Vibe / Ambiance</p>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach(['romantic'=>'Romantic','family_casual'=>'Family Casual','beachfront'=>'Beachfront','garden'=>'Garden / Outdoor','heritage'=>'Heritage','lively_bar'=>'Lively Bar','bohemian'=>'Bohemian','fine_dining'=>'Fine Dining'] as $val => $lbl)
                                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-slate-50/50 cursor-pointer hover:border-primary/40 hover:bg-white has-[:checked]:bg-primary/5 has-[:checked]:border-primary transition-all">
                                                <input type="checkbox" name="attributes[dining_vibe][]" value="{{ $val }}" {{ in_array($val, $eatVibe) ? 'checked' : '' }} class="size-4 rounded border-slate-300 text-primary focus:ring-primary">
                                                <span class="text-xs font-bold text-slate-700">{{ $lbl }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Must-Try Dishes & Instagram --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Must-Try Dishes <span class="text-slate-400 font-normal text-xs">(comma-separated)</span></label>
                                    <input type="text" name="attributes[must_try_dishes]" value="{{ $listing->listingAttributes->where('attribute_key', 'must_try_dishes')->first()?->attribute_value }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 px-4 bg-slate-50/50" placeholder="e.g., Butter Garlic Prawns, Malvani Thali">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Instagram Handle <span class="text-slate-400 font-normal text-xs">(optional)</span></label>
                                    <input type="text" name="attributes[instagram_handle]" value="{{ $listing->listingAttributes->where('attribute_key', 'instagram_handle')->first()?->attribute_value }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 px-4 bg-slate-50/50" placeholder="@yourrestaurant">
                                </div>
                            </div>

                            {{-- Available On Platforms --}}
                            @php
                                $availablePlatforms = array_filter(explode(',', $listing->listingAttributes->where('attribute_key', 'available_on')->first()?->attribute_value ?? ''));
                                $platformDefs = [
                                    'zomato'   => ['label'=>'Zomato',    'color'=>'#E23744', 'bg'=>'#FEF2F2', 'border'=>'#FECACA', 'placeholder'=>'https://zomato.com/alibaug/...', 'attr'=>'zomato_url',   'desc'=>'Menu, reviews & ordering'],
                                    'swiggy'   => ['label'=>'Swiggy',    'color'=>'#FC8019', 'bg'=>'#FFF7ED', 'border'=>'#FED7AA', 'placeholder'=>'https://swiggy.com/city/...', 'attr'=>'swiggy_url',   'desc'=>'Food delivery'],
                                    'magicpin' => ['label'=>'Magicpin',  'color'=>'#E91E8C', 'bg'=>'#FDF2F8', 'border'=>'#FBCFE8', 'placeholder'=>'https://magicpin.in/...',     'attr'=>'magicpin_url', 'desc'=>'Offers & discovery'],
                                    'dineout'  => ['label'=>'Dineout',   'color'=>'#C0392B', 'bg'=>'#FFF5F5', 'border'=>'#FEB2B2', 'placeholder'=>'https://www.dineout.co.in/...','attr'=>'dineout_url',  'desc'=>'Table reservations'],
                                    'eazydiner'=> ['label'=>'EazyDiner', 'color'=>'#FF5A5F', 'bg'=>'#FFF5F5', 'border'=>'#FEB2B2', 'placeholder'=>'https://www.eazydiner.com/...','attr'=>'eazydiner_url','desc'=>'Reserve & earn'],
                                    'google'   => ['label'=>'Google',    'color'=>'#4285F4', 'bg'=>'#EFF6FF', 'border'=>'#BFDBFE', 'placeholder'=>'https://maps.app.goo.gl/...',  'attr'=>'google_url',   'desc'=>'Google Maps & Search'],
                                ];
                            @endphp
                            <div x-data="{
                                selected: {{ json_encode($availablePlatforms) }},
                                toggle(val) {
                                    const i = this.selected.indexOf(val);
                                    if (i >= 0) this.selected.splice(i, 1);
                                    else this.selected.push(val);
                                },
                                has(val) { return this.selected.includes(val); }
                            }">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Available On <span class="normal-case font-normal text-slate-400 ml-1">— select where your restaurant is listed</span></p>

                                {{-- Hidden input for available_on --}}
                                <input type="hidden" name="attributes[available_on]" :value="selected.join(',')">

                                {{-- Platform Cards --}}
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-4">
                                    @foreach($platformDefs as $key => $p)
                                        <button type="button" @click="toggle('{{ $key }}')"
                                            class="relative flex items-center gap-2.5 p-3 rounded-xl border-2 transition-all text-left"
                                            :class="has('{{ $key }}') ? 'border-[{{ $p['color'] }}] bg-[{{ $p['bg'] }}]' : 'border-slate-200 bg-white hover:border-slate-300'">
                                            {{-- Checkmark --}}
                                            <div class="absolute top-2 right-2 w-4 h-4 rounded-full flex items-center justify-center transition-all"
                                                 :class="has('{{ $key }}') ? 'bg-[{{ $p['color'] }}]' : 'bg-slate-100'">
                                                <svg x-show="has('{{ $key }}')" class="w-2.5 h-2.5 text-white" viewBox="0 0 10 8" fill="none">
                                                    <path d="M1 4l2.5 2.5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-black flex-shrink-0"
                                                 style="background-color: {{ $p['color'] }}">
                                                {{ strtoupper(substr($p['label'], 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-slate-800 leading-none mb-0.5">{{ $p['label'] }}</p>
                                                <p class="text-[10px] text-slate-400 leading-tight">{{ $p['desc'] }}</p>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>

                                {{-- URL Inputs (shown when platform selected) --}}
                                @foreach($platformDefs as $key => $p)
                                    <div x-show="has('{{ $key }}')" x-cloak class="mb-3">
                                        <label class="flex items-center gap-2 text-sm font-bold text-slate-700 mb-1.5">
                                            <span class="w-5 h-5 rounded flex items-center justify-center text-white text-[10px] font-black flex-shrink-0"
                                                  style="background-color: {{ $p['color'] }}">{{ strtoupper(substr($p['label'], 0, 1)) }}</span>
                                            {{ $p['label'] }} URL
                                            <span class="text-slate-400 font-normal text-xs">(optional — paste your listing page link)</span>
                                        </label>
                                        <input type="url" name="attributes[{{ $p['attr'] }}]"
                                               value="{{ $listing->listingAttributes->where('attribute_key', $p['attr'])->first()?->attribute_value }}"
                                               class="w-full rounded-xl border-slate-200 text-sm focus:ring-2 py-3 px-4 bg-slate-50/50 transition-all"
                                               style="--tw-ring-color: {{ $p['color'] }}40; border-color: {{ $p['color'] }}40;"
                                               placeholder="{{ $p['placeholder'] }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        {{-- EVENTS --}}
                        <div x-show="categorySlug === 'events'" class="grid grid-cols-2 sm:grid-cols-3 gap-5">
                            @php
                                $evtType   = $listing->listingAttributes->where('attribute_key', 'event_type')->first()?->attribute_value;
                                $venuType  = $listing->listingAttributes->where('attribute_key', 'venue_type')->first()?->attribute_value;
                                $catering  = $listing->listingAttributes->where('attribute_key', 'catering')->first()?->attribute_value;
                            @endphp
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Event Type</label>
                                <select name="attributes[event_type]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                                    <option value="">Select</option>
                                    @foreach(['wedding'=>'Wedding Venue','corporate'=>'Corporate Events','party'=>'Party / Celebration','workshop'=>'Workshop / Retreat'] as $v => $l)
                                        <option value="{{ $v }}" {{ $evtType === $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Max Capacity</label>
                                <input type="number" name="attributes[max_capacity]" value="{{ $listing->listingAttributes->where('attribute_key', 'max_capacity')->first()?->attribute_value }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50" placeholder="e.g., 200">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Venue Type</label>
                                <select name="attributes[venue_type]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                                    <option value="">Select</option>
                                    @foreach(['indoor'=>'Indoor','outdoor'=>'Outdoor','both'=>'Indoor & Outdoor'] as $v => $l)
                                        <option value="{{ $v }}" {{ $venuType === $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Catering</label>
                                <select name="attributes[catering]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                                    <option value="">Select</option>
                                    @foreach(['yes'=>'Yes, In-house','external'=>'External Allowed','no'=>'Not Available'] as $v => $l)
                                        <option value="{{ $v }}" {{ $catering === $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- EXPLORE --}}
                        <div x-show="categorySlug === 'explore'" class="grid grid-cols-2 sm:grid-cols-3 gap-5">
                            @php
                                $actType    = $listing->listingAttributes->where('attribute_key', 'activity_type')->first()?->attribute_value;
                                $difficulty = $listing->listingAttributes->where('attribute_key', 'difficulty')->first()?->attribute_value;
                            @endphp
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Activity Type</label>
                                <select name="attributes[activity_type]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                                    <option value="">Select</option>
                                    @foreach(['water_sports'=>'Water Sports','trekking'=>'Trekking / Hiking','heritage'=>'Heritage / Fort Visits','beach'=>'Beach Activities','yoga'=>'Yoga / Wellness'] as $v => $l)
                                        <option value="{{ $v }}" {{ $actType === $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Duration</label>
                                <input type="text" name="attributes[duration]" value="{{ $listing->listingAttributes->where('attribute_key', 'duration')->first()?->attribute_value }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50" placeholder="e.g., 2 hours">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Difficulty</label>
                                <select name="attributes[difficulty]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                                    <option value="">Select</option>
                                    @foreach(['easy'=>'Easy — All ages','moderate'=>'Moderate','hard'=>'Challenging'] as $v => $l)
                                        <option value="{{ $v }}" {{ $difficulty === $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Group Size</label>
                                <input type="text" name="attributes[group_size]" value="{{ $listing->listingAttributes->where('attribute_key', 'group_size')->first()?->attribute_value }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50" placeholder="e.g., 2–15 people">
                            </div>
                        </div>

                        {{-- SERVICES --}}
                        <div x-show="categorySlug === 'services'" class="grid grid-cols-2 sm:grid-cols-3 gap-5">
                            @php
                                $svcType  = $listing->listingAttributes->where('attribute_key', 'service_type')->first()?->attribute_value;
                                $avail    = $listing->listingAttributes->where('attribute_key', 'availability')->first()?->attribute_value;
                            @endphp
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Service Type</label>
                                <select name="attributes[service_type]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                                    <option value="">Select</option>
                                    @foreach(['transport'=>'Transport / Ferry','chef'=>'Private Chef','cleaning'=>'Housekeeping','photography'=>'Photography','tour_guide'=>'Tour Guide','spa'=>'Spa / Massage'] as $v => $l)
                                        <option value="{{ $v }}" {{ $svcType === $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Availability</label>
                                <select name="attributes[availability]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                                    <option value="">Select</option>
                                    @foreach(['daily'=>'Daily','weekends'=>'Weekends Only','on_demand'=>'On Demand'] as $v => $l)
                                        <option value="{{ $v }}" {{ $avail === $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Experience</label>
                                <input type="text" name="attributes[experience]" value="{{ $listing->listingAttributes->where('attribute_key', 'experience')->first()?->attribute_value }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50" placeholder="e.g., 5+ years">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Coverage Area</label>
                                <input type="text" name="attributes[coverage_area]" value="{{ $listing->listingAttributes->where('attribute_key', 'coverage_area')->first()?->attribute_value }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50" placeholder="e.g., All of Alibaug">
                            </div>
                        </div>

                        {{-- REAL ESTATE --}}
                        <div x-show="categorySlug === 'real-estate'" class="grid grid-cols-2 sm:grid-cols-3 gap-5">
                            @php
                                $lstType    = $listing->listingAttributes->where('attribute_key', 'listing_type')->first()?->attribute_value;
                                $rePropType = $listing->listingAttributes->where('attribute_key', 're_property_type')->first()?->attribute_value;
                                $facing     = $listing->listingAttributes->where('attribute_key', 'facing')->first()?->attribute_value;
                                $constStat  = $listing->listingAttributes->where('attribute_key', 'construction_status')->first()?->attribute_value;
                            @endphp
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Listing Type</label>
                                <select name="attributes[listing_type]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                                    <option value="">Select</option>
                                    @foreach(['sale'=>'For Sale','rent'=>'For Rent','lease'=>'Long Lease'] as $v => $l)
                                        <option value="{{ $v }}" {{ $lstType === $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Property Type</label>
                                <select name="attributes[re_property_type]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                                    <option value="">Select</option>
                                    @foreach(['plot'=>'Plot / Land','villa'=>'Villa','apartment'=>'Apartment','farmhouse'=>'Farmhouse','commercial'=>'Commercial'] as $v => $l)
                                        <option value="{{ $v }}" {{ $rePropType === $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Area (sq ft)</label>
                                <input type="number" name="attributes[area_sqft]" value="{{ $listing->listingAttributes->where('attribute_key', 'area_sqft')->first()?->attribute_value }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50" placeholder="e.g., 2500">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Facing</label>
                                <select name="attributes[facing]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                                    <option value="">Select</option>
                                    @foreach(['east'=>'East','west'=>'West','north'=>'North','south'=>'South','sea_facing'=>'Sea Facing'] as $v => $l)
                                        <option value="{{ $v }}" {{ $facing === $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Construction Status</label>
                                <select name="attributes[construction_status]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50">
                                    <option value="">Select</option>
                                    @foreach(['ready'=>'Ready to Move','under_construction'=>'Under Construction','new_launch'=>'New Launch','plot_only'=>'Plot Only'] as $v => $l)
                                        <option value="{{ $v }}" {{ $constStat === $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">RERA Number <span class="text-slate-400 font-normal text-xs">(optional)</span></label>
                                <input type="text" name="attributes[rera_number]" value="{{ $listing->listingAttributes->where('attribute_key', 'rera_number')->first()?->attribute_value }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3 bg-slate-50/50" placeholder="Optional">
                            </div>
                        </div>

                        {{-- Generic Fallback --}}
                        <div x-show="!['stay', 'eat', 'events', 'explore', 'services', 'real-estate'].includes(categorySlug)">
                            <p class="text-sm text-slate-500 italic">No specific details required for this category.</p>
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

                        @if($tags->isNotEmpty())
                        <div class="mt-8 pt-8 border-t border-slate-100">
                            <h2 class="text-xl font-bold text-slate-900 mb-2 flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">label</span> Best For — Smart Tags
                            </h2>
                            <p class="text-sm text-slate-500 mb-4">Tag your listing to help visitors find it more easily.</p>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($tags as $tag)
                                    <label class="flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 cursor-pointer hover:border-primary/40 hover:bg-white has-[:checked]:bg-primary/5 has-[:checked]:border-primary transition-all">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $listing->tags->pluck('id')->toArray() ?? [])) ? 'checked' : '' }} class="peer size-5 rounded border-slate-300 text-primary shadow-sm focus:ring-primary focus:ring-offset-0">
                                        </div>
                                        @if($tag->icon)
                                            <span class="material-symbols-outlined text-slate-400 text-[20px] peer-checked:text-primary transition-colors">{{ $tag->icon }}</span>
                                        @endif
                                        <span class="text-sm text-slate-700 font-bold select-none">{{ $tag->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @endif
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

                    {{-- Existing Gallery Photos --}}
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-5">
                            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">grid_view</span>
                                Gallery Photos ({{ $galleryImages->count() }})
                            </h2>
                            @if($galleryImages->count() <= 1)
                                <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-md border border-amber-200">Min 1 photo required</span>
                            @endif
                        </div>
                        @if($galleryImages->count() > 0)
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($galleryImages as $image)
                                    <div class="relative group rounded-xl overflow-hidden border border-slate-200 aspect-[4/3] bg-slate-100">
                                        <img src="{{ $image->url }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        @if($image->is_primary)
                                            <div class="absolute top-2 left-2 bg-primary text-white text-[10px] font-bold px-2 py-1 rounded-md shadow-sm">Cover</div>
                                        @endif
                                        <button type="button"
                                                @click="if(confirm('Delete this photo?')) { document.getElementById('delete-img-{{ $image->id }}').submit(); }"
                                                class="absolute bottom-3 right-3 w-8 h-8 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center shadow-lg transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-10 bg-slate-50 border border-slate-200 border-dashed rounded-xl">
                                <span class="material-symbols-outlined text-slate-300 text-4xl mb-2">imagesmode</span>
                                <p class="text-slate-500 font-medium">No gallery photos uploaded yet.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Menu / Food Photos — eat category only --}}
                    @if($listing->category->slug === 'eat')
                        {{-- Upload Menu Photos --}}
                        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm" x-data="{
                            selectedMenuFiles: [],
                            handleMenuSelect(e) {
                                this.selectedMenuFiles = [];
                                const files = e.target.files;
                                for (let i = 0; i < files.length; i++) {
                                    this.selectedMenuFiles.push({ name: files[i].name, url: URL.createObjectURL(files[i]) });
                                }
                            }
                        }">
                            <h2 class="text-xl font-bold text-slate-900 mb-1 flex items-center gap-2">
                                <span class="material-symbols-outlined text-amber-500">menu_book</span>
                                Menu &amp; Food Photos
                            </h2>
                            <p class="text-sm text-slate-500 mb-5">Upload your menu cards, dish photos, or price list images. Shown separately on your listing page.</p>

                            <div class="border-2 border-dashed rounded-xl p-7 transition-all group relative cursor-pointer text-center"
                                 :class="selectedMenuFiles.length > 0 ? 'border-amber-400 bg-amber-50/30' : 'border-slate-300 hover:border-amber-400/60 hover:bg-amber-50/20'">
                                <input type="file" name="menu_images[]" id="menu_images" multiple accept="image/*" @change="handleMenuSelect($event)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div x-show="selectedMenuFiles.length === 0" class="flex flex-col items-center justify-center space-y-3 pointer-events-none">
                                    <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center text-amber-500">
                                        <span class="material-symbols-outlined text-2xl">restaurant_menu</span>
                                    </div>
                                    <div>
                                        <p class="text-slate-700 font-bold text-sm mb-1">Upload menu cards or food photos</p>
                                        <p class="text-slate-400 text-xs">JPG, PNG up to 5MB each</p>
                                    </div>
                                </div>
                                <div x-show="selectedMenuFiles.length > 0" class="pointer-events-none">
                                    <div class="flex items-center justify-center gap-2 text-amber-600 mb-4">
                                        <span class="material-symbols-outlined text-[20px]">check_circle</span>
                                        <span class="font-bold text-sm" x-text="selectedMenuFiles.length + ' menu photo(s) selected'"></span>
                                    </div>
                                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-3">
                                        <template x-for="(file, i) in selectedMenuFiles" :key="i">
                                            <div class="relative rounded-lg overflow-hidden border border-amber-200 aspect-square bg-white">
                                                <img :src="file.url" class="w-full h-full object-cover">
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Existing Menu Photos --}}
                        @if($menuImages->count() > 0)
                            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                                <h2 class="text-xl font-bold text-slate-900 mb-5 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-amber-500">menu_book</span>
                                    Existing Menu Photos ({{ $menuImages->count() }})
                                </h2>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach($menuImages as $image)
                                        <div class="relative group rounded-xl overflow-hidden border border-amber-100 aspect-[3/4] bg-slate-100">
                                            <img src="{{ $image->url }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                            <div class="absolute top-2 left-2 bg-amber-500 text-white text-[10px] font-bold px-2 py-1 rounded-md shadow-sm">Menu</div>
                                            <button type="button"
                                                    @click="if(confirm('Delete this menu photo?')) { document.getElementById('delete-img-{{ $image->id }}').submit(); }"
                                                    class="absolute bottom-3 right-3 w-8 h-8 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center shadow-lg transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif

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
