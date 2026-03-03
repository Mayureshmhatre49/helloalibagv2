@extends('layouts.dashboard')
@section('page-title', 'Create Support Ticket')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('owner.support.index') }}" class="inline-flex items-center gap-1 text-sm text-text-secondary hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Back to Support Center
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-border-light p-6 sm:p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="size-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">support_agent</span>
            </div>
            <div>
                <h2 class="text-lg font-bold text-text-main">Create a Support Ticket</h2>
                <p class="text-sm text-text-secondary">Describe your issue and we'll get back to you as soon as possible.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('owner.support.store') }}" class="space-y-5">
            @csrf

            {{-- Subject --}}
            <div>
                <label for="subject" class="block text-sm font-semibold text-text-main mb-1.5">Subject <span class="text-red-500">*</span></label>
                <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                       class="w-full border border-border-light rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"
                       placeholder="e.g., Help with listing images not showing">
                @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Category & Priority --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block text-sm font-semibold text-text-main mb-1.5">Category <span class="text-red-500">*</span></label>
                    <select name="category" id="category" required
                            class="w-full border border-border-light rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-white">
                        <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                        <option value="listing" {{ old('category') == 'listing' ? 'selected' : '' }}>Listing Related</option>
                        <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>Technical Issue</option>
                        <option value="billing" {{ old('category') == 'billing' ? 'selected' : '' }}>Billing & Payments</option>
                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="priority" class="block text-sm font-semibold text-text-main mb-1.5">Priority <span class="text-red-500">*</span></label>
                    <select name="priority" id="priority" required
                            class="w-full border border-border-light rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-white">
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low — I can wait</option>
                        <option value="normal" {{ old('priority', 'normal') == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High — Need help soon</option>
                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent — Critical issue</option>
                    </select>
                    @error('priority') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Related Listing --}}
            @if($listings->count() > 0)
            <div>
                <label for="listing_id" class="block text-sm font-semibold text-text-main mb-1.5">Related Listing <span class="text-text-secondary font-normal">(optional)</span></label>
                <select name="listing_id" id="listing_id"
                        class="w-full border border-border-light rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-white">
                    <option value="">None — General question</option>
                    @foreach($listings as $listing)
                        <option value="{{ $listing->id }}" {{ old('listing_id') == $listing->id ? 'selected' : '' }}>{{ $listing->title }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- Message --}}
            <div>
                <label for="message" class="block text-sm font-semibold text-text-main mb-1.5">Describe Your Issue <span class="text-red-500">*</span></label>
                <textarea name="message" id="message" rows="6" required
                          class="w-full border border-border-light rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all resize-none"
                          placeholder="Please describe your issue in detail. The more context you provide, the faster we can help...">{{ old('message') }}</textarea>
                @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-primary-dark transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">send</span>
                    Submit Ticket
                </button>
                <a href="{{ route('owner.support.index') }}" class="text-sm text-text-secondary hover:text-text-main transition-colors">Cancel</a>
            </div>
        </form>
    </div>

    {{-- Help Tips --}}
    <div class="mt-6 bg-blue-50/50 border border-blue-100 rounded-2xl p-5">
        <h3 class="text-sm font-semibold text-blue-800 mb-2 flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[18px]">tips_and_updates</span>
            Tips for Faster Resolution
        </h3>
        <ul class="text-sm text-blue-700 space-y-1.5 ml-6 list-disc">
            <li>Include specific listing names or URLs if applicable</li>
            <li>Describe the expected behavior vs. what you're experiencing</li>
            <li>Mention any steps to reproduce the issue</li>
            <li>Set the correct priority level to help us triage effectively</li>
        </ul>
    </div>
</div>
@endsection
