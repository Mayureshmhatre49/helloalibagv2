{{--
  Shared create/edit form for a marketplace item.
  Expects: $categories, $areas, optional $classified (edit mode).
--}}
@php $editing = isset($classified); @endphp

<div class="grid sm:grid-cols-2 gap-5">
    <div class="sm:col-span-2">
        <label class="block text-sm font-bold text-text-main mb-1.5">Title <span class="text-red-500">*</span></label>
        <input type="text" name="title" required maxlength="255"
               value="{{ old('title', $editing ? $classified->title : '') }}"
               placeholder="e.g. Teak wood 6-seater dining table"
               class="w-full border border-border-light rounded-xl px-4 py-2.5 text-sm focus:border-primary focus:ring-primary">
        @error('title')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-bold text-text-main mb-1.5">Category <span class="text-red-500">*</span></label>
        <select name="classified_category_id" required class="w-full border border-border-light rounded-xl px-4 py-2.5 text-sm bg-white focus:border-primary focus:ring-primary">
            <option value="">Select category</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ (string) old('classified_category_id', $editing ? $classified->classified_category_id : '') === (string) $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        @error('classified_category_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-bold text-text-main mb-1.5">Area</label>
        <select name="area_id" class="w-full border border-border-light rounded-xl px-4 py-2.5 text-sm bg-white focus:border-primary focus:ring-primary">
            <option value="">Select area</option>
            @foreach($areas as $area)
                <option value="{{ $area->id }}" {{ (string) old('area_id', $editing ? $classified->area_id : '') === (string) $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-bold text-text-main mb-1.5">Price (₹)</label>
        <input type="number" name="price" min="0" step="1"
               value="{{ old('price', $editing ? ($classified->price ? (int) $classified->price : '') : '') }}"
               placeholder="Leave blank for 'Contact for price'"
               class="w-full border border-border-light rounded-xl px-4 py-2.5 text-sm focus:border-primary focus:ring-primary">
        <label class="flex items-center gap-2 mt-2 text-sm text-text-secondary cursor-pointer">
            <input type="checkbox" name="is_negotiable" value="1" class="rounded text-primary focus:ring-primary/20"
                   {{ old('is_negotiable', $editing ? $classified->is_negotiable : false) ? 'checked' : '' }}>
            Price is negotiable
        </label>
    </div>

    <div>
        <label class="block text-sm font-bold text-text-main mb-1.5">Condition</label>
        <select name="condition" class="w-full border border-border-light rounded-xl px-4 py-2.5 text-sm bg-white focus:border-primary focus:ring-primary">
            <option value="">Not specified</option>
            @foreach(\App\Models\Classified::CONDITIONS as $key => $label)
                <option value="{{ $key }}" {{ old('condition', $editing ? $classified->condition : '') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="sm:col-span-2">
        <label class="block text-sm font-bold text-text-main mb-1.5">Description</label>
        <textarea name="description" rows="5" maxlength="5000"
                  placeholder="Describe the item, age, reason for selling, any defects…"
                  class="w-full border border-border-light rounded-xl px-4 py-2.5 text-sm focus:border-primary focus:ring-primary">{{ old('description', $editing ? $classified->description : '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-bold text-text-main mb-1.5">Contact phone</label>
        <input type="tel" name="contact_phone" maxlength="15" inputmode="numeric"
               pattern="[0-9]{7,15}" oninput="this.value = this.value.replace(/\D/g, '')"
               placeholder="10-digit mobile number"
               value="{{ old('contact_phone', $editing ? $classified->contact_phone : (auth()->user()->phone ?? '')) }}"
               class="w-full border border-border-light rounded-xl px-4 py-2.5 text-sm focus:border-primary focus:ring-primary">
        @error('contact_phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-bold text-text-main mb-1.5">WhatsApp number</label>
        <input type="tel" name="contact_whatsapp" maxlength="15" inputmode="numeric"
               pattern="[0-9]{7,15}" oninput="this.value = this.value.replace(/\D/g, '')"
               placeholder="Digits only"
               value="{{ old('contact_whatsapp', $editing ? $classified->contact_whatsapp : '') }}"
               class="w-full border border-border-light rounded-xl px-4 py-2.5 text-sm focus:border-primary focus:ring-primary">
        @error('contact_whatsapp')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Existing images (edit mode) --}}
    @if($editing && $classified->images->isNotEmpty())
        <div class="sm:col-span-2">
            <label class="block text-sm font-bold text-text-main mb-1.5">Current photos</label>
            <div class="flex flex-wrap gap-3">
                @foreach($classified->images as $img)
                    <div class="relative w-24 h-20 rounded-lg overflow-hidden border border-border-light">
                        <img src="{{ $img->url }}" alt="" class="w-full h-full object-cover">
                        <button form="delete-img-{{ $img->id }}" class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600">
                            <span class="material-symbols-outlined text-[14px]">close</span>
                        </button>
                    </div>
                    <form id="delete-img-{{ $img->id }}" method="POST" action="{{ route('marketplace.images.destroy', $img) }}" class="hidden">
                        @csrf @method('DELETE')
                    </form>
                @endforeach
            </div>
        </div>
    @endif

    <div class="sm:col-span-2">
        <label class="block text-sm font-bold text-text-main mb-1.5">{{ $editing ? 'Add more photos' : 'Photos' }} <span class="text-text-secondary font-normal">(up to 8, first is the cover)</span></label>
        <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/webp"
               class="w-full text-sm text-text-secondary file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-primary/10 file:text-primary file:font-semibold file:text-sm hover:file:bg-primary/20">
        @error('images.*')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>
</div>
