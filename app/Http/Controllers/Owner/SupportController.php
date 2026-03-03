<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $query = SupportTicket::where('user_id', auth()->id())
            ->with(['listing', 'replies'])
            ->withCount('replies');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $tickets = $query->latest()->paginate(15);

        $counts = [
            'all' => SupportTicket::where('user_id', auth()->id())->count(),
            'open' => SupportTicket::where('user_id', auth()->id())->where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('user_id', auth()->id())->where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('user_id', auth()->id())->where('status', 'resolved')->count(),
        ];

        return view('dashboard.support.index', compact('tickets', 'status', 'counts'));
    }

    public function create()
    {
        $listings = auth()->user()->listings()->get();
        return view('dashboard.support.create', compact('listings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|in:general,billing,technical,listing,other',
            'priority' => 'required|in:low,normal,high,urgent',
            'message' => 'required|string|min:10',
            'listing_id' => 'nullable|exists:listings,id',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'category' => $request->category,
            'priority' => $request->priority,
            'listing_id' => $request->listing_id,
        ]);

        TicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_admin_reply' => false,
        ]);

        return redirect()->route('owner.support.show', $ticket)
            ->with('success', 'Support ticket created successfully. Our team will respond shortly.');
    }

    public function show(SupportTicket $ticket)
    {
        // Ensure user can only see their own tickets
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $ticket->load(['replies.user', 'listing']);

        return view('dashboard.support.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|min:2',
        ]);

        TicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_admin_reply' => false,
        ]);

        // Re-open ticket if it was resolved
        if ($ticket->status === 'resolved') {
            $ticket->update(['status' => 'open']);
        }

        return redirect()->route('owner.support.show', $ticket)
            ->with('success', 'Reply sent successfully.');
    }
}
