{{--
  Leaflet.js Map Component
  Usage: @include('components.map', ['lat' => $listing->latitude, 'lng' => $listing->longitude, 'title' => $listing->title])
  Only renders when lat/lng are set.
--}}
@if(!empty($lat) && !empty($lng))
<div class="rounded-2xl overflow-hidden border border-slate-200 shadow-sm h-64 relative" id="listing-map-wrapper">
    <div id="listing-map" class="w-full h-full"></div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
(function () {
    const lat   = {{ (float) $lat }};
    const lng   = {{ (float) $lng }};
    const title = @json($title ?? 'Location');

    const map = L.map('listing-map', { zoomControl: true, scrollWheelZoom: false })
        .setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 18,
    }).addTo(map);

    // Custom marker
    const icon = L.divIcon({
        html: `<div style="background:#1183d4;width:36px;height:36px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 2px 10px rgba(0,0,0,0.3)"></div>`,
        iconSize: [36, 36],
        iconAnchor: [18, 36],
        popupAnchor: [0, -40],
        className: '',
    });

    L.marker([lat, lng], { icon })
        .addTo(map)
        .bindPopup(`<strong>${title}</strong>`)
        .openPopup();
})();
</script>
@endpush
@endif
