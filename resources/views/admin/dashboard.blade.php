@extends('layouts.admin')
@section('page-title', 'Overview')

@section('page-actions')
    <a href="{{ route('admin.listings.index', ['status' => 'pending']) }}" class="inline-flex items-center gap-1.5 bg-white border border-border-light text-text-main text-sm font-semibold px-4 py-2 rounded-lg hover:bg-slate-50 transition-colors">
        <span class="material-symbols-outlined text-[18px]">approval</span> Review queue
    </a>
    <a href="{{ route('admin.classifieds.create') }}" class="inline-flex items-center gap-1.5 bg-primary text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-primary-dark transition-colors shadow-sm">
        <span class="material-symbols-outlined text-[18px]">add_circle</span> Add item
    </a>
@endsection

@section('content')
<p class="text-sm text-text-secondary -mt-2 mb-6">{{ now()->format('l, d M Y') }} · Here's what's happening on Hello Alibaug.</p>

{{-- Stat cards --}}
@php
    $stats = [
        ['label' => 'Business Listings', 'value' => $totalListings,                'icon' => 'list_alt',        'tile' => 'bg-primary/10 text-primary',        'href' => route('admin.listings.index', ['status' => 'all'])],
        ['label' => 'Pending Approvals', 'value' => $pendingListings,              'icon' => 'pending_actions', 'tile' => 'bg-amber-50 text-amber-600',        'href' => route('admin.listings.index', ['status' => 'pending']), 'alert' => $pendingListings > 0],
        ['label' => 'Business Owners',   'value' => $totalOwners,                  'icon' => 'group',           'tile' => 'bg-indigo-50 text-indigo-600',      'href' => route('admin.users.index')],
        ['label' => 'Total Views',       'value' => number_format($totalViews),    'icon' => 'visibility',      'tile' => 'bg-rose-50 text-rose-600'],
        ['label' => 'Total Inquiries',   'value' => $totalInquiries,               'icon' => 'mail',            'tile' => 'bg-purple-50 text-purple-600',      'href' => route('admin.inquiries.index')],
        ['label' => 'Open Tickets',      'value' => $openTickets,                  'icon' => 'support_agent',   'tile' => 'bg-orange-50 text-orange-600',      'href' => route('admin.support.index'), 'alert' => $openTickets > 0],
        ['label' => 'Pending Reviews',   'value' => $pendingReviews,               'icon' => 'reviews',         'tile' => 'bg-amber-50 text-amber-600',        'href' => route('admin.reviews.index'), 'alert' => $pendingReviews > 0],
        ['label' => 'Premium Listings',  'value' => $premiumListings,              'icon' => 'workspace_premium','tile' => 'bg-emerald-50 text-emerald-600'],
    ];
@endphp
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach($stats as $stat)
        {{-- {!! !!} so the tag's real quotes aren't HTML-escaped into &quot; (which
             would wrap the href value in literal quotes and 404). The URL is still
             escaped via e(). --}}
        <{!! ($stat['href'] ?? null) ? 'a href="'.e($stat['href']).'"' : 'div' !!}
           class="group bg-white rounded-2xl border border-border-light p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all relative">
            @if($stat['alert'] ?? false)
                <span class="absolute top-4 right-4 w-2 h-2 rounded-full bg-red-500 ring-4 ring-red-50"></span>
            @endif
            <div class="w-11 h-11 rounded-xl {{ $stat['tile'] }} flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-[22px]">{{ $stat['icon'] }}</span>
            </div>
            <p class="text-2xl font-bold text-slate-900 leading-none">{{ $stat['value'] }}</p>
            <p class="text-xs text-text-secondary font-medium mt-1.5">{{ $stat['label'] }}</p>
        </{{ ($stat['href'] ?? null) ? 'a' : 'div' }}>
    @endforeach
</div>

