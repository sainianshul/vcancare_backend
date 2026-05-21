<?php

namespace App\Services;

use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\User;
use App\Exceptions\Support\TicketClosedException;
use App\Exceptions\Support\UnauthorizedTicketAccessException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SupportService
{
    /**
     * Create a new support ticket.
     */
    public function createTicket(User $user, array $data): SupportTicket
    {
        return DB::transaction(function () use ($user, $data) {
            $ticket = SupportTicket::create([
                'user_id' => $user->id,
                'category' => $data['category'],
                'subject' => $data['subject'],
                'description' => $data['description'],
                'priority' => $data['priority'] ?? SupportTicket::PRIORITY_LOW,
                'status' => SupportTicket::STATUS_OPEN,
            ]);

            // Add the initial description as the first message
            $this->addMessage($ticket, $user, $data['description'], $data['attachments'] ?? [], false, true);

            return $ticket;
        });
    }

    /**
     * Add a message to an existing ticket.
     */
    public function addMessage(SupportTicket $ticket, User $user, string $message, array $attachments = [], bool $isAdmin = false, bool $isInitial = false): SupportTicketMessage
    {
        if (!$isAdmin && $ticket->user_id !== $user->id) {
            throw new UnauthorizedTicketAccessException();
        }

        if (!$isInitial && $ticket->isClosed()) {
            throw new TicketClosedException();
        }

        $uploadedAttachments = [];
        foreach ($attachments as $file) {
            if (is_uploaded_file($file)) {
                $path = $file->store('support_attachments', 'public');
                $uploadedAttachments[] = $path;
            }
        }

        $ticketMessage = SupportTicketMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => $message,
            'attachments' => !empty($uploadedAttachments) ? $uploadedAttachments : null,
            'is_admin' => $isAdmin,
        ]);

        // If a user replies, change status to OPEN (if it was something else, like pending user response)
        // If an admin replies, change status to IN_PROGRESS
        if (!$isInitial) {
            $ticket->status = $isAdmin ? SupportTicket::STATUS_IN_PROGRESS : SupportTicket::STATUS_OPEN;
            $ticket->save();
        }

        return $ticketMessage;
    }

    /**
     * Change the status of a ticket.
     */
    public function updateStatus(SupportTicket $ticket, int $status, ?User $admin = null): bool
    {
        if ($admin && !$admin->isAdmin()) {
            throw new UnauthorizedTicketAccessException("Only admins can arbitrarily change ticket status.");
        }

        $ticket->status = $status;
        
        if ($status === SupportTicket::STATUS_RESOLVED || $status === SupportTicket::STATUS_CLOSED) {
            $ticket->resolved_at = now();
        } else {
            $ticket->resolved_at = null;
        }

        return $ticket->save();
    }
}
