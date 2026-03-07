@extends('layouts.app')

@section('title', 'Alibaug Travel & Lifestyle Guide - Hello Alibaug')
@section('meta_description', 'Hello Alibaug is a trusted hyperlocal platform and an Alibaug Travel Guide created to help people discover, live, and invest in Alibaug with confidence.')

@section('content')
<main class="bg-slate-50 min-h-screen">
    <!-- Hero Section -->
    <div class="bg-white border-b border-slate-200 pt-20 pb-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-5xl font-serif font-bold text-slate-900 mb-6 leading-tight">
                About Hello Alibaug <br class="hidden md:block">
                <span class="text-primary italic font-normal text-2xl md:text-4xl block mt-2">Your Hyperlocal Alibaug Travel & Lifestyle Guide</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-600 leading-relaxed max-w-3xl mx-auto">
                Hello Alibaug is a trusted hyperlocal platform and an Alibaug Travel Guide created to help people discover, live, and invest in Alibaug with confidence. Built with a deep love for this coastal destination, our platform connects residents, second-home owners, tourists, and entrepreneurs with everything that makes Alibaug unique and valuable.
            </p>
        </div>
    </div>

    <!-- Core Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-20">
        
        <!-- Section 1 -->
        <section class="prose prose-lg prose-slate max-w-none">
            <p class="lead text-lg text-slate-600">
                From scenic beaches and charming local cafés to reliable service providers, premium villas, and emerging real estate opportunities, Hello Alibaug brings together verified information and local insights in one place. Whether you are planning a weekend escape, exploring long-term living options, or building a business here, our Alibaug Travel Guide helps you experience the town like a local —informed, confident, and connected.
            </p>

            <h2 class="text-2xl md:text-3xl font-bold font-serif text-slate-900 mt-12 mb-6">A Hyperlocal Platform, Curated by Locals</h2>
            <p>
                Unlike generic travel websites or large listing portals, Hello Alibaug is curated by people who live, build, and grow in Alibaug. Our content is shaped by on-ground knowledge, real experiences, and a deep understanding of the region’s culture, geography, and lifestyle. This local-first approach allows us to highlight meaningful recommendations rather than promotional noise.
            </p>
            <p>
                As a practical and reliable Alibaug Travel Guide, we focus on helping users navigate the destination with clarity. From identifying trusted professionals to discovering lesser-known attractions, our goal is to simplify decision-making for anyone engaging with Alibaug.
            </p>
        </section>

        <!-- What you will discover -->
        <section class="bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-slate-100">
            <h2 class="text-2xl font-bold font-serif text-slate-900 mb-8 text-center">On Hello Alibaug, you will discover:</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50">
                    <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined">verified_user</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 mb-1">Trusted Services</h3>
                        <p class="text-sm text-slate-600 line-clamp-2">Alibaug local services and verified professionals.</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-4 p-4 rounded-2xl bg-amber-50">
                    <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined">villa</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 mb-1">Premium Stays</h3>
                        <p class="text-sm text-slate-600 line-clamp-2">Boutique stays, private villas, cafés, and restaurants.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4 p-4 rounded-2xl bg-emerald-50">
                    <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined">real_estate_agent</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 mb-1">Real Estate</h3>
                        <p class="text-sm text-slate-600 line-clamp-2">Listings and lifestyle-driven investment insights.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4 p-4 rounded-2xl bg-purple-50">
                    <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined">explore</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 mb-1">Hidden Gems</h3>
                        <p class="text-sm text-slate-600 line-clamp-2">Popular and lesser-known places to visit in Alibaug.</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 flex items-center gap-4 bg-slate-900 rounded-2xl p-6 text-white group cursor-pointer hover:bg-black transition-colors">
                <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center shrink-0 group-hover:bg-primary transition-colors">
                    <span class="material-symbols-outlined">campaign</span>
                </div>
                <div>
                    <h3 class="font-bold text-white mb-1">Community Hub</h3>
                    <p class="text-sm text-slate-300">Community updates, events, and local recommendations.</p>
                </div>
            </div>

            <p class="text-center text-sm text-slate-500 mt-8 italic max-w-2xl mx-auto">
                Every listing, guide, and feature on Hello Alibaug is designed to be authentic, relevant, and genuinely useful, helping users make informed decisions with confidence and ease.
            </p>
        </section>

        <!-- Section 3 & 4 -->
        <section class="prose prose-lg prose-slate max-w-none">
            <h2 class="text-2xl md:text-3xl font-bold font-serif text-slate-900 mt-12 mb-6">Supporting Local Businesses & Sustainable Growth</h2>
            <p>
                At the heart of Hello Alibaug is a strong belief in local pride, sustainability, and community collaboration. We actively support homegrown businesses, independent entrepreneurs, and dependable Alibaug local services that contribute positively to the region’s economy and long-term development.
            </p>
            <p>
                As Alibaug continues to grow into one of Mumbai’s most sought-after lifestyle and investment destinations, the need for responsible, well-informed growth becomes even more important. Hello Alibaug acts as a bridge—connecting the town’s traditional character with its modern, forward-looking future while preserving its natural beauty and cultural roots.
            </p>

            <h2 class="text-2xl md:text-3xl font-bold font-serif text-slate-900 mt-12 mb-6">Discover Places to Visit in Alibaug</h2>
            <p>
                Alibaug is known for its scenic coastline, historic forts, temples, beaches, and peaceful villages. However, discovering the right places to visit in Alibaug often requires local insight. Through our curated guides and recommendations, we help visitors explore both well-known attractions and hidden gems that offer a deeper connection to the region.
            </p>
            <p>
                Whether you are interested in nature, culture, food, or relaxation, Hello Alibaug helps you plan meaningful experiences by highlighting places that align with your interests and travel style.
            </p>

            <h2 class="text-2xl md:text-3xl font-bold font-serif text-slate-900 mt-12 mb-6">More Than a Website — A Local Alibaug Ecosystem</h2>
            <p>
                Hello Alibaug is more than just a website or directory. It is a growing digital ecosystem designed to bring people, places, and opportunities together in a transparent and responsible way. From travel planning and discovering places to visit in Alibaug to accessing trusted Alibaug local services and lifestyle resources, our platform supports long-term engagement with the destination.
            </p>
            <div class="bg-primary/5 rounded-2xl p-6 border-l-4 border-primary mt-8">
                <p class="text-xl font-medium text-slate-900 m-0">
                    If Alibaug matters to you—whether as a visitor, resident, or investor—you’re already part of our community. <br><br>
                    <strong>Welcome to Hello Alibaug. Discover Alibaug. Live it like a local.</strong>
                </p>
            </div>
        </section>

    </div>

    <!-- Final CTA Grid -->
    <div class="bg-slate-900 text-white py-20 border-t-4 border-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-serif font-bold mb-4">Why Hello Alibaug?</h2>
                <p class="text-slate-400">Join a movement that celebrates Alibaug’s unique identity.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center group">
                    <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center mx-auto mb-6 text-primary group-hover:bg-primary group-hover:text-white transition-all transform group-hover:scale-110">
                        <span class="material-symbols-outlined text-2xl">search</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Discover</h3>
                    <p class="text-slate-400 text-sm">Find curated listings, guides, and insider tips</p>
                </div>
                <div class="text-center group">
                    <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center mx-auto mb-6 text-blue-400 group-hover:bg-blue-500 group-hover:text-white transition-all transform group-hover:scale-110">
                        <span class="material-symbols-outlined text-2xl">group</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Connect</h3>
                    <p class="text-slate-400 text-sm">Explore the local community, talent, and services</p>
                </div>
                <div class="text-center group">
                    <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center mx-auto mb-6 text-amber-400 group-hover:bg-amber-500 group-hover:text-white transition-all transform group-hover:scale-110">
                        <span class="material-symbols-outlined text-2xl">storefront</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Promote</h3>
                    <p class="text-slate-400 text-sm">List your local business, villa, or event with us</p>
                </div>
                <div class="text-center group">
                    <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center mx-auto mb-6 text-purple-400 group-hover:bg-purple-500 group-hover:text-white transition-all transform group-hover:scale-110">
                        <span class="material-symbols-outlined text-2xl">favorite</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Belong</h3>
                    <p class="text-slate-400 text-sm">Join a movement that celebrates Alibaug’s unique identity</p>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
