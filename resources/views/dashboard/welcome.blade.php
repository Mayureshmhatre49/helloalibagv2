@extends('layouts.app')
@section('title', 'Welcome to Hello Alibaug')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-16">
    <div class="max-w-xl w-full text-center">
        {{-- Success Animation --}}
        <div class="mb-8 relative">
            <div class="w-24 h-24 mx-auto bg-green-50 rounded-full flex items-center justify-center animate-bounce-slow">
                <span class="material-symbols-outlined text-green-500 text-5xl">check_circle</span>
            </div>
        </div>

        <h1 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900 mb-4">
            Welcome aboard, {{ auth()->user()->name }}! 🎉
        </h1>

        <p class="text-lg text-slate-600 mb-8 max-w-md mx-auto">
            Your account has been created successfully. You're now part of the Hello Alibaug community!
        </p>

        {{-- Steps Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-8 mb-8 text-left shadow-sm">
            <h2 class="text-lg font-bold text-slate-900 mb-6 text-center">What happens next?</h2>

            <div class="space-y-6">
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm">1</div>
                    <div>
                        <h3 class="font-bold text-slate-900 mb-1">Complete your profile</h3>
                        <p class="text-sm text-slate-500">Add your business details, logo, and contact information to build trust with customers.</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary/20 text-primary flex items-center justify-center font-bold text-sm">2</div>
                    <div>
                        <h3 class="font-bold text-slate-900 mb-1">Create your first listing</h3>
                        <p class="text-sm text-slate-500">Add photos, pricing, amenities, and everything travelers need to find you.</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-sm">3</div>
                    <div>
                        <h3 class="font-bold text-slate-900 mb-1">Our team reviews your listing</h3>
                        <p class="text-sm text-slate-500">We carefully verify every listing to maintain our premium quality standards. This usually takes <strong>24-48 hours</strong>.</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-sm">4</div>
                    <div>
                        <h3 class="font-bold text-slate-900 mb-1">Go live & start receiving enquiries</h3>
                        <p class="text-sm text-slate-500">Once approved, your listing goes live and you'll start getting calls and messages from interested customers.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Trust Note --}}
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-8 flex items-start gap-3 text-left">
            <span class="material-symbols-outlined text-primary flex-shrink-0 mt-0.5">shield</span>
            <div>
                <p class="text-sm font-bold text-slate-900 mb-0.5">Quality you can trust</p>
                <p class="text-sm text-slate-600">Every listing on Hello Alibaug is manually reviewed by our team to ensure accuracy and quality. This is what makes our platform premium.</p>
            </div>
        </div>

        {{-- CTA Buttons --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('owner.dashboard') }}" class="bg-primary hover:bg-primary/90 text-white px-8 py-3.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-primary/20 w-full sm:w-auto text-center flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[20px]">dashboard</span>
                Go to Dashboard
            </a>
            <a href="{{ route('owner.onboarding.start') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-900 px-8 py-3.5 rounded-xl font-bold text-sm transition-all w-full sm:w-auto text-center flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[20px]">add_circle</span>
                Create First Listing
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-bounce-slow { animation: bounce-slow 2s ease-in-out infinite; }
</style>
@endpush
@endsection
