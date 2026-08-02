{{--
    Reusable location picker.
    Params (via @include):
      $latitude, $longitude  — current values (nullable)
      $areas                 — collection of areas with id/latitude/longitude (for recentre-on-area-change)
    Emits hidden inputs named `latitude` and `longitude`.
    Reads the sibling <select name="area_id"> to recentre when the area changes.
--}}
@php
    $pickerId = 'locpick_' . uniqid();
    $curLat = old('latitude', $latitude ?? null);
    $curLng = old('longitude', $longitude ?? null);
    $areaCentroids = collect($areas ?? [])
        ->filter(fn ($a) => $a->latitude !== null && $a->longitude !== null)
        ->mapWithKeys(fn ($a) => [$a->id => [(float) $a->latitude, (float) $a->longitude]]);
@endphp

@once
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
              integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    @endpush
@endonce

<div class="location-picker" data-picker="{{ $pickerId }}" data-areas="{{ json_encode($areaCentroids) }}">
    <div class="flex items-center justify-between mb-2">
        <label class="block text-sm font-bold text-slate-700">Pin exact location</label>
        <button type="button" data-locate class="inline-flex items-center gap-1 text-xs font-semibold text-primary hover:underline">
            <span class="material-symbols-outlined text-[16px]">my_location</span> Use my location
        </button>
    </div>
    <p class="text-xs text-slate-500 mb-2">Search for your address, or drag the pin to mark exactly where your business is. This is what shows on the public map.</p>

    {{-- Address search — jumps the pin straight to a matched address. --}}
    <div class="relative mb-2" data-search-wrap>
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px] pointer-events-none">search</span>
        <input type="text" data-search autocomplete="off"
               placeholder="Search address or landmark — e.g. Nagaon Beach Road"
               class="w-full pl-10 pr-9 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-primary focus:ring-primary">
        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hidden" data-search-spinner>
            <span class="material-symbols-outlined text-[18px] animate-spin">progress_activity</span>
        </span>
        <div data-search-results
             class="absolute z-30 left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden max-h-60 overflow-y-auto hidden"></div>
    </div>

    <div id="{{ $pickerId }}" class="w-full h-64 rounded-xl overflow-hidden border border-slate-200 bg-slate-100" style="z-index:0;"></div>

    <div class="flex items-center gap-2 mt-2">
        <p class="text-xs text-slate-500" data-coords>
            @if($curLat && $curLng)
                Pinned at {{ number_format((float) $curLat, 5) }}, {{ number_format((float) $curLng, 5) }}
            @else
                No exact pin set — the area centre will be used until you drop one.
            @endif
        </p>
        <button type="button" data-clear class="text-xs text-slate-500 hover:text-red-500 underline ml-auto {{ ($curLat && $curLng) ? '' : 'hidden' }}">Clear pin</button>
    </div>

    {{-- Exact coordinates, editable. These are the real submitted fields, so the
         map and the boxes can never disagree about what gets saved. --}}
    <div class="grid grid-cols-2 gap-2 mt-2">
        <div>
            <label class="block text-[11px] font-semibold text-slate-500 mb-1">Latitude</label>
            <input type="text" name="latitude" value="{{ $curLat }}" data-lat inputmode="decimal"
                   placeholder="18.64140"
                   class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm font-mono focus:border-primary focus:ring-primary">
        </div>
        <div>
            <label class="block text-[11px] font-semibold text-slate-500 mb-1">Longitude</label>
            <input type="text" name="longitude" value="{{ $curLng }}" data-lng inputmode="decimal"
                   placeholder="72.87220"
                   class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm font-mono focus:border-primary focus:ring-primary">
        </div>
    </div>
    <p class="text-[11px] text-slate-500 mt-1">Have exact coordinates? Paste them here and the pin will move.</p>
</div>

