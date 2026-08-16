@extends('layouts.app')

@section('title', 'Alibaug Map — Explore Stays, Restaurants & Experiences by Location')
@section('meta_description', 'Interactive map of Alibaug showing villas, restaurants, beaches, and experiences across Mandwa, Kihim, Nagaon, Kashid, and more. Browse by location to plan your trip.')

@push('styles')
<style>
    /* Fixed pane height so the map initialises with a known, non-zero size. */
    .ha-map-pane { height: 600px; }
    @media (min-width: 1024px) { .ha-map-pane { height: 720px; } }

    /* Contain the map's high internal z-indexes inside the map section stacking context */
    .ha-map-section { position: relative; z-index: 0; }

    .ha-marker {
        background: white;
        border-radius: 9999px;
        padding: 5px 10px;
        font-weight: 700;
        font-size: 11.5px;
        color: #0d161b;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.22);
        border: 2px solid white;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: transform 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease, color 0.15s ease;
        cursor: pointer;
        pointer-events: auto;
    }
    .ha-marker:hover { transform: translateY(-2px) scale(1.06); box-shadow: 0 8px 22px rgba(0,0,0,0.3); z-index: 10000; }
    .ha-marker__dot { width: 8px; height: 8px; border-radius: 9999px; flex-shrink: 0; }
    .ha-marker.is-active { background: #0d161b; color: white; border-color: #f5c842; transform: translateY(-2px) scale(1.08); }
</style>
@endpush

@section('content')
@php
    $listingCount = count($markers);
@endphp

{{-- ── HEADER STRIP ────────────────────────────────────────────────────── --}}
<section class="bg-white border-b border-border-light">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 py-5 flex items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-text-secondary text-xs font-medium mb-1.5" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-slate-700">Map</span>
            </nav>
            <h1 class="text-slate-900 font-serif font-bold text-2xl sm:text-3xl tracking-tight flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[28px]" style="font-variation-settings:'FILL' 1">map</span>
                Alibaug on the Map
            </h1>
            <p class="text-text-secondary text-sm mt-1">{{ $listingCount }} listings across {{ count($categories) }} categories</p>
        </div>
        <a href="{{ route('search') }}"
           class="hidden md:inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-sm px-4 py-2.5 rounded-xl transition-colors">
            <span class="material-symbols-outlined text-[18px]">view_list</span>
            List view
        </a>
    </div>
</section>

{{-- ── MAP LAYOUT ──────────────────────────────────────────────────────── --}}
<section class="bg-slate-50 ha-map-section">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 py-6">
        <div class="grid lg:grid-cols-[340px_1fr] gap-5"
             x-data="haMap({
                 markers: @js($markers),
                 categories: @js($categories),
                 colorByCategory: @js(collect($categories)->pluck('color','slug')->all()),
                 mapId: @js($googleMapId),
             })"
             x-init="initMap($refs.mapEl)">

            {{-- Sidebar: filters + list --}}
            <aside class="ha-map-pane bg-white border border-border-light rounded-2xl overflow-hidden flex flex-col">
                {{-- Filters --}}
                <div class="p-4 border-b border-border-light">
                    <p class="text-text-secondary text-[11px] uppercase tracking-wider font-bold mb-3">Filter by category</p>
                    <div class="flex flex-wrap gap-1.5">
                        <button @click="setCategory(null)" type="button"
                                :class="activeCategory === null ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-400'"
                                class="text-xs font-bold px-3 py-1.5 rounded-full border transition-colors">All</button>
                        @foreach ($categories as $cat)
                            <button @click="setCategory('{{ $cat['slug'] }}')" type="button"
                                    :class="activeCategory === '{{ $cat['slug'] }}' ? 'text-white border-transparent' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-400'"
                                    :style="activeCategory === '{{ $cat['slug'] }}' ? 'background-color: {{ $cat['color'] }}' : ''"
                                    class="text-xs font-bold px-3 py-1.5 rounded-full border transition-colors inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full" style="background-color: {{ $cat['color'] }}"></span>
                                {{ $cat['name'] }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- List of listings --}}
                <div class="flex-1 overflow-y-auto divide-y divide-border-light">
                    <template x-for="m in filteredMarkers" :key="m.id">
                        <a :href="m.url"
                           @mouseenter="hoverMarker(m.id)"
                           @mouseleave="unhoverMarker(m.id)"
                           class="block px-4 py-3 hover:bg-slate-50 transition-colors group">
                            <div class="flex gap-3">
                                <div class="w-16 h-16 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0 relative" x-data="{ imgError: false }">
                                    <template x-if="m.image && !imgError">
                                        <img :src="m.image" :alt="m.title" loading="lazy" x-on:error="imgError = true" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!m.image || imgError">
                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                            <span class="material-symbols-outlined text-[28px]">image</span>
                                        </div>
                                    </template>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start gap-2 mb-0.5">
                                        <span class="w-2 h-2 rounded-full flex-shrink-0 mt-1.5" :style="`background-color: ${colorByCategory[m.category_slug] || '#64748b'}`"></span>
                                        <p class="text-[10px] uppercase tracking-wider font-bold text-text-secondary" x-text="m.category"></p>
                                    </div>
                                    <h3 class="text-slate-900 font-bold text-sm leading-tight group-hover:text-primary transition-colors line-clamp-2" x-text="m.title"></h3>
                                    <p class="text-text-secondary text-xs mt-0.5 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[12px]">location_on</span>
                                        <span x-text="m.area"></span>
                                    </p>
                                    <template x-if="m.price">
                                        <p class="text-[#e8831a] text-xs font-bold mt-1" x-text="m.price"></p>
                                    </template>
                                </div>
                                <template x-if="m.is_featured">
                                    <span class="material-symbols-outlined text-amber-500 text-[16px] flex-shrink-0" title="Featured" style="font-variation-settings:'FILL' 1">star</span>
                                </template>
                            </div>
                        </a>
                    </template>
                    <template x-if="filteredMarkers.length === 0">
                        <div class="px-4 py-12 text-center text-text-secondary text-sm">
                            <span class="material-symbols-outlined text-3xl text-slate-300 block mb-2">search_off</span>
                            No listings match this filter
                        </div>
                    </template>
                </div>
            </aside>

            {{-- Map --}}
            <div class="ha-map-pane bg-white border border-border-light rounded-2xl overflow-hidden shadow-sm relative">
                <div x-ref="mapEl" id="ha-map" style="width: 100%; height: 100%; position: absolute; inset: 0;"></div>

                {{-- Marker count badge --}}
                <div class="absolute top-4 left-4 bg-white/95 backdrop-blur border border-border-light rounded-full px-3 py-1.5 shadow-md pointer-events-none">
                    <span class="text-slate-900 text-xs font-bold" x-text="`${filteredMarkers.length} listing${filteredMarkers.length === 1 ? '' : 's'}`"></span>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Official Google Maps JS API inline bootstrap loader — see
    // https://developers.google.com/maps/documentation/javascript/load-maps-js-api
    (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({key: "{{ $googleMapsKey }}", v: "weekly"});

    document.addEventListener('alpine:init', () => {
        Alpine.data('haMap', (config) => ({
            markers: config.markers,
            categories: config.categories,
            colorByCategory: config.colorByCategory,
            mapId: config.mapId,
            activeCategory: null,
            map: null,
            infoWindow: null,
            mapMarkers: {},
            useAdvancedMarkers: false,
            AdvancedMarkerElement: null,

            get filteredMarkers() {
                if (!this.activeCategory) return this.markers;
                return this.markers.filter(m => m.category_slug === this.activeCategory);
            },

            async initMap(el) {
                const { Map } = await google.maps.importLibrary('maps');

                // Advanced (custom-HTML) markers need a Map ID configured in
                // Cloud Console. Without one, fall back to plain colored pins
                // so the page still works with just an API key.
                if (this.mapId) {
                    const markerLib = await google.maps.importLibrary('marker');
                    this.AdvancedMarkerElement = markerLib.AdvancedMarkerElement;
                    this.useAdvancedMarkers = true;
                }

                this.map = new Map(el, {
                    center: { lat: 18.6414, lng: 72.8722 },
                    zoom: 11,
                    mapId: this.mapId || undefined,
                    gestureHandling: 'greedy',
                    streetViewControl: false,
                });

                this.infoWindow = new google.maps.InfoWindow();
                this.renderMarkers();

                requestAnimationFrame(() => this.fitMarkers());
                window.addEventListener('resize', () => this.fitMarkers());
            },

            renderMarkers() {
                // Clear existing
                Object.values(this.mapMarkers).forEach(m => {
                    if (this.useAdvancedMarkers) { m.map = null; } else { m.setMap(null); }
                });
                this.mapMarkers = {};

                const visible = this.filteredMarkers;
                if (visible.length === 0) return;

                visible.forEach(m => {
                    const color = this.colorByCategory[m.category_slug] || '#64748b';
                    const position = { lat: m.lat, lng: m.lng };
                    let marker;

                    if (this.useAdvancedMarkers) {
                        const wrap = document.createElement('div');
                        wrap.innerHTML = this.markerHtml(m, color);
                        marker = new this.AdvancedMarkerElement({
                            map: this.map,
                            position,
                            content: wrap.firstElementChild,
                        });
                        marker.addListener('gmp-click', () => this.openPopup(marker, m, color));
                    } else {
                        marker = new google.maps.Marker({
                            map: this.map,
                            position,
                            title: m.title,
                            icon: {
                                path: google.maps.SymbolPath.CIRCLE,
                                fillColor: color,
                                fillOpacity: 1,
                                strokeColor: '#ffffff',
                                strokeWeight: 2,
                                scale: 9,
                            },
                        });
                        marker.addListener('click', () => this.openPopup(marker, m, color));
                    }

                    this.mapMarkers[m.id] = marker;
                });

                this.fitMarkers();
            },

            openPopup(marker, m, color) {
                this.infoWindow.setContent(this.popupHtml(m, color));
                this.infoWindow.open({ map: this.map, anchor: marker });
            },

            fitMarkers() {
                if (!this.map) return;
                const visible = this.filteredMarkers;
                if (visible.length === 0) return;
                if (visible.length === 1) {
                    this.map.setCenter({ lat: visible[0].lat, lng: visible[0].lng });
                    this.map.setZoom(14);
                    return;
                }
                const bounds = new google.maps.LatLngBounds();
                visible.forEach(m => bounds.extend({ lat: m.lat, lng: m.lng }));
                this.map.fitBounds(bounds, 50);
            },

            markerHtml(m, color) {
                const label = m.price ? m.price : m.title.length > 18 ? m.title.slice(0, 16) + '…' : m.title;
                return `
                    <span class="ha-marker">
                        <span class="ha-marker__dot" style="background-color: ${color}"></span>
                        ${this.escape(label)}
                    </span>`;
            },

            popupHtml(m, color) {
                const img = m.image
                    ? `<div style="height:140px;background:#f1f5f9 url('${m.image}') center/cover no-repeat"></div>`
                    : `<div style="height:140px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#cbd5e1"><span class="material-symbols-outlined" style="font-size:32px">image</span></div>`;
                const price = m.price ? `<p style="margin:8px 0 0;font-weight:700;color:#0d161b;font-size:13px">${this.escape(m.price)}</p>` : '';
                const featured = m.is_featured ? `<span style="background:#fef3c7;color:#92400e;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;padding:2px 8px;border-radius:999px;margin-left:6px">Featured</span>` : '';

                return `
                    ${img}
                    <div style="padding:14px 16px 16px;background:white">
                        <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px">
                            <span style="width:8px;height:8px;border-radius:999px;background:${color};display:inline-block"></span>
                            <span style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#4c799a">${this.escape(m.category || '')}</span>
                            ${featured}
                        </div>
                        <h3 style="margin:0;font-size:15px;font-weight:700;color:#0d161b;line-height:1.3">${this.escape(m.title)}</h3>
                        <p style="margin:4px 0 0;font-size:12px;color:#64748b;display:flex;align-items:center;gap:3px">
                            <span class="material-symbols-outlined" style="font-size:13px">location_on</span>
                            ${this.escape(m.area || '')}
                        </p>
                        ${price}
                        <a href="${m.url}" style="display:inline-flex;align-items:center;gap:4px;margin-top:10px;color:#1183d4;font-weight:700;font-size:12px;text-decoration:none">
                            View listing
                            <span class="material-symbols-outlined" style="font-size:14px">arrow_forward</span>
                        </a>
                    </div>`;
            },

            setCategory(slug) {
                this.activeCategory = slug;
                this.$nextTick(() => this.renderMarkers());
            },

            hoverMarker(id) {
                // Only the AdvancedMarkerElement path has a content DOM node
                // to highlight — plain colored pins skip this.
                const m = this.mapMarkers[id];
                if (m && m.content) m.content.classList.add('is-active');
            },

            unhoverMarker(id) {
                const m = this.mapMarkers[id];
                if (m && m.content) m.content.classList.remove('is-active');
            },

            escape(s) {
                return String(s ?? '').replace(/[&<>"']/g, ch => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[ch]));
            },
        }));
    });
</script>
@endpush
