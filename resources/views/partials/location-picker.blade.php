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
    <p class="text-xs text-slate-500 mb-2">Drag the pin (or tap the map) to mark exactly where your business is. This is what shows on the public map.</p>
    <div id="{{ $pickerId }}" class="w-full h-64 rounded-xl overflow-hidden border border-slate-200 bg-slate-100" style="z-index:0;"></div>
    <div class="flex items-center gap-2 mt-2">
        <p class="text-xs text-slate-400" data-coords>
            @if($curLat && $curLng)
                Pinned at {{ number_format((float) $curLat, 5) }}, {{ number_format((float) $curLng, 5) }}
            @else
                No exact pin set — the area centre will be used until you drop one.
            @endif
        </p>
        <button type="button" data-clear class="text-xs text-slate-400 hover:text-red-500 underline ml-auto {{ ($curLat && $curLng) ? '' : 'hidden' }}">Clear pin</button>
    </div>

    <input type="hidden" name="latitude" value="{{ $curLat }}" data-lat>
    <input type="hidden" name="longitude" value="{{ $curLng }}" data-lng>
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

            if (locateBtn) {
                locateBtn.addEventListener('click', function () {
                    if (!navigator.geolocation) return;
                    locateBtn.disabled = true;
                    navigator.geolocation.getCurrentPosition(function (pos) {
                        setPin(L.latLng(pos.coords.latitude, pos.coords.longitude), 16);
                        locateBtn.disabled = false;
                    }, function () { locateBtn.disabled = false; }, { enableHighAccuracy: true, timeout: 8000 });
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
