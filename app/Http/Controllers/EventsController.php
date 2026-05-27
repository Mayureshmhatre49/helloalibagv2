<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    private const string TZ = 'Asia/Kolkata';

    public function index(Request $request)
    {
        $filter = $request->get('when', 'upcoming');

        $base = Listing::approved()
            ->whereHas('category', fn ($q) => $q->where('slug', 'events'))
            ->with(['category:id,name,slug,icon', 'area:id,name,slug', 'images' => fn ($q) => $q->orderBy('sort_order')->limit(1)]);

        $query = match ($filter) {
            'weekend' => $base->thisWeekend(),
            'today' => $base->eventsBetween(now(self::TZ)->startOfDay(), now(self::TZ)->endOfDay()),
            'week' => $base->eventsBetween(now(self::TZ)->startOfDay(), now(self::TZ)->addWeek()->endOfDay()),
            'month' => $base->eventsBetween(now(self::TZ)->startOfDay(), now(self::TZ)->addMonth()->endOfDay()),
            default => $base->upcomingEvents(),
        };

        $events = $query->paginate(12)->withQueryString();

        // Group by date for the listing layout (only when not paginated past p1).
        $grouped = collect($events->items())->groupBy(fn (Listing $l) => optional($l->event_start_at)->format('Y-m-d'));

        // Quick counters for the filter chips.
        $counts = [
            'today' => Listing::approved()
                ->whereHas('category', fn ($q) => $q->where('slug', 'events'))
                ->eventsBetween(now(self::TZ)->startOfDay(), now(self::TZ)->endOfDay())
                ->count(),
            'weekend' => Listing::approved()
                ->whereHas('category', fn ($q) => $q->where('slug', 'events'))
                ->thisWeekend()
                ->count(),
            'week' => Listing::approved()
                ->whereHas('category', fn ($q) => $q->where('slug', 'events'))
                ->eventsBetween(now(self::TZ)->startOfDay(), now(self::TZ)->addWeek()->endOfDay())
                ->count(),
            'month' => Listing::approved()
                ->whereHas('category', fn ($q) => $q->where('slug', 'events'))
                ->eventsBetween(now(self::TZ)->startOfDay(), now(self::TZ)->addMonth()->endOfDay())
                ->count(),
            'upcoming' => Listing::approved()
                ->whereHas('category', fn ($q) => $q->where('slug', 'events'))
                ->upcomingEvents()
                ->count(),
        ];

        $weekendLabel = $this->weekendLabel();

        return view('events.calendar', compact('events', 'grouped', 'filter', 'counts', 'weekendLabel'));
    }

    /**
     * "This weekend — Sat Nov 22 – Sun Nov 23"
     */
    private function weekendLabel(): string
    {
        $now = now(self::TZ);
        $sat = $now->isWeekend()
            ? $now->copy()->startOfWeek(Carbon::SATURDAY)
            : $now->copy()->next(Carbon::SATURDAY);
        $sun = $sat->copy()->addDay();

        return $sat->isSameMonth($sun)
            ? "Sat {$sat->format('M j')} – Sun {$sun->format('j')}"
            : "Sat {$sat->format('M j')} – Sun {$sun->format('M j')}";
    }
}
