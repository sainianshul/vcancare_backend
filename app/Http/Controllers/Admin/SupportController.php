<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Services\SupportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    protected $supportService;

    public function __construct(SupportService $supportService)
    {
        $this->supportService = $supportService;
    }

    /**
     * Display a listing of tickets.
     */
    public function index()
    {
        return view('admin.support.index');
    }

    /**
     * DataTables data source.
     */
    public function data(Request $request)
    {
        $tickets = SupportTicket::with('user')->latest();

        if ($request->has('status') && $request->status !== '') {
            $tickets->where('status', $request->status);
        }

        return datatables()->of($tickets)
            ->addColumn('user', function ($ticket) {
                return '<div class="d-flex align-items-center">
                            <div class="d-flex flex-column">
                                <a href="#" class="text-gray-800 text-hover-primary mb-1 fw-bold">' . $ticket->user->name . '</a>
                                <span class="text-muted fs-7">' . $ticket->user->email . '</span>
                            </div>
                        </div>';
            })
            ->editColumn('reference_id', function ($ticket) {
                return '<a href="' . route('admin.support.show', $ticket->id) . '" class="text-primary fw-bold text-hover-primary mb-1 fs-6">' . $ticket->reference_id . '</a>';
            })
            ->editColumn('status', function ($ticket) {
                $statusMap = [
                    SupportTicket::STATUS_OPEN => ['class' => 'badge-light-warning', 'text' => 'Open'],
                    SupportTicket::STATUS_IN_PROGRESS => ['class' => 'badge-light-primary', 'text' => 'In Progress'],
                    SupportTicket::STATUS_RESOLVED => ['class' => 'badge-light-success', 'text' => 'Resolved'],
                    SupportTicket::STATUS_CLOSED => ['class' => 'badge-light-secondary', 'text' => 'Closed'],
                ];
                
                $status = $statusMap[$ticket->status] ?? ['class' => 'badge-light-secondary', 'text' => 'Unknown'];
                
                return '<span class="badge ' . $status['class'] . '">' . $status['text'] . '</span>';
            })
            ->editColumn('priority', function ($ticket) {
                $priorityMap = [
                    SupportTicket::PRIORITY_LOW => ['class' => 'text-success', 'icon' => 'ki-arrow-down'],
                    SupportTicket::PRIORITY_MEDIUM => ['class' => 'text-warning', 'icon' => 'ki-minus'],
                    SupportTicket::PRIORITY_HIGH => ['class' => 'text-danger', 'icon' => 'ki-arrow-up'],
                ];
                
                $prio = $priorityMap[$ticket->priority] ?? $priorityMap[SupportTicket::PRIORITY_LOW];
                
                return '<div class="d-flex align-items-center"><i class="ki-outline ' . $prio['icon'] . ' fs-3 ' . $prio['class'] . ' me-2"></i> <span class="fw-bold ' . $prio['class'] . '">' . $ticket->priority_text . '</span></div>';
            })
            ->editColumn('created_at', function ($ticket) {
                return '<span class="text-gray-600 fs-7">' . $ticket->created_at->format('d M Y') . '<br><span class="fs-8 text-gray-500">' . $ticket->created_at->format('h:i A') . '</span></span>';
            })
            ->rawColumns(['user', 'reference_id', 'status', 'priority', 'created_at'])
            ->make(true);
    }

    /**
     * Show ticket details and chat interface.
     */
    public function show($id)
    {
        $ticket = SupportTicket::with(['user', 'messages.user'])->findOrFail($id);
        
        // Auto assign to me if it is open
        if ($ticket->status === SupportTicket::STATUS_OPEN) {
            $this->supportService->updateStatus($ticket, SupportTicket::STATUS_IN_PROGRESS, Auth::user());
            $ticket->assigned_to = Auth::id();
            $ticket->save();
        }

        return view('admin.support.show', compact('ticket'));
    }

    /**
     * Reply to a ticket as admin.
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $ticket = SupportTicket::findOrFail($id);

        try {
            $this->supportService->addMessage(
                $ticket,
                Auth::user(),
                $request->message,
                $request->file('attachments') ?? [],
                true // isAdmin = true
            );

            return redirect()->back()->with('success', 'Reply sent successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update ticket status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer|in:0,1,2,3'
        ]);

        $ticket = SupportTicket::findOrFail($id);

        try {
            $this->supportService->updateStatus($ticket, (int)$request->status, Auth::user());
            return redirect()->back()->with('success', 'Ticket status updated.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
