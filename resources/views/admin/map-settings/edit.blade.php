@extends('layouts.admin')
@section('page-title', 'Google Maps API')

@section('content')
<div class="max-w-3xl space-y-6">

    {{-- ── Status banner ──────────────────────────────────────────── --}}
    @if($settings->auto_disabled_at)
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-3">
            <span class="material-symbols-outlined text-amber-600 text-[22px]">warning</span>
            <div>
                <p class="text-sm font-bold text-amber-900">Auto-disabled — quota safety cutoff triggered</p>
                <p class="text-xs text-amber-700 mt-0.5">{{ $settings->auto_disabled_reason }}</p>
                <p class="text-[11px] text-amber-600 mt-1">{{ $settings->auto_disabled_at->format('d M Y, g:i A') }}. Re-enable below once you've reviewed usage, or raise the free-cap numbers if Google's published limits have changed.</p>
            </div>
        </div>
    @elseif($settings->enabled)
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-center gap-3">
            <span class="material-symbols-outlined text-emerald-600 text-[22px]">check_circle</span>
            <p class="text-sm font-bold text-emerald-900">Google Maps is enabled — the /map page and listing location search are live.</p>
        </div>
    @else
        <div class="bg-slate-100 border border-slate-200 rounded-2xl p-4 flex items-center gap-3">
            <span class="material-symbols-outlined text-slate-500 text-[22px]">toggle_off</span>
            <p class="text-sm font-bold text-slate-700">Google Maps is disabled — /map redirects home, listing search uses the free Geoapify fallback.</p>
        </div>
    @endif

    {{-- ── Enable / disable toggle ────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex items-center justify-between gap-4">
        <div>
            <h2 class="text-sm font-bold text-slate-900">{{ $settings->enabled ? 'Disable' : 'Enable' }} the integration</h2>
            <p class="text-xs text-slate-500 mt-0.5">
                @if(empty($settings->api_key))
                    Add an API key below first.
                @else
                    Instantly flips the /map page and listing location search on or off site-wide.
                @endif
            </p>
        </div>
        <form method="POST" action="{{ route('admin.map-settings.toggle') }}">
            @csrf
            @method('PATCH')
            <button type="submit" @if(empty($settings->api_key)) disabled @endif
                class="px-5 py-2.5 rounded-xl font-bold text-sm transition-colors
                    {{ $settings->enabled ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-primary text-white hover:bg-primary-dark' }}
                    disabled:opacity-40 disabled:cursor-not-allowed">
                {{ $settings->enabled ? 'Disable' : 'Enable' }}
            </button>
        </form>
    </div>

    {{-- ── Settings form ──────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-900 mb-1">API key & limits</h2>
        <p class="text-xs text-slate-500 mb-4">The key is encrypted at rest. It still ships to visitors' browsers when the /map page loads — protect it with HTTP-referrer and API restrictions in Google Cloud Console, not just here.</p>

        <form method="POST" action="{{ route('admin.map-settings.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Google Maps API key</label>
                <input type="password" name="api_key" autocomplete="off"
                       placeholder="{{ $settings->api_key ? '•••••••••••••••• (saved — leave blank to keep it)' : 'Paste your Google Maps API key' }}"
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-mono focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                @error('api_key') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Map ID <span class="text-slate-400 font-normal">(optional)</span></label>
                <input type="text" name="map_id" value="{{ old('map_id', $settings->map_id) }}"
                       placeholder="e.g. 8f1a2b3c4d5e6f7g"
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-mono focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                <p class="text-[11px] text-slate-500 mt-1">From Cloud Console → Google Maps Platform → Map Management. Enables the styled pill-shaped markers on /map; without it, the map still works with plain colored pins.</p>
                @error('map_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Free map loads / month</label>
                    <input type="number" name="monthly_free_limit_map_loads" min="1" required
                           value="{{ old('monthly_free_limit_map_loads', $settings->monthly_free_limit_map_loads) }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    @error('monthly_free_limit_map_loads') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Free location searches / month</label>
                    <input type="number" name="monthly_free_limit_search" min="1" required
                           value="{{ old('monthly_free_limit_search', $settings->monthly_free_limit_search) }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    @error('monthly_free_limit_search') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <p class="text-[11px] text-slate-500 -mt-2">Pre-filled with Google's current published free caps (10,000/month each). Adjust if Google changes these.</p>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Auto-disable threshold</label>
                <div class="flex items-center gap-2">
                    <input type="number" name="auto_disable_threshold_percent" min="50" max="100" required
                           value="{{ old('auto_disable_threshold_percent', $settings->auto_disable_threshold_percent) }}"
                           class="w-24 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    <span class="text-sm text-slate-500">% of the free cap — the integration auto-disables itself once either counter reaches this, so it never spends into the paid tier.</span>
                </div>
                @error('auto_disable_threshold_percent') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="bg-primary text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-primary-dark transition-colors">Save settings</button>
        </form>
    </div>

    {{-- ── Usage this month ───────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-900 mb-1">Usage this month</h2>
        <p class="text-xs text-slate-500 mb-4">Resets on the 1st of each month (calendar month, not necessarily Google's own billing-cycle date).</p>

        <div class="space-y-4">
            @foreach($usage as $row)
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-semibold text-slate-800">{{ $row['label'] }}</span>
                        <span class="text-xs font-bold {{ $row['percent'] >= 95 ? 'text-red-600' : ($row['percent'] >= 75 ? 'text-amber-600' : 'text-slate-500') }}">
                            {{ number_format($row['used']) }} / {{ number_format($row['limit']) }} ({{ $row['percent'] }}%)
                        </span>
                    </div>
                    <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full {{ $row['percent'] >= 95 ? 'bg-red-500' : ($row['percent'] >= 75 ? 'bg-amber-500' : 'bg-primary') }}"
                             style="width: {{ $row['percent'] }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100 text-[11px] text-slate-500 space-y-1">
            <p><strong>Map page loads</strong> are counted once per page view (an accurate proxy — Google bills per map initialization, and each view initializes the map once).</p>
            <p><strong>Location searches</strong> are counted exactly once per outbound call our server makes to Google (repeat searches within a 6-hour cache window aren't re-counted, matching what Google would actually bill).</p>
            <p>These are our own best-effort counters, not Google's billing meter — always cross-check the authoritative number in Cloud Console → APIs &amp; Services → Metrics.</p>
        </div>
    </div>

    {{-- ── Setup reference ────────────────────────────────────────── --}}
    <details class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <summary class="p-6 cursor-pointer text-sm font-bold text-slate-900 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-slate-400">help</span>
            How to get a Google Maps API key
        </summary>
        <div class="px-6 pb-6 text-xs text-slate-600 space-y-2 border-t border-slate-100 pt-4">
            <p>1. In <a href="https://console.cloud.google.com/" target="_blank" rel="noopener" class="text-primary underline">Google Cloud Console</a>, create (or select) a project and attach a billing account.</p>
            <p>2. Under APIs &amp; Services → Library, enable exactly two APIs: <strong>Maps JavaScript API</strong> and <strong>Places API (New)</strong>. Nothing else is needed for this integration.</p>
            <p>3. Under APIs &amp; Services → Credentials, create an API key. Restrict it: an <strong>HTTP referrer</strong> restriction limited to your domain(s), and an <strong>API restriction</strong> limited to the two APIs above.</p>
            <p>4. (Optional) Under Google Maps Platform → Map Management, create a Map ID for styled markers, and paste it above.</p>
            <p>5. Under Google Maps Platform → Quotas, set a daily request cap per API as a second safety net alongside the auto-disable threshold above.</p>
            <p>6. Under Billing → Budgets &amp; alerts, set a budget alert (e.g. ₹1) so you're notified by email even if both safety nets somehow fail.</p>
            <p>7. Paste the key above and hit Enable.</p>
        </div>
    </details>
</div>
@endsection