{{-- Analytics Charts --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    {{-- Growth Chart --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm p-6">
        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2 mb-4">
            <span class="material-symbols-outlined text-primary">trending_up</span>
            Platform Growth (6 Months)
        </h2>
        <div id="growthChart" class="w-full h-72"></div>
    </div>

    {{-- Inquiry Chart --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm p-6">
        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2 mb-4">
            <span class="material-symbols-outlined text-purple-500">campaign</span>
            Inquiries Generated (30 Days)
        </h2>
        <div id="inquiryChart" class="w-full h-72"></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    {{-- Activity Feed --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400">notifications</span>
                Recent Activity
            </h2>
        </div>
        <div class="divide-y divide-slate-50">
            @forelse($activityFeed as $item)
                <div class="px-6 py-4 flex gap-4 hover:bg-slate-50/50 transition-colors">
                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined {{ $item['color'] }} text-[20px]">{{ $item['icon'] }}</span>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-0.5">
                            <p class="text-sm font-bold text-slate-900">{{ $item['title'] }}</p>
                            <span class="text-[10px] text-slate-500 font-medium">{{ $item['time']->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-slate-500 line-clamp-1">{{ $item['description'] }}</p>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <p class="text-sm text-slate-500 italic">No recent activity found</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Recent Submissions --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400">fact_check</span>
                Recent Submissions
            </h2>
            <a href="{{ route('admin.listings.index') }}" class="text-xs text-primary font-bold hover:underline">View All</a>
        </div>
        <div class="divide-y divide-slate-50">
            @foreach($recentListings as $listing)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-50 border border-slate-100 flex-shrink-0">
                            @if($listing->getPrimaryImageUrl())
                                <img src="{{ $listing->getPrimaryImageUrl() }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300"><span class="material-symbols-outlined">image</span></div>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900 line-clamp-1">{{ $listing->title }}</p>
                            <p class="text-[11px] text-slate-500 font-medium">{{ $listing->category->name }} · {{ $listing->creator->name }}</p>
                        </div>
                    </div>
                    @php
                        $statusColors = [
                            'approved' => 'bg-green-100 text-green-700',
                            'pending' => 'bg-amber-100 text-amber-700',
                            'rejected' => 'bg-red-100 text-red-700',
                        ];
                    @endphp
                    <span class="text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wider {{ $statusColors[$listing->status] ?? 'bg-slate-100 text-slate-600' }}">{{ $listing->status }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const primaryColor = '#1183d4'; // Hello Alibaug primary blue
    const secondaryColor = '#0f172a'; // Slate 900
    const tertiaryColor = '#64748b'; // Slate 500

    // Growth Chart (Area)
    const growthData = @json($growthChartData);
    const growthOptions = {
        series: [
            { name: 'New Listings', data: growthData.listings.reverse() },
            { name: 'New Users', data: growthData.users.reverse() }
        ],
        chart: { type: 'area', height: 300, fontFamily: 'Manrope, sans-serif', toolbar: { show: false } },
        colors: [primaryColor, secondaryColor],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] } },
        xaxis: { categories: growthData.labels.reverse(), axisBorder: { show: false }, axisTicks: { show: false }, labels: { style: { colors: tertiaryColor } } },
        yaxis: { labels: { style: { colors: tertiaryColor } } },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4, yaxis: { lines: { show: true } } },
        legend: { position: 'top', horizontalAlign: 'right' }
    };
    new ApexCharts(document.querySelector("#growthChart"), growthOptions).render();

    // Inquiry Chart (Bar)
    const inquiryData = @json($inquiryChartData);
    const inquiryOptions = {
        series: [{ name: 'Inquiries', data: inquiryData.series.reverse() }],
        chart: { type: 'bar', height: 300, fontFamily: 'Manrope, sans-serif', toolbar: { show: false } },
        colors: ['#a855f7'], // Purple
        plotOptions: { bar: { borderRadius: 4, columnWidth: '60%' } },
        dataLabels: { enabled: false },
        xaxis: { categories: inquiryData.labels.reverse(), axisBorder: { show: false }, axisTicks: { show: false }, labels: { style: { colors: tertiaryColor } }, tickAmount: 10 },
        yaxis: { labels: { style: { colors: tertiaryColor } } },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4, yaxis: { lines: { show: true } } }
    };
    new ApexCharts(document.querySelector("#inquiryChart"), inquiryOptions).render();
});
</script>
@endsection
