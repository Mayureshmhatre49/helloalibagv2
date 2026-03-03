@extends('layouts.app')
@section('title', 'Privacy Policy — Hello Alibaug')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-serif font-bold text-text-main mb-2">Privacy Policy</h1>
    <p class="text-sm text-text-secondary mb-8">Last updated: {{ now()->format('F Y') }}</p>

    <div class="prose prose-sm max-w-none text-text-secondary [&_h2]:text-text-main [&_h2]:text-lg [&_h2]:font-bold [&_h2]:mt-8 [&_h2]:mb-3">
        <h2>Information We Collect</h2>
        <p>We collect information you provide directly, including name, email address, phone number, and listing details. We also automatically collect usage data such as pages visited, search queries, and device information.</p>

        <h2>How We Use Your Information</h2>
        <p>Your information is used to provide and improve our services, process listing submissions, send notifications about your account, and connect potential customers with listing owners.</p>

        <h2>Data Sharing</h2>
        <p>We do not sell your personal information. Listing contact details (phone, email) are displayed publicly as part of the listing. We may share data with service providers who assist in operating our platform.</p>

        <h2>Cookies</h2>
        <p>We use cookies and similar technologies to maintain your session, remember preferences, and analyze how our service is used.</p>

        <h2>Data Security</h2>
        <p>We implement industry-standard security measures to protect your data. However, no method of transmission over the internet is 100% secure.</p>

        <h2>Your Rights</h2>
        <p>You can access, update, or delete your personal information from your account settings. To request complete data deletion, please contact us at hello@helloalibaug.com.</p>

        <h2>Contact</h2>
        <p>For privacy-related questions, email us at hello@helloalibaug.com or visit our <a href="{{ route('page.contact') }}" class="text-primary hover:underline">Contact page</a>.</p>
    </div>
</div>
@endsection
