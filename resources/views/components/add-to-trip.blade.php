@php
    $endpoint = route('trips.attach', $listing);
    $loginUrl = route('login') . '?redirect=' . urlencode(url()->current());
@endphp

<div class="relative inline-block"
     x-data="{
         open: false,
         creatingNew: false,
         newName: '',
         flash: null,
         busy: false,
         trips: @js($trips),
         async attach(tripId) {
             if (this.busy) return;
             this.busy = true;
             try {
                 const fd = new FormData();
                 fd.append('trip_id', tripId);
                 const res = await fetch(@js($endpoint), {
                     method: 'POST',
                     headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                     body: fd,
                 });
                 const data = await res.json();
                 this.flash = { ok: data.ok, message: data.message };
                 if (data.ok) {
                     const t = this.trips.find(t => t.id === tripId);
                     if (t) t.has_listing = true;
                 }
             } catch (e) {
                 this.flash = { ok: false, message: 'Something went wrong. Please try again.' };
             } finally {
                 this.busy = false;
             }
         },
         async createAndAttach() {
             if (this.busy || !this.newName.trim()) return;
             this.busy = true;
             try {
                 const fd = new FormData();
                 fd.append('new_trip_name', this.newName.trim());
                 const res = await fetch(@js($endpoint), {
                     method: 'POST',
                     headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                     body: fd,
                 });
                 const data = await res.json();
                 this.flash = { ok: data.ok, message: data.message };
                 if (data.ok && data.trip) {
                     this.trips.unshift({ id: data.trip.id, name: data.trip.name, has_listing: true });
                     this.newName = '';
                     this.creatingNew = false;
                 }
             } catch (e) {
                 this.flash = { ok: false, message: 'Something went wrong. Please try again.' };
             } finally {
                 this.busy = false;
             }
         },
     }"
     @click.outside="open = false; creatingNew = false; flash = null"
     @keydown.escape.window="open = false; creatingNew = false">

    {{-- Trigger button --}}
    @if ($variant === 'icon')
        <button type="button" @click="open = !open"
                class="w-9 h-9 rounded-full bg-white/95 hover:bg-white text-slate-700 hover:text-primary shadow-md flex items-center justify-center transition-all"
                title="Add to trip">
            <span class="material-symbols-outlined text-[18px]">add_road</span>
        </button>
    @else
        <button type="button" @click="open = !open"
                class="inline-flex items-center gap-1.5 bg-white border border-border-light hover:border-primary/40 text-slate-700 text-sm font-bold px-3 py-2 rounded-xl transition-colors shadow-sm">
            <span class="material-symbols-outlined text-[16px]">add_road</span>
            <span class="hidden sm:inline">Add to trip</span>
            <span class="material-symbols-outlined text-[16px] transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
        </button>
    @endif

    {{-- Dropdown panel --}}
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         class="absolute left-0 mt-2 w-72 max-w-[calc(100vw-2rem)] bg-white rounded-xl shadow-xl border border-slate-100 z-50 origin-top-left overflow-hidden"
         style="display: none;">

        @guest
            <div class="p-5 text-center">
                <span class="material-symbols-outlined text-[28px] text-primary mb-2 block">luggage</span>
                <p class="text-slate-900 font-bold text-sm mb-1">Sign in to plan trips</p>
                <p class="text-text-secondary text-xs mb-4 leading-relaxed">Save this place to a trip and share it with friends.</p>
                <a href="{{ $loginUrl }}"
                   class="inline-flex items-center gap-1.5 bg-primary text-white font-bold text-sm px-4 py-2 rounded-xl hover:bg-primary-dark transition-colors w-full justify-center">
                    Sign in <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>
        @endguest

        @auth
            <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/60">
                <p class="text-slate-900 font-bold text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[18px]">luggage</span>
                    Add to a trip
                </p>
            </div>

            {{-- Flash --}}
            <div x-show="flash" x-cloak class="px-4 py-2 text-xs font-semibold"
                 :class="flash?.ok ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700'">
                <span x-text="flash?.message"></span>
            </div>

            {{-- List of existing trips --}}
            <div class="max-h-56 overflow-y-auto divide-y divide-slate-50">
                <template x-for="t in trips" :key="t.id">
                    <button type="button"
                            @click="attach(t.id)"
                            :disabled="busy || t.has_listing"
                            :class="t.has_listing ? 'bg-emerald-50' : 'hover:bg-slate-50'"
                            class="w-full text-left px-4 py-3 flex items-center gap-3 transition-colors group">
                        <span class="material-symbols-outlined text-[18px] flex-shrink-0"
                              :class="t.has_listing ? 'text-emerald-600' : 'text-slate-500 group-hover:text-primary'"
                              x-text="t.has_listing ? 'check_circle' : 'add_circle'"></span>
                        <span class="flex-1 text-sm font-semibold text-slate-900 truncate" x-text="t.name"></span>
                        <span x-show="t.has_listing" class="text-emerald-700 text-[10px] uppercase tracking-wider font-bold flex-shrink-0">Added</span>
                    </button>
                </template>
                <template x-if="trips.length === 0">
                    <p class="px-4 py-4 text-xs text-text-secondary text-center">No trips yet — create your first below.</p>
                </template>
            </div>

            {{-- New trip --}}
            <div class="border-t border-slate-100">
                <button type="button" x-show="!creatingNew" @click="creatingNew = true"
                        class="w-full text-left px-4 py-3 hover:bg-slate-50 transition-colors flex items-center gap-3 text-primary font-bold text-sm">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Create a new trip
                </button>
                <div x-show="creatingNew" x-cloak class="p-3 bg-slate-50">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-text-secondary mb-1.5">New trip name</label>
                    <input type="text" x-model="newName" maxlength="120" autofocus
                           @keydown.enter.prevent="createAndAttach()"
                           placeholder="e.g. Weekend in Mandwa"
                           class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none mb-2">
                    <div class="flex items-center justify-end gap-2">
                        <button type="button" @click="creatingNew = false; newName = ''"
                                class="text-xs font-bold text-text-secondary hover:text-slate-900 px-2 py-1 transition-colors">Cancel</button>
                        <button type="button" @click="createAndAttach()"
                                :disabled="busy || !newName.trim()"
                                :class="(busy || !newName.trim()) ? 'bg-slate-200 text-slate-500 cursor-not-allowed' : 'bg-primary text-white hover:bg-primary-dark'"
                                class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1.5 rounded-lg transition-colors">
                            <span x-show="!busy">Create &amp; add</span>
                            <span x-show="busy">Saving…</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-100 px-4 py-2 text-center">
                <a href="{{ route('trips.index') }}" class="text-text-secondary hover:text-primary text-xs font-bold inline-flex items-center gap-1 transition-colors">
                    View all my trips <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </div>
        @endauth
    </div>
</div>
