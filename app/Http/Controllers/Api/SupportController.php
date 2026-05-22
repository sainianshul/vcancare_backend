<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Support\StoreTicketRequest;
use App\Http\Requests\Api\Support\ReplyTicketRequest;
use App\Models\SupportTicket;
use App\Services\SupportService;
use App\Exceptions\Support\TicketClosedException;
use App\Exceptions\Support\UnauthorizedTicketAccessException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Support Tickets', description: 'API Endpoints for managing support tickets (For both Patients and Nurses)')]
class SupportController extends Controller
{
    protected $supportService;

    public function __construct(SupportService $supportService)
    {
        $this->supportService = $supportService;
    }

    /**
     * List all support tickets for the authenticated user.
     */
    #[OA\Get(
        path: '/api/support/tickets',
        operationId: 'getSupportTickets',
        summary: 'List user tickets',
        security: [['sanctum' => []]],
        tags: ['Support Tickets']
    )]
    #[OA\Response(response: 200, description: 'Success')]
    public function index(Request $request)
    {
        $tickets = SupportTicket::where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $tickets
        ]);
    }

    /**
     * Create a new support ticket.
     */
    #[OA\Post(
        path: '/api/support/tickets',
        operationId: 'storeSupportTicket',
        summary: 'Create a new support ticket',
        security: [['sanctum' => []]],
        tags: ['Support Tickets']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                required: ['category', 'subject', 'description'],
                properties: [
                    new OA\Property(property: 'category', type: 'string', example: 'technical'),
                    new OA\Property(property: 'subject', type: 'string', example: 'App keeps crashing'),
                    new OA\Property(property: 'description', type: 'string', example: 'My app crashes on startup'),
                    new OA\Property(property: 'priority', type: 'integer', description: '0: Low, 1: Medium, 2: High', example: 1),
                    new OA\Property(property: 'attachments[]', type: 'array', items: new OA\Items(type: 'string', format: 'binary'), description: 'Array of image files')
                ]
            )
        )
    )]
    #[OA\Response(response: 201, description: 'Ticket created successfully')]
    #[OA\Response(response: 422, description: 'Validation errors')]
    #[OA\Response(response: 500, description: 'Server error')]
    public function store(StoreTicketRequest $request)
    {
        try {
            $ticket = $this->supportService->createTicket(Auth::user(), $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Support ticket created successfully.',
                'data' => $ticket
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create support ticket. ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show a specific ticket and its messages.
     */
    #[OA\Get(
        path: '/api/support/tickets/{id}',
        operationId: 'showSupportTicket',
        summary: 'Get ticket details and messages',
        security: [['sanctum' => []]],
        tags: ['Support Tickets']
    )]
    #[OA\Parameter(name: 'id', description: 'Ticket ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Success')]
    #[OA\Response(response: 403, description: 'Unauthorized access')]
    #[OA\Response(response: 404, description: 'Ticket not found')]
    public function show($id)
    {
        $ticket = SupportTicket::with(['messages' => function($q) {
            $q->orderBy('created_at', 'asc');
        }])->findOrFail($id);

        if ($ticket->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $ticket
        ]);
    }

    /**
     * Reply to a ticket.
     */
    #[OA\Post(
        path: '/api/support/tickets/{id}/reply',
        operationId: 'replySupportTicket',
        summary: 'Reply to an existing ticket',
        security: [['sanctum' => []]],
        tags: ['Support Tickets']
    )]
    #[OA\Parameter(name: 'id', description: 'Ticket ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                required: ['message'],
                properties: [
                    new OA\Property(property: 'message', type: 'string', example: 'Thank you for your help.'),
                    new OA\Property(property: 'attachments[]', type: 'array', items: new OA\Items(type: 'string', format: 'binary'), description: 'Array of image files')
                ]
            )
        )
    )]
    #[OA\Response(response: 200, description: 'Reply sent successfully')]
    #[OA\Response(response: 400, description: 'Ticket is closed')]
    #[OA\Response(response: 403, description: 'Unauthorized access')]
    #[OA\Response(response: 404, description: 'Ticket not found')]
    public function reply(ReplyTicketRequest $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        try {
            $message = $this->supportService->addMessage(
                $ticket, 
                Auth::user(), 
                $request->message, 
                $request->file('attachments') ?? []
            );

            return response()->json([
                'success' => true,
                'message' => 'Reply sent successfully.',
                'data' => $message
            ]);
        } catch (UnauthorizedTicketAccessException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 403);
        } catch (TicketClosedException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send reply. ' . $e->getMessage()], 500);
        }
    }
}
