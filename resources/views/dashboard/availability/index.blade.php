@extends('layouts.dashboard')
@section('page-title', 'Availability — ' . $listing->title)

@section('content')
<div class="max-w-4xl" x-data="availabilityCalendar()">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-text-main">Availability Calendar</h2>
            <p class="text-sm text-text-secondary mt-1">{{ $listing->title }}</p>
        </div>
        <a href="{{ route('owner.listings.index') }}" class="text-sm text-text-secondary hover:text-primary">← Back to Listings</a>
    </div>

    {{-- Calendar Controls --}}
    <div class="bg-white rounded-2xl border border-border-light p-6 shadow-sm mb-6">
        <div class="flex items-center justify-between mb-6">
            <button @click="prevMonth()" class="p-2 hover:bg-slate-100 rounded-xl transition-colors">
                <span class="material-symbols-outlined">chevron_left</span>
            </button>
            <h3 class="text-lg font-bold text-text-main" x-text="monthLabel"></h3>
            <button @click="nextMonth()" class="p-2 hover:bg-slate-100 rounded-xl transition-colors">
                <span class="material-symbols-outlined">chevron_right</span>
            </button>
        </div>

        {{-- Legend --}}
        <div class="flex items-center gap-4 mb-4 text-xs">
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-green-400"></span> Available</span>
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-red-400"></span> Booked</span>
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-slate-200"></span> Not Set</span>
        </div>

        {{-- Day Headers --}}
        <div class="grid grid-cols-7 gap-1 mb-2">
            <template x-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']">
                <div class="text-center text-xs font-bold text-text-secondary py-2" x-text="day"></div>
            </template>
        </div>

        {{-- Calendar Grid --}}
        <div class="grid grid-cols-7 gap-1">
            <template x-for="blank in blanks">
                <div class="h-14"></div>
            </template>
            <template x-for="day in days" :key="day.date">
                <button type="button" @click="toggleDay(day)" class="h-14 rounded-xl border text-sm font-medium transition-all flex flex-col items-center justify-center"
                    :class="{
                        'bg-green-50 border-green-200 text-green-700 hover:bg-green-100': day.status === 'available',
                        'bg-red-50 border-red-200 text-red-700 hover:bg-red-100': day.status === 'booked',
                        'bg-white border-slate-200 text-slate-500 hover:bg-slate-50': !day.status
                    }">
                    <span x-text="day.num"></span>
                    <span class="text-[9px]" x-show="day.price" x-text="'₹' + day.price"></span>
                </button>
            </template>
        </div>
    </div>

    {{-- Save --}}
    <form method="POST" action="{{ route('owner.availability.update', $listing) }}" x-ref="form">
        @csrf @method('PUT')
        <template x-for="(day, idx) in changedDays" :key="day.date">
            <div>
                <input type="hidden" :name="'dates[' + idx + '][date]'" :value="day.date">
                <input type="hidden" :name="'dates[' + idx + '][status]'" :value="day.status">
                <input type="hidden" :name="'dates[' + idx + '][price_override]'" :value="day.price || ''">
            </div>
        </template>
        <button type="submit" x-show="changedDays.length > 0" class="bg-primary text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-primary-dark transition-colors">
            Save <span x-text="changedDays.length"></span> Change(s)
        </button>
    </form>
</div>

<script>
function availabilityCalendar() {
    const existing = @json($availabilities->mapWithKeys(fn($a) => [$a->date->format('Y-m-d') => ['status' => $a->status, 'price' => $a->price_override]]));
    return {
        currentDate: new Date(),
        existing: existing,
        changes: {},
        get monthLabel() { return this.currentDate.toLocaleString('default', { month: 'long', year: 'numeric' }); },
        get blanks() { return new Array(new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), 1).getDay()); },
        get days() {
            const year = this.currentDate.getFullYear();
            const month = this.currentDate.getMonth();
            const count = new Date(year, month + 1, 0).getDate();
            return Array.from({ length: count }, (_, i) => {
                const d = `${year}-${String(month + 1).padStart(2, '0')}-${String(i + 1).padStart(2, '0')}`;
                const change = this.changes[d];
                const ex = this.existing[d];
                return { num: i + 1, date: d, status: change?.status || ex?.status || null, price: change?.price || ex?.price || null };
            });
        },
        get changedDays() { return Object.values(this.changes); },
        toggleDay(day) {
            const states = [null, 'available', 'booked'];
            const idx = states.indexOf(day.status);
            const next = states[(idx + 1) % states.length];
            if (next) {
                this.changes[day.date] = { date: day.date, status: next, price: day.price };
            } else {
                delete this.changes[day.date];
            }
        },
        prevMonth() { this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() - 1, 1); },
        nextMonth() { this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 1); },
    };
}
</script>
@endsection
