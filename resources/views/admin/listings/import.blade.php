@extends('layouts.admin')
@section('page-title', 'Import Listings via CSV')

@section('content')

{{-- Page Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-text-main">Bulk Import Listings</h2>
        <p class="text-sm text-text-secondary mt-1">Upload a CSV file to add multiple listings at once. They will be created as <strong>Pending</strong> under <strong>Nishat Mhatre</strong> for review.</p>
    </div>
    <a href="{{ route('admin.listings.index') }}" class="flex items-center gap-1.5 text-sm font-medium text-text-secondary hover:text-text-main transition-colors">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
        Back to Listings
    </a>
</div>

{{-- Success Banner --}}
@if(session('import_success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 mb-6">
        <div class="flex items-start gap-3">
            <span class="material-symbols-outlined text-emerald-600 text-2xl mt-0.5">check_circle</span>
            <div class="flex-1">
                <p class="font-semibold text-emerald-800">Import Complete!</p>
                <p class="text-sm text-emerald-700 mt-0.5">{{ session('import_success') }}</p>
                <div class="flex items-center gap-4 mt-3">
                    <div class="text-center bg-white rounded-xl px-4 py-2 border border-emerald-200">
                        <p class="text-2xl font-bold text-emerald-700">{{ session('imported_count', 0) }}</p>
                        <p class="text-xs text-emerald-600 font-medium">Imported</p>
                    </div>
                    @if(session('skipped_count', 0) > 0)
                    <div class="text-center bg-white rounded-xl px-4 py-2 border border-amber-200">
                        <p class="text-2xl font-bold text-amber-700">{{ session('skipped_count', 0) }}</p>
                        <p class="text-xs text-amber-600 font-medium">Skipped</p>
                    </div>
                    @endif
                </div>
                <a href="{{ route('admin.listings.index', ['status' => 'pending']) }}" class="inline-flex items-center gap-1.5 mt-4 text-sm font-semibold text-emerald-700 hover:text-emerald-900 underline underline-offset-2">
                    <span class="material-symbols-outlined text-[16px]">approval</span>
                    Review pending listings now →
                </a>
            </div>
        </div>
    </div>
@endif

{{-- Error Banner --}}
@if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-2xl p-5 mb-6">
        <div class="flex items-start gap-3">
            <span class="material-symbols-outlined text-red-600 text-2xl mt-0.5">error</span>
            <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
        </div>
    </div>
@endif

{{-- Skipped Row Errors --}}
@if(session('import_errors') && count(session('import_errors')) > 0)
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-6">
        <p class="font-semibold text-amber-800 mb-2">⚠️ Skipped Rows</p>
        <ul class="space-y-1">
            @foreach(session('import_errors') as $err)
                <li class="text-sm text-amber-700 flex items-start gap-2">
                    <span class="material-symbols-outlined text-[14px] mt-0.5 flex-shrink-0">warning</span>
                    {{ $err }}
                </li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

    {{-- Upload Card --}}
    <div class="lg:col-span-3">
        <div class="bg-white rounded-2xl border border-border-light p-6">
            <h3 class="font-bold text-text-main mb-1">Upload Your CSV</h3>
            <p class="text-sm text-text-secondary mb-5">File must be <code class="bg-background-light px-1 py-0.5 rounded text-xs">.csv</code> format, max 2 MB.</p>

            <form method="POST" action="{{ route('admin.listings.import.store') }}" enctype="multipart/form-data" x-data="{ fileName: '', dragging: false }">
                @csrf

                {{-- Drop Zone --}}
                <div
                    class="border-2 border-dashed rounded-xl p-8 text-center transition-all cursor-pointer"
                    :class="dragging ? 'border-primary bg-primary/5' : 'border-border-light hover:border-primary/50 bg-background-light'"
                    @dragover.prevent="dragging = true"
                    @dragleave.prevent="dragging = false"
                    @drop.prevent="dragging = false; const f = $event.dataTransfer.files[0]; if(f){ fileName = f.name; $refs.fileInput.files = $event.dataTransfer.files; }"
                    @click="$refs.fileInput.click()"
                >
                    <span class="material-symbols-outlined text-4xl mb-2" :class="fileName ? 'text-primary' : 'text-gray-300'">upload_file</span>
                    <p x-show="!fileName" class="text-sm text-text-secondary">Drag & drop your CSV here or <span class="text-primary font-semibold">browse</span></p>
                    <p x-show="fileName" class="text-sm font-semibold text-primary" x-text="'✓ ' + fileName"></p>
                    <input
                        type="file"
                        name="csv_file"
                        accept=".csv,text/csv"
                        class="hidden"
                        x-ref="fileInput"
                        @change="fileName = $event.target.files[0]?.name || ''"
                    >
                </div>

                @error('csv_file')
                    <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                @enderror

                {{-- Info Box --}}
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mt-4 flex items-start gap-3">
                    <span class="material-symbols-outlined text-blue-500 text-[20px] mt-0.5 flex-shrink-0">info</span>
                    <div class="text-xs text-blue-700 space-y-1">
                        <p>• All imported listings will be assigned to <strong>Nishat Mhatre</strong></p>
                        <p>• Status will be set to <strong>Pending</strong> — Nishat must approve each one</p>
                        <p>• Rows with missing <code class="bg-blue-100 px-1 rounded">title</code>, <code class="bg-blue-100 px-1 rounded">category_slug</code>, or <code class="bg-blue-100 px-1 rounded">area_slug</code> will be skipped</p>
                        <p>• Use <code class="bg-blue-100 px-1 rounded">|</code> as separator for multiple amenities (e.g., <code class="bg-blue-100 px-1 rounded">Pool|WiFi|AC</code>)</p>
                    </div>
                </div>

                <button
                    type="submit"
                    x-bind:disabled="!fileName"
                    :class="fileName ? 'bg-primary hover:bg-primary/90 cursor-pointer' : 'bg-gray-300 cursor-not-allowed'"
                    class="mt-5 w-full flex items-center justify-center gap-2 text-white font-semibold py-3 px-6 rounded-xl transition-colors"
                >
                    <span class="material-symbols-outlined text-[20px]">upload</span>
                    Import Listings
                </button>
            </form>
        </div>

        {{-- Download Template --}}
        <div class="mt-4 bg-white rounded-2xl border border-border-light p-5 flex items-center justify-between gap-4">
            <div>
                <p class="font-semibold text-text-main">📥 Download Pre-filled Template</p>
                <p class="text-xs text-text-secondary mt-0.5">Ready-to-use CSV with 30 curated Alibaug listings across all categories.</p>
            </div>
            <a
                href="{{ asset('templates/alibaug-listings-template.csv') }}"
                download="alibaug-listings-template.csv"
                class="flex-shrink-0 flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors"
            >
                <span class="material-symbols-outlined text-[18px]">download</span>
                Download CSV
            </a>
        </div>
    </div>

    {{-- Column Reference --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-border-light p-6">
            <h3 class="font-bold text-text-main mb-1">CSV Column Reference</h3>
            <p class="text-xs text-text-secondary mb-4">The first row of your CSV must be the header row with these exact column names.</p>

            <div class="space-y-2">
                @php
                $columns = [
                    ['name' => 'title',         'req' => true,  'note' => 'Name of the listing'],
                    ['name' => 'category_slug',  'req' => true,  'note' => 'stay / eat / events / explore / services / real-estate'],
                    ['name' => 'area_slug',      'req' => true,  'note' => 'mandwa / kihim / alibaug-town / awas / nagaon / versoli / zirad / kashid / sasawane / dhokawade'],
                    ['name' => 'description',    'req' => false, 'note' => 'Full description text'],
                    ['name' => 'price',          'req' => false, 'note' => 'Numeric price (leave blank if N/A)'],
                    ['name' => 'address',        'req' => false, 'note' => 'Full street address'],
                    ['name' => 'phone',          'req' => false, 'note' => '10-digit mobile or landline'],
                    ['name' => 'email',          'req' => false, 'note' => 'Contact email'],
                    ['name' => 'website',        'req' => false, 'note' => 'Full URL with https://'],
                    ['name' => 'whatsapp',       'req' => false, 'note' => 'WhatsApp number'],
                    ['name' => 'amenities',      'req' => false, 'note' => 'Pool|WiFi|AC|Kitchen (pipe-separated)'],
                    ['name' => 'image_url',      'req' => false, 'note' => 'Full image URL (https://...)'],
                ];
                @endphp

                @foreach($columns as $col)
                    <div class="flex items-start gap-3 p-2.5 rounded-lg hover:bg-background-light transition-colors">
                        <code class="text-xs font-mono bg-background-light border border-border-light px-2 py-0.5 rounded flex-shrink-0 mt-0.5">{{ $col['name'] }}</code>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-text-secondary">{{ $col['note'] }}</p>
                        </div>
                        @if($col['req'])
                            <span class="text-[10px] font-bold text-red-500 flex-shrink-0 mt-0.5">REQ</span>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-4 pt-4 border-t border-border-light">
                <p class="text-xs font-semibold text-text-secondary mb-2">Valid Amenity Names</p>
                <div class="flex flex-wrap gap-1">
                    @foreach(['Pool','WiFi','AC','Kitchen','Garden','BBQ Grill','Balcony','Smart TV','Free Parking','Sea View','Beach View','Jacuzzi','Gym','Caretaker','Washer & Dryer','Pet Friendly','Meals Included'] as $a)
                        <span class="text-[10px] bg-background-light border border-border-light px-1.5 py-0.5 rounded font-mono">{{ $a }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
