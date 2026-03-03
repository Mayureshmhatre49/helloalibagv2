@extends('layouts.dashboard')
@section('page-title', 'Edit Listing')

@section('content')
<div class="max-w-3xl" x-data="{
    selectedCategory: '{{ old('category_id', $listing->category_id) }}',
    categorySlug: '',
    priceLabel: 'Price (₹)',
    categoryMap: { @foreach($categories as $cat)'{{ $cat->id }}': '{{ $cat->slug }}',@endforeach },
    priceLabels: { 'stay': 'Price per Night (₹)', 'eat': 'Average Cost for 2 (₹)', 'events': 'Starting Price (₹)', 'explore': 'Price per Person (₹)', 'services': 'Service Charge (₹)', 'real-estate': 'Price (₹)' },
    init() { if (this.selectedCategory) { this.onCategoryChange(); } },
    onCategoryChange() { this.categorySlug = this.categoryMap[this.selectedCategory] || ''; this.priceLabel = this.priceLabels[this.categorySlug] || 'Price (₹)'; }
}">
    <form action="{{ route('owner.listings.update', $listing->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Basic Info --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Basic Information</h2>
            <div class="space-y-4">
                <div>
                    <label for="title" class="block text-sm font-bold text-slate-700 mb-1.5">Listing Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $listing->title) }}" required class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3" placeholder="e.g., Luxury Beachfront Villa with Pool">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="category_id" class="block text-sm font-bold text-slate-700 mb-1.5">Category *</label>
                        <select name="category_id" id="category_id" required x-model="selectedCategory" @change="onCategoryChange()" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $listing->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="area_id" class="block text-sm font-bold text-slate-700 mb-1.5">Area</label>
                        <select name="area_id" id="area_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3">
                            <option value="">Select Area</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}" {{ old('area_id', $listing->area_id) == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-bold text-slate-700 mb-1.5">Description</label>
                    <textarea name="description" id="description" rows="4" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary" placeholder="Describe your listing in detail...">{{ old('description', $listing->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="price" class="block text-sm font-bold text-slate-700 mb-1.5">
                        <span x-text="priceLabel">Price (₹)</span>
                    </label>
                    <input type="number" name="price" id="price" value="{{ old('price', $listing->price) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3" placeholder="e.g., 25000" step="0.01">
                </div>
            </div>
        </div>

        {{-- Dynamic Fields per Category --}}
        {{-- STAY --}}
        <div x-show="categorySlug === 'stay'" x-transition class="bg-white rounded-2xl border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">
                <span class="material-symbols-outlined align-middle text-primary mr-1">villa</span> Stay Details
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Bedrooms</label>
                    <input type="number" name="attributes[bedrooms]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3" placeholder="0" min="0">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Bathrooms</label>
                    <input type="number" name="attributes[bathrooms]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3" placeholder="0" min="0">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Max Guests</label>
                    <input type="number" name="attributes[max_guests]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3" placeholder="0" min="1">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Property Type</label>
                    <select name="attributes[property_type]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3">
                        <option value="">Select</option>
                        <option value="villa">Villa</option>
                        <option value="apartment">Apartment</option>
                        <option value="cottage">Cottage</option>
                        <option value="bungalow">Bungalow</option>
                        <option value="resort">Resort</option>
                        <option value="homestay">Homestay</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Check-in Time</label>
                    <input type="time" name="attributes[check_in]" value="14:00" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Check-out Time</label>
                    <input type="time" name="attributes[check_out]" value="11:00" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3">
                </div>
            </div>
        </div>

        {{-- EAT --}}
        <div x-show="categorySlug === 'eat'" x-transition class="bg-white rounded-2xl border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">
                <span class="material-symbols-outlined align-middle text-rose-500 mr-1">restaurant_menu</span> Restaurant Details
            </h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Cuisine Type</label>
                    <select name="attributes[cuisine]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3">
                        <option value="">Select</option>
                        <option value="indian">Indian</option>
                        <option value="coastal">Coastal / Konkan</option>
                        <option value="continental">Continental</option>
                        <option value="chinese">Chinese / Asian</option>
                        <option value="multi-cuisine">Multi-Cuisine</option>
                        <option value="cafe">Café / Bakery</option>
                        <option value="seafood">Seafood</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Average Cost (for 2)</label>
                    <input type="number" name="attributes[avg_cost_for_two]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3" placeholder="₹ 800">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Food Type</label>
                    <select name="attributes[food_type]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3">
                        <option value="">Select</option>
                        <option value="veg">Pure Vegetarian</option>
                        <option value="non-veg">Non-Vegetarian</option>
                        <option value="both">Veg & Non-Veg</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Seating Capacity</label>
                    <input type="number" name="attributes[seating_capacity]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3" placeholder="50">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Opens At</label>
                    <input type="time" name="attributes[opens_at]" value="10:00" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Closes At</label>
                    <input type="time" name="attributes[closes_at]" value="23:00" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3">
                </div>
            </div>
        </div>

        {{-- EVENTS --}}
        <div x-show="categorySlug === 'events'" x-transition class="bg-white rounded-2xl border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">
                <span class="material-symbols-outlined align-middle text-purple-500 mr-1">celebration</span> Event Details
            </h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Event Type</label>
                    <select name="attributes[event_type]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3">
                        <option value="">Select</option>
                        <option value="wedding">Wedding Venue</option>
                        <option value="corporate">Corporate Events</option>
                        <option value="party">Party / Celebration</option>
                        <option value="workshop">Workshop / Retreat</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Max Capacity</label>
                    <input type="number" name="attributes[max_capacity]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3" placeholder="100">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Venue Type</label>
                    <select name="attributes[venue_type]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3">
                        <option value="">Select</option>
                        <option value="indoor">Indoor</option>
                        <option value="outdoor">Outdoor</option>
                        <option value="both">Indoor & Outdoor</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Catering</label>
                    <select name="attributes[catering]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3">
                        <option value="">Select</option>
                        <option value="yes">Yes, In-house</option>
                        <option value="external">External allowed</option>
                        <option value="no">Not available</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- EXPLORE --}}
        <div x-show="categorySlug === 'explore'" x-transition class="bg-white rounded-2xl border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">
                <span class="material-symbols-outlined align-middle text-green-600 mr-1">terrain</span> Activity Details
            </h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Activity Type</label>
                    <select name="attributes[activity_type]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3">
                        <option value="">Select</option>
                        <option value="water_sports">Water Sports</option>
                        <option value="trekking">Trekking / Hiking</option>
                        <option value="heritage">Heritage / Fort Visits</option>
                        <option value="beach">Beach Activities</option>
                        <option value="yoga">Yoga / Wellness</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Duration</label>
                    <input type="text" name="attributes[duration]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3" placeholder="e.g., 2 hours">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Difficulty</label>
                    <select name="attributes[difficulty]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3">
                        <option value="">Select</option>
                        <option value="easy">Easy — All ages</option>
                        <option value="moderate">Moderate</option>
                        <option value="hard">Challenging</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Group Size</label>
                    <input type="text" name="attributes[group_size]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3" placeholder="e.g., 2-15 people">
                </div>
            </div>
        </div>

        {{-- SERVICES --}}
        <div x-show="categorySlug === 'services'" x-transition class="bg-white rounded-2xl border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">
                <span class="material-symbols-outlined align-middle text-teal-500 mr-1">concierge</span> Service Details
            </h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Service Type</label>
                    <select name="attributes[service_type]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3">
                        <option value="">Select</option>
                        <option value="transport">Transport / Ferry</option>
                        <option value="chef">Private Chef</option>
                        <option value="cleaning">Housekeeping</option>
                        <option value="photography">Photography</option>
                        <option value="tour_guide">Tour Guide</option>
                        <option value="spa">Spa / Massage</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Availability</label>
                    <select name="attributes[availability]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3">
                        <option value="">Select</option>
                        <option value="daily">Daily</option>
                        <option value="weekends">Weekends Only</option>
                        <option value="on_demand">On Demand</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Experience</label>
                    <input type="text" name="attributes[experience]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3" placeholder="e.g., 5+ years">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Coverage Area</label>
                    <input type="text" name="attributes[coverage_area]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3" placeholder="e.g., All of Alibaug">
                </div>
            </div>
        </div>

        {{-- REAL ESTATE --}}
        <div x-show="categorySlug === 'real-estate'" x-transition class="bg-white rounded-2xl border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">
                <span class="material-symbols-outlined align-middle text-orange-500 mr-1">real_estate_agent</span> Property Details
            </h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Listing Type</label>
                    <select name="attributes[listing_type]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3">
                        <option value="">Select</option>
                        <option value="sale">For Sale</option>
                        <option value="rent">For Rent</option>
                        <option value="lease">Long Lease</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Property Type</label>
                    <select name="attributes[re_property_type]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3">
                        <option value="">Select</option>
                        <option value="plot">Plot / Land</option>
                        <option value="villa">Villa</option>
                        <option value="apartment">Apartment</option>
                        <option value="farmhouse">Farmhouse</option>
                        <option value="commercial">Commercial</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Area (sq ft)</label>
                    <input type="number" name="attributes[area_sqft]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3" placeholder="e.g., 2500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Facing</label>
                    <select name="attributes[facing]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3">
                        <option value="">Select</option>
                        <option value="east">East</option>
                        <option value="west">West</option>
                        <option value="north">North</option>
                        <option value="south">South</option>
                        <option value="sea_facing">Sea Facing</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Construction Status</label>
                    <select name="attributes[construction_status]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3">
                        <option value="">Select</option>
                        <option value="ready">Ready to Move</option>
                        <option value="under_construction">Under Construction</option>
                        <option value="new_launch">New Launch</option>
                        <option value="plot_only">Plot Only</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">RERA Number</label>
                    <input type="text" name="attributes[rera_number]" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3" placeholder="Optional">
                </div>
            </div>
        </div>

        {{-- Contact Info --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Contact Information</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $listing->phone) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3" placeholder="9876543210">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">WhatsApp</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $listing->whatsapp) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3" placeholder="9876543210">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $listing->email) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Address</label>
                    <input type="text" name="address" value="{{ old('address', $listing->address) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary py-3">
                </div>
            </div>
        </div>

        {{-- Amenities --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Amenities & Features</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($amenities as $amenity)
                    <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-primary/30 has-[:checked]:bg-primary/5 has-[:checked]:border-primary/30 transition-colors">
                        <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" {{ in_array($amenity->id, old('amenities', $listing->amenities->pluck('id')->toArray() ?? [])) ? 'checked' : '' }} class="rounded border-slate-300 text-primary focus:ring-primary">
                        <span class="material-symbols-outlined text-slate-500 text-[18px]">{{ $amenity->icon }}</span>
                        <span class="text-sm text-slate-700 font-medium">{{ $amenity->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Images --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Photos</h2>
            <div class="border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:border-primary/50 transition-colors">
                <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">cloud_upload</span>
                <p class="text-sm text-slate-500 mb-3">Upload listing images (max 5MB each, JPEG or PNG)</p>
                <input type="file" name="images[]" multiple accept="image/*" class="text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center gap-3">
            <button type="submit" class="bg-primary hover:bg-primary/90 text-white px-8 py-3.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">send</span>
                Submit for Approval
            </button>
            <a href="{{ route('owner.listings.index') }}" class="text-sm text-slate-500 hover:text-slate-700 font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