@once
    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
                integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
        window.initLocationPicker = function (rootEl) {
            if (!window.L || rootEl.dataset.inited) return;
            rootEl.dataset.inited = '1';

            var ALIBAUG   = [18.6414, 72.8722];
            var container = rootEl.querySelector('div[id^="locpick_"]');
            var latInput  = rootEl.querySelector('[data-lat]');
            var lngInput  = rootEl.querySelector('[data-lng]');
            var coordsEl  = rootEl.querySelector('[data-coords]');
            var clearBtn  = rootEl.querySelector('[data-clear]');
            var locateBtn = rootEl.querySelector('[data-locate]');
            var areaCentroids = JSON.parse(rootEl.getAttribute('data-areas') || '{}');

            var hasPin = !!(latInput.value && lngInput.value);
            var start  = hasPin ? [parseFloat(latInput.value), parseFloat(lngInput.value)] : ALIBAUG;

            var map = L.map(container, { scrollWheelZoom: false }).setView(start, hasPin ? 15 : 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap', maxZoom: 19
            }).addTo(map);

            var marker = L.marker(start, { draggable: true, opacity: hasPin ? 1 : 0.35 }).addTo(map);

            function setPin(latlng, zoom) {
                marker.setLatLng(latlng).setOpacity(1);
                latInput.value = latlng.lat.toFixed(7);
                lngInput.value = latlng.lng.toFixed(7);
                coordsEl.textContent = 'Pinned at ' + latlng.lat.toFixed(5) + ', ' + latlng.lng.toFixed(5);
                clearBtn.classList.remove('hidden');
                if (zoom) map.setView(latlng, zoom);
            }

            marker.on('dragend', function () { setPin(marker.getLatLng()); });
            map.on('click', function (e) { setPin(e.latlng); });

            clearBtn.addEventListener('click', function () {
                latInput.value = ''; lngInput.value = '';
                marker.setOpacity(0.35);
                coordsEl.textContent = 'No exact pin set — the area centre will be used until you drop one.';
                clearBtn.classList.add('hidden');
            });

            // ── Typing coordinates directly moves the pin ──────────────────
            function applyTypedCoords() {
                var lat = parseFloat(latInput.value);
                var lng = parseFloat(lngInput.value);
                if (isNaN(lat) || isNaN(lng)) return;
                if (lat < -90 || lat > 90 || lng < -180 || lng > 180) {
                    coordsEl.textContent = 'Those coordinates aren’t valid — latitude is -90 to 90, longitude -180 to 180.';
                    return;
                }
                var ll = L.latLng(lat, lng);
                marker.setLatLng(ll).setOpacity(1);
                coordsEl.textContent = 'Pinned at ' + lat.toFixed(5) + ', ' + lng.toFixed(5);
                clearBtn.classList.remove('hidden');
                map.setView(ll, 16);
            }
            [latInput, lngInput].forEach(function (input) {
                input.addEventListener('change', applyTypedCoords);
                input.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') { e.preventDefault(); applyTypedCoords(); }
                });
            });

            // ── Address autocomplete ───────────────────────────────────────
            var searchInput = rootEl.querySelector('[data-search]');
            var resultsEl   = rootEl.querySelector('[data-search-results]');
            var spinnerEl   = rootEl.querySelector('[data-search-spinner]');
            var searchWrap  = rootEl.querySelector('[data-search-wrap]');

            if (searchInput && resultsEl) {
                var searchTimer = null;
                var lastQuery = '';
                var activeController = null;

                function hideResults() { resultsEl.classList.add('hidden'); resultsEl.innerHTML = ''; }

                function renderResults(items) {
                    if (!items.length) {
                        resultsEl.innerHTML = '<div class="px-4 py-3 text-sm text-slate-500">No matches in the Alibaug area — try a nearby landmark, or drag the pin.</div>';
                        resultsEl.classList.remove('hidden');
                        return;
                    }
                    resultsEl.innerHTML = '';
                    items.forEach(function (item) {
                        var btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'w-full text-left px-4 py-2.5 hover:bg-primary/5 border-b border-slate-50 last:border-0 transition-colors';
                        var main = document.createElement('p');
                        main.className = 'text-sm font-semibold text-slate-800';
                        main.textContent = item.label;
                        btn.appendChild(main);
                        if (item.detail) {
                            var sub = document.createElement('p');
                            sub.className = 'text-xs text-slate-500';
                            sub.textContent = item.detail;
                            btn.appendChild(sub);
                        }
                        btn.addEventListener('click', function () {
                            setPin(L.latLng(item.lat, item.lon), 17);
                            searchInput.value = item.label;
                            hideResults();
                        });
                        resultsEl.appendChild(btn);
                    });
                    resultsEl.classList.remove('hidden');
                }

                searchInput.addEventListener('input', function () {
                    var q = searchInput.value.trim();
                    clearTimeout(searchTimer);

                    if (q.length < 3) { hideResults(); return; }
                    if (q === lastQuery) return;

                    // Debounced so a typed address is a few requests, not one per keystroke.
                    searchTimer = setTimeout(function () {
                        lastQuery = q;
                        if (activeController) activeController.abort();
                        activeController = new AbortController();
                        spinnerEl.classList.remove('hidden');

                        fetch('{{ route('places.search') }}?q=' + encodeURIComponent(q), {
                            headers: { 'Accept': 'application/json' },
                            signal: activeController.signal,
                        })
                            .then(function (r) { return r.ok ? r.json() : { results: [] }; })
                            .then(function (data) {
                                spinnerEl.classList.add('hidden');
                                renderResults(data.results || []);
                            })
                            .catch(function (err) {
                                if (err.name === 'AbortError') return;
                                spinnerEl.classList.add('hidden');
                                hideResults();
                            });
                    }, 350);
                });

                searchInput.addEventListener('keydown', function (e) {
                    // The picker sits inside a form — Enter must search, not submit.
                    if (e.key === 'Enter') e.preventDefault();
                    if (e.key === 'Escape') hideResults();
                });

                document.addEventListener('click', function (e) {
                    if (searchWrap && !searchWrap.contains(e.target)) hideResults();
                });
            }

            if (locateBtn) {
                locateBtn.addEventListener('click', function () {
                    if (!navigator.geolocation) {
                        coordsEl.textContent = 'Location is not supported by this browser — drag the pin instead.';
                        return;
                    }
                    var original = locateBtn.innerHTML;
                    locateBtn.disabled = true;
                    locateBtn.innerHTML = '<span class="material-symbols-outlined text-[16px]">hourglass_top</span> Locating…';
                    navigator.geolocation.getCurrentPosition(function (pos) {
                        setPin(L.latLng(pos.coords.latitude, pos.coords.longitude), 16);
                        locateBtn.disabled = false;
                        locateBtn.innerHTML = original;
                    }, function (err) {
                        locateBtn.disabled = false;
                        locateBtn.innerHTML = original;
                        coordsEl.textContent = err.code === err.PERMISSION_DENIED
                            ? 'Location permission was blocked — allow it in your browser, or just drag the pin.'
                            : 'Couldn’t get your location — drag the pin to set it manually.';
                    }, { enableHighAccuracy: true, timeout: 10000 });
                });
            }

            // Recentre on area change (only while the user hasn't dropped a pin yet).
            var areaSelect = document.querySelector('select[name="area_id"]');
            if (areaSelect) {
                areaSelect.addEventListener('change', function () {
                    var c = areaCentroids[areaSelect.value];
                    if (c && !latInput.value) { map.setView(c, 14); }
                });
                if (!hasPin && areaCentroids[areaSelect.value]) {
                    map.setView(areaCentroids[areaSelect.value], 13);
                }
            }

            // Leaflet renders grey tiles if built inside a hidden container (wizard
            // steps / tabs). Recalculate size on first reveal and on window resize.
            setTimeout(function () { map.invalidateSize(); }, 200);
            window.addEventListener('resize', function () { map.invalidateSize(); });
            if ('IntersectionObserver' in window) {
                var io = new IntersectionObserver(function (entries) {
                    entries.forEach(function (e) {
                        if (e.isIntersecting) { map.invalidateSize(); }
                    });
                }, { threshold: 0.1 });
                io.observe(container);
            }
            rootEl._leafletMap = map;
        };

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.location-picker').forEach(window.initLocationPicker);
        });
        </script>
    @endpush
@endonce
