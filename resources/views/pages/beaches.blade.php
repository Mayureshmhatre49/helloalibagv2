@extends('layouts.app')
@section('title', 'Alibaug Beaches Guide — Which Beach to Visit & Where to Swim')
@section('meta_description', 'A guide to every beach around Alibaug — Alibaug, Varsoli, Kihim, Awas, Sasawane, Nagaon, Akshi, Kashid, Mandwa, Revdanda and Korlai. Which are best for water sports, which are quiet, and where swimming is safe.')

@section('jsonld')
@include('partials.schema.itemlist', ['beaches' => [
    ['name' => 'Alibaug Beach', 'description' => 'Kolaba Fort walk at low tide; busy; limited water sports.'],
    ['name' => 'Varsoli Beach', 'description' => 'Quiet walks, cleaner sand, ~2 km north of Alibaug town.'],
    ['name' => 'Awas Beach', 'description' => 'Long empty stretches, casuarina groves.'],
    ['name' => 'Sasawane Beach', 'description' => 'Calm shallows, casuarina shade, Karmarkar sculpture museum.'],
    ['name' => 'Kihim Beach', 'description' => 'Shaded groves, coastal flora, shallow shore.'],
    ['name' => 'Mandwa Beach', 'description' => 'Ferry port, jet ski, banana rides, Mumbai views.'],
    ['name' => 'Akshi Beach', 'description' => 'Flat firm sand, wading, shorebirds.'],
    ['name' => 'Nagaon Beach', 'description' => 'Main water sports hub — parasailing, jet ski, banana rides.'],
    ['name' => 'Kashid Beach', 'description' => 'White sand, high surf, parasailing, beach shacks.'],
    ['name' => 'Revdanda Beach', 'description' => 'Black sand, portuguese fort ruins, camping.'],
    ['name' => 'Korlai Beach & Lighthouse', 'description' => 'Lighthouse views, rocky shore, portuguese fort.'],
]])
@include('partials.schema.breadcrumbs', ['crumbs' => [
    ['label' => 'Home', 'url' => route('home')],
    ['label' => 'Beaches Guide', 'url' => route('page.beaches')],
]])
@endsection
<main class="bg-slate-50 min-h-screen">

    {{-- Hero --}}
    <div class="bg-white border-b border-slate-200 pt-16 pb-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 bg-sky-50 text-sky-700 px-4 py-2 rounded-full text-sm font-bold mb-6">
                <span class="material-symbols-outlined text-[18px]">beach_access</span> Beach Guide
            </div>
            <h1 class="text-3xl md:text-5xl font-serif font-bold text-slate-900 mb-4">Alibaug Beaches</h1>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">Eleven beaches sit within an hour of Alibaug town, and they are not interchangeable. Some are built for water sports and crowds, others are for a quiet morning walk. Here is how to pick the right one.</p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- Comparison table --}}
        <h2 class="text-2xl font-serif font-bold text-slate-900 mb-2">Compare at a glance</h2>
        <p class="text-sm text-slate-600 mb-5">Distances are approximate, measured from Alibaug town centre.</p>

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm mb-12">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="text-left px-5 py-4 font-bold text-slate-700">Beach</th>
                            <th class="text-left px-5 py-4 font-bold text-slate-700">Distance</th>
                            <th class="text-left px-5 py-4 font-bold text-slate-700">Best for</th>
                            <th class="text-left px-5 py-4 font-bold text-slate-700">Water sports</th>
                            <th class="text-left px-5 py-4 font-bold text-slate-700">Crowd</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php
                            $beaches = [
                                ['Alibaug Beach', '0 km', 'Kolaba Fort walk at low tide, sunset', 'Limited', 'Busy'],
                                ['Varsoli Beach', '~2 km N', 'Quiet walks, cleaner sand', 'No', 'Quiet'],
                                ['Awas Beach', '~7 km N', 'Long empty stretches, birdlife', 'No', 'Very quiet'],
                                ['Sasawane Beach', '~9 km N', 'Calm shallows, casuarina shade', 'No', 'Very quiet'],
                                ['Kihim Beach', '~11 km N', 'Butterflies, wildflowers, shaded groves', 'Limited', 'Moderate'],
                                ['Mandwa Beach', '~20 km N', 'Mumbai skyline views, ferry arrivals', 'Limited', 'Moderate'],
                                ['Akshi Beach', '~5 km S', 'Long walks, suru tree cover', 'No', 'Quiet'],
                                ['Nagaon Beach', '~9 km S', 'Water sports, beach shacks, food', 'Yes — main hub', 'Very busy'],
                                ['Revdanda Beach', '~17 km S', 'Fort ruins, coconut groves', 'No', 'Quiet'],
                                ['Korlai Beach', '~22 km S', 'Lighthouse, Portuguese fort', 'No', 'Very quiet'],
                                ['Kashid Beach', '~30 km S', 'White sand, clearest water, day trip', 'Yes', 'Busy on weekends'],
                            ];
                        @endphp
                        @foreach($beaches as [$name, $dist, $bestFor, $sports, $crowd])
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 py-4 font-semibold text-slate-900 whitespace-nowrap">{{ $name }}</td>
                                <td class="px-5 py-4 text-slate-600 whitespace-nowrap">{{ $dist }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $bestFor }}</td>
                                <td class="px-5 py-4">
                                    @if($sports === 'No')
                                        <span class="text-slate-500 text-xs font-semibold">None</span>
                                    @elseif($sports === 'Limited')
                                        <span class="bg-amber-50 text-amber-700 px-2 py-1 rounded-md text-xs font-bold">Limited</span>
                                    @else
                                        <span class="bg-emerald-50 text-emerald-700 px-2 py-1 rounded-md text-xs font-bold">{{ $sports }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-slate-500 text-xs">{{ $crowd }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Safety — deliberately high on the page, not buried at the bottom --}}
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6 mb-12">
            <h2 class="text-lg font-bold text-red-900 mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-[22px]">warning</span>
                Before you swim — read this
            </h2>
            <ul class="space-y-2 text-sm text-red-900/90">
                <li><strong>The Konkan coast has strong undercurrents.</strong> Drownings happen here every year, most often to strong swimmers who underestimated the pull. Stay where you can stand.</li>
                <li><strong>Do not enter the sea during the monsoon (June–September).</strong> The water is rough, visibility is poor, and swimming is frequently prohibited outright. Local bans exist for good reason.</li>
                <li><strong>Check the tide.</strong> At several beaches the water withdraws hundreds of metres at low tide and returns quickly. The Kolaba Fort causeway floods — people get stranded there regularly.</li>
                <li><strong>Lifeguards are not present at most beaches</strong>, and not at all outside peak season. Assume nobody is watching.</li>
                <li><strong>Never swim after drinking</strong>, and never let children in the water unsupervised.</li>
                <li><strong>Jellyfish</strong> appear seasonally. If stung, rinse with seawater — not fresh water — and seek medical help if the reaction spreads.</li>
            </ul>
            <p class="text-sm text-red-900 mt-4 pt-4 border-t border-red-200">
                In an emergency, dial <a href="tel:112" class="font-bold underline">112</a>.
                See our <a href="{{ route('page.emergency') }}" class="font-bold underline">emergency contacts page</a> for local hospitals and police.
            </p>
        </div>

        {{-- North of town --}}
        <h2 class="text-2xl font-serif font-bold text-slate-900 mb-5">North of Alibaug town</h2>
        <div class="grid gap-4 mb-12">
            @php
                $north = [
                    ['Varsoli Beach', '~2 km', 'The closest escape from the main beach and noticeably cleaner. A long, straight stretch backed by casuarina trees, with a naval station at one end. Popular with early-morning walkers and joggers rather than day-trippers.', 'Sunrise walks, a swim close to town, quiet evenings.'],
                    ['Awas Beach', '~7 km', 'One of the emptiest beaches in the area — wide, flat and often deserted on weekdays. Wading birds are common in the shallows. There is very little infrastructure, so bring what you need.', 'Solitude, photography, birdwatching.'],
                    ['Sasawane Beach', '~9 km', 'A small, calm beach with shallow water and shade from casuarina groves. Its size keeps crowds away even in season, and it works well for families with young children who just want to paddle.', 'Families, shade, calm shallow water.'],
                    ['Kihim Beach', '~11 km', 'Known for the butterflies and wildflowers that ornithologist Salim Ali wrote about, best seen just after the monsoon. Shaded groves run right down to the sand, and there are more places to stay and eat here than at the quieter northern beaches.', 'Nature walks, post-monsoon greenery, a full day out.'],
                    ['Mandwa Beach', '~20 km', 'Where most visitors from Mumbai first touch land — the jetty here receives the ferries. On a clear day you can see the Mumbai skyline across the water. The beach itself is pleasant but functional rather than scenic.', 'Skyline views, first or last stop around a ferry.'],
                ];
            @endphp
            @foreach($north as [$name, $dist, $desc, $bestFor])
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <div class="flex items-baseline justify-between gap-4 mb-2 flex-wrap">
                        <h3 class="text-lg font-bold text-slate-900">{{ $name }}</h3>
                        <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full whitespace-nowrap">{{ $dist }} from town</span>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed mb-3">{{ $desc }}</p>
                    <p class="text-xs text-slate-500"><span class="font-bold text-slate-700">Go for:</span> {{ $bestFor }}</p>
                </div>
            @endforeach
        </div>

        {{-- South of town --}}
        <h2 class="text-2xl font-serif font-bold text-slate-900 mb-5">South of Alibaug town</h2>
        <div class="grid gap-4 mb-12">
            @php
                $south = [
                    ['Akshi Beach', '~5 km', 'A long, quiet beach lined with suru trees, popular with people who want to walk for an hour without meeting anyone. The sand is firm enough to cycle on in places.', 'Long walks, cycling, quiet mornings.'],
                    ['Nagaon Beach', '~9 km', 'The busiest beach in the area and the centre of the water sports trade. Expect parasailing, jet skis, banana boats and bumper rides, along with a line of shacks selling Konkani seafood. Lively rather than peaceful — come here for activity, not quiet.', 'Water sports, food, groups and families.'],
                    ['Revdanda Beach', '~17 km', 'Backed by coconut groves, with the ruins of a 16th-century Portuguese fort at one end that you can walk through. Fewer visitors than Nagaon and a better sense of history.', 'Fort ruins, photography, a half-day trip.'],
                    ['Korlai Beach', '~22 km', 'Beneath Korlai Fort and its lighthouse, with a small fishing village nearby where a Portuguese-influenced creole is still spoken. Rocky in stretches and rarely crowded.', 'Fort and lighthouse, local history, seclusion.'],
                    ['Kashid Beach', '~30 km', 'The most scenic beach in the region and worth the drive — white sand and noticeably bluer water than the beaches nearer town, framed by hills. Weekends get genuinely crowded; weekdays are far better. Water sports operate here in season.', 'A full day trip, the best swimming water, water sports.'],
                ];
            @endphp
            @foreach($south as [$name, $dist, $desc, $bestFor])
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <div class="flex items-baseline justify-between gap-4 mb-2 flex-wrap">
                        <h3 class="text-lg font-bold text-slate-900">{{ $name }}</h3>
                        <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full whitespace-nowrap">{{ $dist }} from town</span>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed mb-3">{{ $desc }}</p>
                    <p class="text-xs text-slate-500"><span class="font-bold text-slate-700">Go for:</span> {{ $bestFor }}</p>
                </div>
            @endforeach
        </div>

        {{-- Water sports --}}
        <h2 class="text-2xl font-serif font-bold text-slate-900 mb-2">Water sports</h2>
        <p class="text-sm text-slate-600 mb-5">Concentrated at <strong>Nagaon</strong> and <strong>Kashid</strong>, with limited operations at Alibaug, Kihim and Mandwa. Most operate roughly October to May and shut during the monsoon.</p>

        <div class="grid sm:grid-cols-2 gap-4 mb-6">
            @php
                $sports = [
                    ['parasailing', 'Parasailing', 'Towed behind a speedboat, a few minutes airborne. The most popular activity at Nagaon.'],
                    ['sailing', 'Jet ski', 'Short solo or pillion rides within a marked stretch of water.'],
                    ['kayaking', 'Banana &amp; bumper rides', 'Inflatables towed at speed — done in groups, and the most likely to end with everyone in the water.'],
                    ['directions_boat', 'Speedboat rides', 'A quick loop out and back. Usually the calmest option available.'],
                ];
            @endphp
            @foreach($sports as [$icon, $name, $desc])
                <div class="bg-white rounded-xl border border-slate-200 p-5 flex gap-4">
                    <span class="material-symbols-outlined text-sky-600 text-[24px] shrink-0">{{ $icon }}</span>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm mb-1">{!! $name !!}</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $desc }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 mb-12">
            <h3 class="font-bold text-amber-900 text-sm mb-2">Booking water sports safely</h3>
            <ul class="text-xs text-amber-900/90 space-y-1.5">
                <li>Rates are set by individual operators, change with season and demand, and are usually negotiable — agree the price <em>before</em> you start, not after.</li>
                <li>Insist on a life jacket for every activity, and check the straps yourself.</li>
                <li>Choose operators with visibly maintained equipment. If a boat or harness looks worn, walk away.</li>
                <li>Confirm what is included — some quoted prices cover one short ride only.</li>
                <li>Activities are suspended in rough weather. That call is for your safety; do not push an operator to run anyway.</li>
            </ul>
        </div>

        {{-- When to visit --}}
        <h2 class="text-2xl font-serif font-bold text-slate-900 mb-5">When to visit</h2>
        <div class="grid sm:grid-cols-3 gap-4 mb-12">
            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <div class="text-xs font-bold text-emerald-700 bg-emerald-50 inline-block px-2.5 py-1 rounded-full mb-2">Best · Nov–Feb</div>
                <p class="text-xs text-slate-600 leading-relaxed">Cool, dry and clear. Every beach and all water sports are open. Weekends and holidays get crowded — arrive early.</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <div class="text-xs font-bold text-amber-700 bg-amber-50 inline-block px-2.5 py-1 rounded-full mb-2">Warm · Mar–May</div>
                <p class="text-xs text-slate-600 leading-relaxed">Hot and humid by midday. Go early morning or after 4pm. Water sports still run, and beaches are quieter than in winter.</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <div class="text-xs font-bold text-slate-600 bg-slate-100 inline-block px-2.5 py-1 rounded-full mb-2">Monsoon · Jun–Sep</div>
                <p class="text-xs text-slate-600 leading-relaxed">Dramatically green and largely empty, but the sea is dangerous, swimming is unsafe or banned, and water sports shut down. Come for the landscape, not the water.</p>
            </div>
        </div>

        {{-- Cross-links --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Plan the rest of your trip</h2>
            <div class="grid sm:grid-cols-2 gap-3">
                <a href="{{ route('page.ferry-schedule') }}" class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:border-primary hover:bg-primary/5 transition-colors">
                    <span class="material-symbols-outlined text-primary">directions_boat</span>
                    <span class="text-sm font-semibold text-slate-800">Ferry timings from Mumbai</span>
                </a>
                <a href="{{ route('map.index') }}" class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:border-primary hover:bg-primary/5 transition-colors">
                    <span class="material-symbols-outlined text-primary">map</span>
                    <span class="text-sm font-semibold text-slate-800">See everything on the map</span>
                </a>
                <a href="{{ route('search', ['category_id' => optional(\App\Models\Category::where('slug', 'stay')->first())->id]) }}" class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:border-primary hover:bg-primary/5 transition-colors">
                    <span class="material-symbols-outlined text-primary">hotel</span>
                    <span class="text-sm font-semibold text-slate-800">Places to stay near the beach</span>
                </a>
                <a href="{{ route('page.emergency') }}" class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:border-primary hover:bg-primary/5 transition-colors">
                    <span class="material-symbols-outlined text-primary">emergency</span>
                    <span class="text-sm font-semibold text-slate-800">Emergency contacts</span>
                </a>
            </div>
        </div>

        <p class="text-xs text-slate-500 mt-6 text-center">Distances are approximate and conditions change with the season and tide. Confirm locally before setting out, especially during the monsoon.</p>
    </div>
</main>
@endsection
