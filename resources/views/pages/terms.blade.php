@extends('layouts.app')
@section('title', 'Terms of Service — Hello Alibaug')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-serif font-bold text-text-main mb-2">Terms of Service</h1>
    <p class="text-sm text-text-secondary mb-8">Last updated: {{ now()->format('F Y') }}</p>

    <div class="prose prose-sm max-w-none text-text-secondary [&_h2]:text-text-main [&_h2]:text-lg [&_h2]:font-bold [&_h2]:mt-8 [&_h2]:mb-3">
        <h2>Acceptance of Terms</h2>
        <p>By accessing Hello Alibaug, you agree to these terms. If you don't agree, please do not use our platform.</p>

        <h2>Listing Submissions</h2>
        <p>Business owners can submit listings for review. We reserve the right to approve, reject, or remove any listing that doesn't meet our quality standards or violates our policies.</p>

        <h2>User Accounts</h2>
        <p>You're responsible for maintaining the security of your account. You must provide accurate information when creating your account and submitting listings.</p>

        <h2>Content Guidelines</h2>
        <p>All content submitted must be accurate and not misleading. You must have the right to share any images or information included in your listings. We prohibit content that is illegal, offensive, or infringes on others' rights.</p>

        <h2>Reviews & Feedback</h2>
        <p>Reviews must reflect genuine experiences. We reserve the right to remove reviews that are fake, abusive, or irrelevant.</p>

        <h2>Limitation of Liability</h2>
        <p>Hello Alibaug is a listing platform. We are not responsible for the quality of services provided by listed businesses, nor for transactions between users and businesses.</p>

        <h2>Changes</h2>
        <p>We may update these terms at any time. Continued use of the platform after changes constitutes acceptance.</p>

        <h2>Contact</h2>
        <p>Questions about these terms? Visit our <a href="{{ route('page.contact') }}" class="text-primary hover:underline">Contact page</a>.</p>
    </div>
</div>
@endsection
