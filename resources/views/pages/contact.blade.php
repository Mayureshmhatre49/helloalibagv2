@extends('layouts.app')
@section('title', 'Contact Us — Hello Alibaug')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-serif font-bold text-text-main mb-3">Contact Us</h1>
        <p class="text-text-secondary">Got a question or feedback? We'd love to hear from you.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl p-4 mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl border border-border-light p-6">
                <form method="POST" action="{{ route('page.contact.submit') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-text-main mb-1">Name *</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required class="w-full border border-border-light rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-main mb-1">Email *</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required class="w-full border border-border-light rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-main mb-1">Subject *</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required class="w-full border border-border-light rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                        @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-main mb-1">Message *</label>
                        <textarea name="message" rows="5" required class="w-full border border-border-light rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none">{{ old('message') }}</textarea>
                        @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="bg-primary text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-primary-dark transition-colors">Send Message</button>
                </form>
            </div>
        </div>
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-border-light p-5">
                <span class="material-symbols-outlined text-primary text-2xl mb-2 block">mail</span>
                <h3 class="font-bold text-text-main text-sm mb-1">Email</h3>
                <p class="text-sm text-text-secondary">hello@helloalibaug.com</p>
            </div>
            <div class="bg-white rounded-2xl border border-border-light p-5">
                <span class="material-symbols-outlined text-primary text-2xl mb-2 block">call</span>
                <h3 class="font-bold text-text-main text-sm mb-1">Phone</h3>
                <p class="text-sm text-text-secondary">+91 98765 43210</p>
            </div>
            <div class="bg-white rounded-2xl border border-border-light p-5">
                <span class="material-symbols-outlined text-primary text-2xl mb-2 block">location_on</span>
                <h3 class="font-bold text-text-main text-sm mb-1">Address</h3>
                <p class="text-sm text-text-secondary">Alibag, Raigad, Maharashtra 402201</p>
            </div>
        </div>
    </div>
</div>
@endsection
