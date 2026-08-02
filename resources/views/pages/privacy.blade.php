@extends('layouts.app')
@section('title', 'Privacy Policy — Hello Alibaug')
@section('meta_description', 'How Hello Alibaug collects, uses, shares and protects your personal data, including cookies, advertising and your rights under India\'s DPDP Act 2023.')

@php
    // ── Edit these three values to match the registered business ──────────
    // They appear throughout this policy and in the Terms of Service.
    $legalEntity     = config('legal.entity_name', 'Hello Alibaug');
    $grievanceName   = config('legal.grievance_officer', 'The Grievance Officer');
    $contactEmail    = config('legal.contact_email', 'hello@helloalibaug.com');
@endphp

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-serif font-bold text-text-main mb-2">Privacy Policy</h1>
    <p class="text-sm text-slate-600 mb-8">Last updated: {{ now()->format('F Y') }}</p>

    <div class="prose prose-sm max-w-none text-slate-600 [&_h2]:text-text-main [&_h2]:text-lg [&_h2]:font-bold [&_h2]:mt-8 [&_h2]:mb-3 [&_h3]:text-text-main [&_h3]:text-base [&_h3]:font-bold [&_h3]:mt-5 [&_h3]:mb-2 [&_a]:text-primary hover:[&_a]:underline [&_table]:text-xs [&_th]:text-left [&_th]:font-bold [&_th]:text-text-main [&_th]:py-2 [&_td]:py-2 [&_td]:align-top [&_td]:border-t [&_td]:border-slate-100">

        <p>This policy explains what personal data {{ $legalEntity }} (“Hello Alibaug”, “we”, “us”) collects when you use <strong>helloalibaug.com</strong>, why we collect it, who we share it with, and the choices and rights you have. It applies to everyone who visits the site, creates an account, lists a business, or contacts us.</p>

        <h2>1. Information We Collect</h2>

        <h3>1.1 Information you give us</h3>
        <table class="w-full">
            <thead><tr><th>Data</th><th>When</th></tr></thead>
            <tbody>
                <tr><td>Name, email address, phone number, password</td><td>Creating an account</td></tr>
                <tr><td>Business name, description, address, coordinates, contact details, photos, pricing</td><td>Submitting or editing a listing</td></tr>
                <tr><td>Message content, name, email, phone</td><td>Sending an inquiry, booking request, or contact-form message</td></tr>
                <tr><td>Rating, review text, review photos</td><td>Leaving a review</td></tr>
                <tr><td>Item details, asking price, contact number</td><td>Posting a marketplace classified</td></tr>
                <tr><td>Profile photo, bio, social links</td><td>Optionally, when completing your profile</td></tr>
            </tbody>
        </table>

        <h3>1.2 Information collected automatically</h3>
        <p>When you browse the site we automatically receive your IP address, browser type and version, device and operating system, referring page, pages viewed, search queries and filters used, and the date and time of each visit. We also record aggregate daily view counts per listing so owners can see how their listing is performing — this is a count only, not a record of who viewed what.</p>

        <h3>1.3 Location data</h3>
        <p>If you use the “Use my location” button when pinning a listing, your browser asks your permission before sharing your device's coordinates. We use those coordinates once, to place the map pin — we do not track your location in the background, and declining has no effect other than requiring you to place the pin manually.</p>

        <h2>2. How We Use Your Information</h2>
        <ul>
            <li><strong>To operate the platform</strong> — create and secure your account, publish listings, deliver inquiries to owners, process bookings and reviews.</li>
            <li><strong>To communicate</strong> — send transactional email such as listing approval or rejection notices, inquiry alerts, booking confirmations and security notifications. These are essential to the service and cannot be opted out of while your account is active.</li>
            <li><strong>To send newsletters</strong> — only if you explicitly subscribe and confirm your address. Every newsletter contains a one-click unsubscribe link.</li>
            <li><strong>To moderate and protect</strong> — review submissions before publication, investigate abuse, prevent spam and fraud, and enforce our Terms.</li>
            <li><strong>To improve the service</strong> — understand which pages, searches and categories are used, and fix problems.</li>
            <li><strong>To meet legal obligations</strong> — respond to lawful requests and retain records where required.</li>
        </ul>

        <h2>3. Cookies &amp; Similar Technologies</h2>
        <p>Cookies are small files stored by your browser. We use them in three categories:</p>
        <table class="w-full">
            <thead><tr><th>Type</th><th>Purpose</th><th>Can you disable it?</th></tr></thead>
            <tbody>
                <tr><td><strong>Strictly necessary</strong></td><td>Keeps you signed in, maintains your session, and protects forms against cross-site request forgery.</td><td>No — the site will not function without these.</td></tr>
                <tr><td><strong>Preference</strong></td><td>Remembers choices such as newsletter dismissal so you are not asked repeatedly.</td><td>Yes, via your browser settings.</td></tr>
                <tr><td><strong>Analytics &amp; advertising</strong></td><td>Helps us understand usage patterns and, where advertising is served, supports ad delivery and measurement.</td><td>Yes — see sections 4 and 5.</td></tr>
            </tbody>
        </table>
        <p>Most browsers let you refuse or delete cookies through their settings. Blocking strictly necessary cookies will prevent you from signing in.</p>

        <h2>4. Advertising</h2>
        <p>Where third-party advertising is served on this site, including <strong>Google AdSense</strong>, the following applies:</p>
        <ul>
            <li>Third-party vendors, including Google, use cookies to serve ads based on your prior visits to this and other websites.</li>
            <li>Google's use of advertising cookies enables it and its partners to serve ads to you based on your visit to our site and/or other sites on the internet.</li>
            <li>You may opt out of personalised advertising by visiting <a href="https://www.google.com/settings/ads" target="_blank" rel="noopener">Google Ads Settings</a>.</li>
            <li>You can opt out of some third-party vendors' use of cookies for personalised advertising at <a href="https://www.aboutads.info/choices/" target="_blank" rel="noopener">aboutads.info/choices</a> or <a href="https://optout.networkadvertising.org/" target="_blank" rel="noopener">optout.networkadvertising.org</a>.</li>
            <li>Opting out does not remove advertising — it means the ads you see are less likely to be tailored to you.</li>
        </ul>

        <h2>5. Analytics</h2>
        <p>We measure how the site is used so we can improve it. Analytics data is aggregated and used to understand traffic patterns rather than to identify individuals. Where a third-party analytics provider is used, that provider processes data under its own privacy policy, and you can limit collection through your browser's cookie settings or by enabling “Do Not Track”.</p>

        <h2>6. Who We Share Data With</h2>
        <p><strong>We do not sell your personal data.</strong> We share it only in these situations:</p>
        <table class="w-full">
            <thead><tr><th>Recipient</th><th>What &amp; why</th></tr></thead>
            <tbody>
                <tr><td><strong>Publicly on the site</strong></td><td>Listing details you submit — including business name, address, map location, phone, email and photos — are published deliberately, so customers can find and contact you. Do not enter personal contact details you do not want visible.</td></tr>
                <tr><td><strong>Listing owners</strong></td><td>When you send an inquiry or booking request, your name, email, phone and message are sent to that listing's owner so they can respond.</td></tr>
                <tr><td><strong>Hosting provider</strong></td><td>Stores the site and database on our behalf.</td></tr>
                <tr><td><strong>Email delivery provider</strong></td><td>Delivers transactional email and newsletters.</td></tr>
                <tr><td><strong>Geoapify</strong></td><td>Address search and geocoding. When you search for an address in the location picker, the text you type is sent to Geoapify to return matching places.</td></tr>
                <tr><td><strong>OpenStreetMap</strong></td><td>Supplies map tiles. Your browser requests tiles directly from their servers when a map is displayed.</td></tr>
                <tr><td><strong>Advertising &amp; analytics providers</strong></td><td>As described in sections 4 and 5.</td></tr>
                <tr><td><strong>Legal authorities</strong></td><td>Where we are required by law, or to establish, exercise or defend legal claims.</td></tr>
            </tbody>
        </table>

        <h2>7. How Long We Keep Data</h2>
        <table class="w-full">
            <thead><tr><th>Data</th><th>Retention</th></tr></thead>
            <tbody>
                <tr><td>Account and profile</td><td>Until you delete your account, then removed within 30 days except where retention is legally required.</td></tr>
                <tr><td>Listings and their images</td><td>Until deleted by you or an administrator. Deleting an account also deletes its listings, reviews and bookings.</td></tr>
                <tr><td>Inquiries and booking requests</td><td>Up to 3 years, so owners and guests can refer back to past correspondence.</td></tr>
                <tr><td>Reviews</td><td>Kept while the listing exists, since removing them would misrepresent its rating history.</td></tr>
                <tr><td>Newsletter subscription</td><td>Until you unsubscribe.</td></tr>
                <tr><td>Aggregate listing view counts</td><td>Retained as statistics; contains no personal identifiers.</td></tr>
                <tr><td>Server and security logs</td><td>Typically up to 12 months.</td></tr>
            </tbody>
        </table>

        <h2>8. Security</h2>
        <p>Passwords are stored hashed, never in plain text. The site is served over HTTPS. Administrator accounts require two-factor authentication, and sensitive actions are rate-limited and logged. No system is perfectly secure, so we cannot guarantee absolute security — but if a breach affects your personal data, we will notify you and the relevant authority as required by law.</p>

        <h2>9. Your Rights</h2>
        <p>Under India's <strong>Digital Personal Data Protection Act, 2023</strong> and other applicable law, you may:</p>
        <ul>
            <li><strong>Access</strong> a summary of the personal data we hold about you and how it is processed.</li>
            <li><strong>Correct</strong> inaccurate or incomplete data — most of this you can edit yourself from your profile and listing pages.</li>
            <li><strong>Erase</strong> your data where it is no longer needed for the purpose it was collected.</li>
            <li><strong>Withdraw consent</strong> you previously gave, such as newsletter subscription, without affecting processing already carried out.</li>
            <li><strong>Nominate</strong> another person to exercise your rights in the event of death or incapacity.</li>
            <li><strong>Complain</strong> to the Data Protection Board of India if you believe your rights have been infringed.</li>
        </ul>
        <p>To exercise any of these, email <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>. We respond within 30 days and may ask you to verify your identity first.</p>

        <h2>10. Grievance Officer</h2>
        <p>In accordance with the Information Technology Act, 2000 and rules made under it, and the DPDP Act 2023, complaints about the handling of your personal data may be addressed to:</p>
        <p>
            {{ $grievanceName }}<br>
            {{ $legalEntity }}<br>
            Alibaug, Raigad, Maharashtra, India<br>
            <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
        </p>
        <p>We acknowledge complaints within 24 hours and aim to resolve them within 15 days.</p>

        <h2>11. Children</h2>
        <p>This service is not directed at children under 18, and we do not knowingly collect their personal data. Where processing a child's data is unavoidable, we do so only with verifiable parental consent as required by the DPDP Act, and we do not use it for tracking or targeted advertising. If you believe a child has provided us data, contact us and we will delete it.</p>

        <h2>12. Data Location &amp; Transfers</h2>
        <p>Our servers and database are operated on our behalf by our hosting provider. Some service providers described in section 6 may process limited data outside India. Where that happens, we rely on the provider's contractual safeguards and only share the minimum needed for the service to function.</p>

        <h2>13. Third-Party Links</h2>
        <p>Listings and articles may link to external websites, and businesses listed here operate their own sites and booking channels. We are not responsible for their privacy practices — review their policies before sharing data with them.</p>

        <h2>14. Changes to This Policy</h2>
        <p>We may update this policy as the service or the law changes. The “last updated” date above always reflects the current version. Material changes affecting how we use your data will be communicated by email or a prominent notice on the site.</p>

        <h2>15. Contact</h2>
        <p>Questions about this policy or your data: <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>, or via our <a href="{{ route('page.contact') }}">contact page</a>.</p>

        <hr class="my-8 border-slate-200">
        <p class="text-xs text-slate-500">This policy describes our actual data practices in plain language. It is not legal advice, and it has not been reviewed by a lawyer. Before relying on it for a regulatory application or in a dispute, have it reviewed by a qualified legal professional familiar with Indian data protection law.</p>
    </div>
</div>
@endsection
