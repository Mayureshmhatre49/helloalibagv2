<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'open');
        $query = SupportTicket::with(['user', 'listing', 'assignedAdmin'])
            ->withCount('replies');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $tickets = $query->latest()->paginate(20);

        $counts = [
            'all' => SupportTicket::count(),
            'open' => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
            'closed' => SupportTicket::where('status', 'closed')->count(),
        ];

        return view('admin.support.index', compact('tickets', 'status', 'counts'));
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load(['user', 'listing', 'replies.user', 'assignedAdmin']);

        return view('admin.support.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'message' => 'required|string|min:2',
            'is_internal_note' => 'boolean',
        ]);

        TicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_admin_reply' => true,
            'is_internal_note' => $request->boolean('is_internal_note'),
        ]);

        // Auto-assign ticket to the replying admin if not assigned
        if (!$ticket->assigned_to) {
            $ticket->update(['assigned_to' => auth()->id()]);
        }

        // If ticket is open, move to in progress
        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        return redirect()->route('admin.support.show', $ticket)
            ->with('success', 'Reply sent successfully.');
    }

    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $updateData = ['status' => $request->status];

        if ($request->status === 'resolved') {
            $updateData['resolved_at'] = now();
        } elseif ($request->status === 'closed') {
            $updateData['closed_at'] = now();
        }

        $ticket->update($updateData);

        return redirect()->route('admin.support.show', $ticket)
            ->with('success', 'Ticket status updated to ' . ucfirst($request->status) . '.');
    }
}
