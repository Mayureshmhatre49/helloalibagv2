@extends('layouts.app')
@section('title', 'Terms of Service — Hello Alibaug')
@section('meta_description', 'The terms governing use of Hello Alibaug — accounts, listings, subscription plans, reviews, bookings, liability and governing law.')

@php
    // ── Edit these values to match the registered business ────────────────
    // The same values are used in the Privacy Policy.
    $legalEntity   = config('legal.entity_name', 'Hello Alibaug');
    $grievanceName = config('legal.grievance_officer', 'The Grievance Officer');
    $contactEmail  = config('legal.contact_email', 'hello@helloalibaug.com');
@endphp

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-serif font-bold text-text-main mb-2">Terms of Service</h1>
    <p class="text-sm text-slate-600 mb-8">Last updated: {{ now()->format('F Y') }}</p>

    <div class="prose prose-sm max-w-none text-slate-600 [&_h2]:text-text-main [&_h2]:text-lg [&_h2]:font-bold [&_h2]:mt-8 [&_h2]:mb-3 [&_h3]:text-text-main [&_h3]:text-base [&_h3]:font-bold [&_h3]:mt-5 [&_h3]:mb-2 [&_a]:text-primary hover:[&_a]:underline">

        <p>These terms govern your use of <strong>helloalibaug.com</strong>, operated by {{ $legalEntity }} (“Hello Alibaug”, “we”, “us”). By using the site you accept them. If you do not accept them, please do not use the platform.</p>

        <h2>1. What Hello Alibaug Is</h2>
        <p>Hello Alibaug is a <strong>discovery and listing platform</strong> for stays, dining, experiences, services, events and real estate in and around Alibaug. We publish information supplied by business owners and connect them with interested customers.</p>
        <p>We are <strong>not</strong> a party to any booking, sale, rental or transaction between you and a listed business. We do not own, operate, inspect or manage the properties or services listed, we do not act as an agent for either side, and we do not process payments between guests and businesses. Any agreement you enter into is directly between you and that business.</p>

        <h2>2. Eligibility</h2>
        <p>You must be at least 18 years old and legally capable of entering a contract. By creating an account you confirm this. Business accounts must be created by someone authorised to act for that business.</p>

        <h2>3. Your Account</h2>
        <ul>
            <li>Provide accurate information and keep it current.</li>
            <li>Keep your password confidential — you are responsible for activity under your account.</li>
            <li>Tell us immediately at <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a> if you suspect unauthorised access.</li>
            <li>One person or business should not maintain duplicate accounts to circumvent listing limits.</li>
            <li>Administrator accounts require two-factor authentication.</li>
        </ul>
        <p>We may suspend an account that breaches these terms. A suspended account cannot sign in, and its listings may be withdrawn from public view.</p>

        <h2>4. Listings</h2>

        <h3>4.1 Review before publication</h3>
        <p>Every listing is reviewed by our team before going live. Approval typically takes around 24 hours but is not guaranteed within any timeframe. We may approve, reject, edit or remove any listing at our discretion — for example where it is inaccurate, incomplete, duplicated, miscategorised, or breaches these terms. Rejections include a reason, and you may correct and resubmit.</p>

        <h3>4.2 Your responsibilities as a listing owner</h3>
        <ul>
            <li>Information must be accurate and kept up to date, including pricing, availability and contact details.</li>
            <li>You must hold all licences, registrations and permissions the law requires for your business — including, where applicable, hospitality, food safety, fire safety and tourism registrations. We do not verify these.</li>
            <li>Photographs must be of your actual business and you must own them or have permission to use them.</li>
            <li>Do not misrepresent your location, facilities, capacity or ratings.</li>
            <li>Respond to inquiries and bookings in good faith, and honour what you advertise.</li>
        </ul>

        <h3>4.3 Editing an approved listing</h3>
        <p>Editing a live listing returns it to pending status for re-review, and any “Verified” badge is cleared until the updated content has been checked. This keeps published information trustworthy.</p>

        <h3>4.4 Badges</h3>
        <p>“Verified”, “Featured” and “Premium” badges are applied at our discretion. Verification means we have carried out a basic check at a point in time — it is not a warranty of quality, safety or legal compliance, and it can be revoked.</p>

        <h2>5. Subscription Plans &amp; Payments</h2>
        <ul>
            <li>Plans determine how many listings you may publish. Limits are enforced at submission.</li>
            <li>Fees, inclusions and limits are those shown on the plans page when you subscribe.</li>
            <li>Unless stated otherwise, fees are <strong>not refundable</strong> for partial periods. Downgrading or cancelling stops future billing; it does not refund the current period.</li>
            <li>If a plan is downgraded or lapses while you have more listings than the new plan allows, we may unpublish the excess until you upgrade or remove some.</li>
            <li>We may change pricing with reasonable notice. Changes do not affect the period already paid for.</li>
        </ul>

        <h3>5.1 Real Estate listings</h3>
        <p>Real Estate is a <strong>paid category settled offline</strong>. You may submit a listing at any time, but it will not be published until our team has contacted you, payment has been collected, and an administrator has recorded that payment. Real Estate listings do not consume your plan's listing allowance. We record who confirmed each payment and when.</p>

        <h2>6. Marketplace Classifieds</h2>
        <p>Classified items are reviewed before publication and expire automatically after 30 days, after which you may repost. You are responsible for the accuracy and legality of what you offer. Prohibited items include anything unlawful to sell, counterfeit goods, live animals, weapons, and regulated substances. Deal safely — meet in public, inspect before paying, and be sceptical of advance-payment requests. We are not party to these transactions and cannot recover money or goods.</p>

        <h2>7. Reviews</h2>
        <ul>
            <li>Reviews may only be left by users who have actually inquired about or booked the listing, and are moderated before publication.</li>
            <li>Reviews must describe a genuine first-hand experience.</li>
            <li>Do not post reviews of your own business, of a competitor, or in exchange for payment or incentives.</li>
            <li>We remove reviews that are fake, defamatory, abusive, discriminatory, contain personal data, or are unrelated to the experience.</li>
            <li>Owners may publicly reply once per review. We do not remove a review simply because the owner disagrees with it.</li>
        </ul>

        <h2>8. Inquiries &amp; Bookings</h2>
        <p>Sending an inquiry or booking request through the site passes your name, contact details and message to that listing's owner. A request is not a confirmed booking until the owner confirms it. Cancellation, refund and deposit terms are set by the business, not by us — ask before paying.</p>

        <h2>9. Acceptable Use</h2>
        <p>You must not:</p>
        <ul>
            <li>Post unlawful, misleading, defamatory, obscene, hateful or discriminatory content.</li>
            <li>Infringe anyone's intellectual property, privacy or publicity rights.</li>
            <li>Upload malware, attempt to gain unauthorised access, probe for vulnerabilities, or disrupt the service.</li>
            <li>Scrape, harvest or bulk-copy listings or contact details, or use automated systems beyond ordinary browsing and normal search-engine indexing.</li>
            <li>Impersonate any person or business, or misuse another user's contact details — including for unsolicited marketing.</li>
            <li>Manipulate rankings, reviews or view counts.</li>
        </ul>

        <h2>10. Content &amp; Intellectual Property</h2>
        <p>You keep ownership of what you upload. By submitting content you grant us a non-exclusive, royalty-free, worldwide licence to host, store, resize, display and distribute it for the purpose of operating and promoting the platform — including in search results, category pages, the map, and social previews. This licence ends when you delete the content, except for copies already distributed or retained in backups.</p>
        <p>The Hello Alibaug name, logo, design and compiled content are ours or our licensors'. Map data is © OpenStreetMap contributors. Do not reproduce our branding or substantial portions of the site without written permission.</p>

        <h2>11. Takedown Requests</h2>
        <p>If content here infringes your rights, email <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a> with the URL, a description of the content, an explanation of your rights, and your contact details. We investigate and remove infringing material where the claim is substantiated.</p>

        <h2>12. Availability</h2>
        <p>We aim to keep the platform available but do not guarantee uninterrupted or error-free service. We may modify, suspend or discontinue features, and may perform maintenance that makes the site temporarily unavailable.</p>

        <h2>13. Disclaimers</h2>
        <p>The platform and its content are provided <strong>“as is”</strong>, without warranties of any kind, whether express or implied, to the fullest extent permitted by law. In particular we do not warrant that:</p>
        <ul>
            <li>listing information — including pricing, availability, facilities and location — is accurate, complete or current;</li>
            <li>businesses listed hold required licences, or meet quality or safety standards;</li>
            <li>reviews are free of bias or error;</li>
            <li>ferry timings, event dates, weather information, opening hours or travel guidance are accurate — <strong>always confirm directly with the operator before travelling.</strong></li>
        </ul>

        <h2>14. Limitation of Liability</h2>
        <p>To the fullest extent permitted by law, we are not liable for indirect, incidental, special or consequential loss, nor for loss of profit, revenue, data, goodwill or opportunity, arising from your use of the platform or any dealing with a listed business — including disputes, cancellations, injury, property damage or financial loss.</p>
        <p>Where liability cannot lawfully be excluded, our total aggregate liability is limited to the amount you paid us in the twelve months before the claim arose, or ₹5,000, whichever is greater. Nothing here excludes liability for fraud, or for death or personal injury caused by our negligence, where the law does not permit exclusion.</p>

        <h2>15. Indemnity</h2>
        <p>You agree to indemnify {{ $legalEntity }} against claims, losses and reasonable legal costs arising from content you submit, your use of the platform, your breach of these terms, or your dealings with other users.</p>

        <h2>16. Termination</h2>
        <p>You may close your account at any time. We may suspend or terminate access where these terms are breached, where required by law, or where continued access poses a risk to other users. Deleting an account permanently removes its listings, reviews, classifieds and bookings — this cannot be undone. Sections that by nature should survive termination — including intellectual property, disclaimers, liability, indemnity and governing law — continue to apply.</p>

        <h2>17. Grievance Officer</h2>
        <p>In accordance with the Information Technology Act, 2000 and the rules made under it, complaints regarding content or use of the platform may be sent to:</p>
        <p>
            {{ $grievanceName }}<br>
            {{ $legalEntity }}<br>
            Alibaug, Raigad, Maharashtra, India<br>
            <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
        </p>
        <p>Complaints are acknowledged within 24 hours and resolved within 15 days.</p>

        <h2>18. Governing Law &amp; Disputes</h2>
        <p>These terms are governed by the laws of India. Courts at Alibaug, Raigad District, Maharashtra have exclusive jurisdiction, subject to any consumer-protection right you have to bring proceedings where you live. We encourage you to contact us first — most disputes are resolved quickly without formal proceedings.</p>

        <h2>19. Changes</h2>
        <p>We may update these terms as the platform evolves. The “last updated” date above reflects the current version, and material changes will be notified by email or a prominent notice. Continuing to use the platform after changes take effect constitutes acceptance.</p>

        <h2>20. Contact</h2>
        <p>Questions about these terms: <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>, or via our <a href="{{ route('page.contact') }}">contact page</a>. See also our <a href="{{ route('page.privacy') }}">Privacy Policy</a>.</p>

        <hr class="my-8 border-slate-200">
        <p class="text-xs text-slate-500">These terms describe how the platform actually operates, in plain language. They are not legal advice and have not been reviewed by a lawyer. Before relying on them in a dispute or regulatory application, have them reviewed by a qualified legal professional.</p>
    </div>
</div>
@endsection
