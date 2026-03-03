@extends('layouts.app')

@section('title', 'Complete Your Listing')

@section('content')
<div class="min-h-screen bg-slate-50 py-10" x-data="wizard()">
    <div class="max-w-4xl mx-auto px-4 w-full">
        <!-- Progress Header -->
        <div class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <template x-for="(stepName, index) in stepNames" :key="index">
                    <div class="flex-1 flex items-center">
                        <div class="flex flex-col items-center flex-1 relative z-10 text-center">
                            <div class="size-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300"
                                 :class="step > index + 1 ? 'bg-primary text-white' : (step === index + 1 ? 'bg-primary text-white ring-4 ring-primary/20' : 'bg-slate-200 text-slate-500')">
                                <span x-show="step > index + 1" class="material-symbols-outlined text-[20px]">check</span>
                                <span x-show="step <= index + 1" x-text="index + 1"></span>
                            </div>
                            <span class="text-xs font-bold mt-3 transition-colors hidden sm:block" 
                                  :class="step >= index + 1 ? 'text-slate-900' : 'text-slate-400'" 
                                  x-text="stepName"></span>
                        </div>
                        <div class="h-1 flex-1 bg-slate-200 rounded-full overflow-hidden mx-[-1rem] relative z-0" x-show="index < stepNames.length - 1">
                            <div class="h-full bg-primary transition-all duration-500" :style="step > index + 1 ? 'width: 100%' : 'width: 0%'"></div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Form Content -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
            <form id="onboardingForm" action="{{ route('owner.onboarding.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="category_id" value="{{ $category->id }}">
                
                <div class="p-8 sm:p-12">
                    
                    <!-- STEP 1: Basic Info -->
                    <div x-show="step === 1" x-transition.opacity.duration.300ms data-step="1">
                        <div class="mb-8">
                            <h2 class="text-2xl font-serif font-bold text-slate-900 mb-2">Basic Information</h2>
                            <p class="text-slate-500">Let's start with the standard details about your {{ strtolower($category->name) }}.</p>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Listing Title <span class="text-red-500">*</span></label>
                                <input type="text" name="title" x-model="formData.title" required class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3" placeholder="e.g., Luxury Sea View Villa">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Area / Location <span class="text-red-500">*</span></label>
                                    <select name="area_id" x-model="formData.area_id" required class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3">
                                        <option value="">Select an Area</option>
                                        @foreach($areas as $area)
                                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Price (₹) <span class="text-slate-400 font-normal">(Optional)</span></label>
                                    <input type="number" name="price" x-model="formData.price" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3" placeholder="e.g., 5000">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Description <span class="text-red-500">*</span></label>
                                <textarea name="description" x-model="formData.description" required rows="5" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3" placeholder="Describe what makes your listing special..."></textarea>
                            </div>

                            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                                <h3 class="font-bold text-slate-900 mb-4">Contact Details</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Contact Name</label>
                                        <input type="text" value="{{ auth()->user()->name }}" readonly class="w-full bg-slate-100 rounded-xl border-transparent text-slate-500 py-3 cursor-not-allowed">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                                        <input type="email" name="email" x-model="formData.email" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Phone Number</label>
                                        <input type="tel" name="phone" x-model="formData.phone" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: Category Details -->
                    <div x-show="step === 2" x-transition.opacity.duration.300ms style="display: none;" data-step="2">
                        <div class="mb-8">
                            <h2 class="text-2xl font-serif font-bold text-slate-900 mb-2">Details for {{ $category->name }}</h2>
                            <p class="text-slate-500">Provide specific details to help visitors understand your offering.</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @forelse($category->attributes as $attribute)
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">
                                        {{ $attribute->name }} 
                                        {!! $attribute->is_required ? '<span class="text-red-500">*</span>' : '<span class="text-slate-400 font-normal">(Optional)</span>' !!}
                                    </label>
                                    
                                    @if($attribute->field_type === 'select' && $attribute->values->count() > 0)
                                        <select name="dynamic_attributes[{{ $attribute->id }}]" {{ $attribute->is_required ? 'required' : '' }} class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3">
                                            <option value="">Select {{ $attribute->name }}</option>
                                            @foreach($attribute->values as $val)
                                                <option value="{{ $val->value }}">{{ $val->label }}</option>
                                            @endforeach
                                        </select>
                                    @elseif($attribute->field_type === 'number')
                                        <input type="number" name="dynamic_attributes[{{ $attribute->id }}]" {{ $attribute->is_required ? 'required' : '' }} class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3" placeholder="Enter {{ strtolower($attribute->name) }}">
                                    @else
                                        <input type="text" name="dynamic_attributes[{{ $attribute->id }}]" {{ $attribute->is_required ? 'required' : '' }} class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3" placeholder="Enter {{ strtolower($attribute->name) }}">
                                    @endif
                                </div>
                            @empty
                                <div class="col-span-2 text-center py-8">
                                    <p class="text-slate-500">No specific attributes required for this category. You can proceed to the next step.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- STEP 3: Amenities -->
                    <div x-show="step === 3" x-transition.opacity.duration.300ms style="display: none;" data-step="3">
                        <div class="mb-8">
                            <h2 class="text-2xl font-serif font-bold text-slate-900 mb-2">Amenities & Features</h2>
                            <p class="text-slate-500">Select all that apply to your listing.</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @forelse($amenities as $amenity)
                                <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 cursor-pointer hover:border-primary/50 transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                    <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" class="rounded text-primary focus:ring-primary size-5 border-slate-300">
                                    <span class="material-symbols-outlined text-slate-500 text-[20px]">{{ $amenity->icon }}</span>
                                    <span class="font-medium text-slate-800 text-sm">{{ $amenity->name }}</span>
                                </label>
                            @empty
                                <p class="col-span-full text-slate-500 text-center py-8">No specific amenities configured yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- STEP 4: Photos -->
                    <div x-show="step === 4" x-transition.opacity.duration.300ms style="display: none;" data-step="4">
                        <div class="mb-8">
                            <h2 class="text-2xl font-serif font-bold text-slate-900 mb-2">Photos</h2>
                            <p class="text-slate-500">Upload at least 3 high-quality (JPEG or PNG) photos. The first photo will be your cover image.</p>
                        </div>
                        
                        <div class="border-2 border-dashed border-slate-300 rounded-3xl p-10 text-center hover:border-primary/50 transition-colors"
                             :class="imagesCount >= 3 ? 'border-green-300 bg-green-50' : 'bg-slate-50'">
                            <span class="material-symbols-outlined text-5xl mb-4" :class="imagesCount >= 3 ? 'text-green-500' : 'text-slate-400'">
                                <span x-text="imagesCount >= 3 ? 'check_circle' : 'cloud_upload'"></span>
                            </span>
                            <h3 class="font-bold text-slate-800 text-lg mb-2">Select Images</h3>
                            <p class="text-sm text-slate-500 mb-6">Select multiple files from your device. Minimum 3 required.</p>
                            
                            <input type="file" name="images[]" id="imageInput" multiple accept="image/jpeg,image/png,image/webp" required
                                   @change="updateImageCount"
                                   class="hidden">
                            
                            <label for="imageInput" class="inline-flex items-center justify-center bg-white border border-slate-200 text-slate-700 px-6 py-3 rounded-full font-bold shadow-sm cursor-pointer hover:bg-slate-50 transition-colors">
                                Browse Files
                            </label>
                            
                            <p class="mt-4 font-bold" :class="imagesCount >= 3 ? 'text-green-600' : 'text-primary'" x-show="imagesCount > 0">
                                <span x-text="imagesCount"></span> files selected
                            </p>
                        </div>
                    </div>

                    <!-- STEP 5: SEO -->
                    <div x-show="step === 5" x-transition.opacity.duration.300ms style="display: none;" data-step="5">
                        <div class="mb-8">
                            <h2 class="text-2xl font-serif font-bold text-slate-900 mb-2">Search Optimization</h2>
                            <p class="text-slate-500">How your listing will appear on Google and other search engines.</p>
                        </div>
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">SEO Title <span class="text-slate-400 font-normal">(Leave blank to use listing title)</span></label>
                                <input type="text" name="meta_title" x-model="formData.meta_title" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3" placeholder="e.g., Luxury Villa in Kihim | Hello Alibaug">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">SEO Description</label>
                                <textarea name="meta_description" x-model="formData.meta_description" rows="3" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3" placeholder="A short summary of your listing for search results..."></textarea>
                            </div>

                            <!-- Preview Fragment -->
                            <div class="mt-8 pt-6 border-t border-slate-100">
                                <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-4">Google Preview</h3>
                                <div class="bg-slate-50 p-6 rounded-2xl">
                                    <p class="text-xs text-slate-600 mb-1">https://helloalibaug.com/{{ $category->slug }}/<span x-text="slugPreview"></span></p>
                                    <p class="text-xl text-[#1a0dab] hover:underline cursor-pointer font-medium mb-1 truncate" x-text="formData.meta_title || formData.title || 'Your Listing Title Here'"></p>
                                    <p class="text-sm text-slate-600 line-clamp-2" x-text="formData.meta_description || formData.description || 'Your meta description will appear here...'"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 6: Review & Submit -->
                    <div x-show="step === 6" x-transition.opacity.duration.300ms style="display: none;" data-step="6">
                        <div class="mb-8">
                            <h2 class="text-2xl font-serif font-bold text-slate-900 mb-2">Review & Submit</h2>
                            <p class="text-slate-500">Please review your information before final submission.</p>
                        </div>
                        
                        <div class="bg-slate-50 rounded-2xl p-6 mb-8 border border-slate-100 space-y-4">
                            <div class="flex items-start justify-between pb-4 border-b border-slate-200">
                                <div>
                                    <p class="text-sm text-slate-500 mb-1">Category</p>
                                    <p class="font-bold text-slate-900">{{ $category->name }}</p>
                                </div>
                                <button type="button" @click="goToStep(1)" class="text-primary text-sm font-bold hover:underline">Edit</button>
                            </div>
                            
                            <div class="flex items-start justify-between pb-4 border-b border-slate-200">
                                <div class="w-2/3">
                                    <p class="text-sm text-slate-500 mb-1">Listing Title</p>
                                    <p class="font-bold text-slate-900 truncate" x-text="formData.title || '-'"></p>
                                </div>
                                <button type="button" @click="goToStep(1)" class="text-primary text-sm font-bold hover:underline">Edit</button>
                            </div>

                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm text-slate-500 mb-1">Images</p>
                                    <p class="font-bold text-green-600" x-text="imagesCount + ' files attached'"></p>
                                </div>
                                <button type="button" @click="goToStep(4)" class="text-primary text-sm font-bold hover:underline">Edit</button>
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 flex gap-4">
                            <span class="material-symbols-outlined text-blue-500">info</span>
                            <div>
                                <h4 class="font-bold text-blue-900 mb-1">Ready to Submit!</h4>
                                <p class="text-sm text-blue-800">Your listing will be reviewed by our team manually. Approval usually takes up to 24 hours.</p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Footer Navigation -->
                <div class="bg-slate-50 border-t border-slate-100 p-6 sm:p-8 flex items-center justify-between">
                    <button type="button" x-show="step > 1" @click="prevStep" class="px-6 py-3 rounded-xl font-bold text-slate-600 hover:bg-white hover:text-slate-900 transition-colors border border-transparent hover:border-slate-200">
                        Back
                    </button>
                    <div x-show="step === 1"></div> <!-- Spacer -->

                    <button type="button" x-show="step < totalSteps" @click="nextStep" class="bg-slate-900 hover:bg-black text-white px-8 py-3 rounded-xl font-bold transition-all shadow-lg shadow-slate-900/20 flex items-center gap-2">
                        Next Step <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </button>

                    <button type="submit" x-show="step === totalSteps" class="bg-primary hover:bg-primary/90 text-white px-10 py-3 rounded-xl font-bold transition-all shadow-xl shadow-primary/30 flex items-center gap-2">
                        Submit Listing <span class="material-symbols-outlined text-[20px]">send</span>
                    </button>
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
            totalSteps: 6,
            stepNames: ['Basic Info', 'Details', 'Amenities', 'Photos', 'SEO', 'Review'],
            imagesCount: 0,
            formData: {
                title: '',
                area_id: '',
                price: '',
                description: '',
                email: '{{ auth()->user()->email }}',
                phone: '{{ auth()->user()->phone ?? "" }}',
                meta_title: '',
                meta_description: ''
            },
            
            get slugPreview() {
                if(!this.formData.title) return 'your-listing-slug';
                return this.formData.title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
            },

            updateImageCount(e) {
                this.imagesCount = e.target.files.length;
            },

            goToStep(s) {
                this.step = s;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },

            nextStep() {
                if (this.validateStep(this.step)) {
                    this.step++;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            },

            prevStep() {
                if (this.step > 1) {
                    this.step--;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            },

            validateStep(s) {
                // Ensure browser HTML5 validation runs on visible required inputs
                const stepElement = document.querySelector(`[data-step="${s}"]`);
                if (!stepElement) return true;
                
                const inputs = stepElement.querySelectorAll('input[required], select[required], textarea[required]');
                let isValid = true;
                
                inputs.forEach(input => {
                    if (!input.checkValidity()) {
                        input.reportValidity();
                        isValid = false;
                    }
                });

                // Custom validation for image step
                if (s === 4 && this.imagesCount < 3) {
                    alert('Please select at least 3 images for your listing.');
                    return false;
                }

                return isValid;
            }
        }))
    })
</script>
@endpush
@endsection
