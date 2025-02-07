<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;

class TicketService
{
    public function create(array $data): Ticket
    {
        try {
            // Set default status to 'open' if not provided
            $data['status'] = $data['status'] ?? 'open';

            return Ticket::create($data);
        } catch (Exception $e) {
            throw new Exception('Error creating ticket: ' . $e->getMessage());
        }
    }

    public function getAll(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        try {
            $query = Ticket::query();

            if (isset($filters['status']) && in_array($filters['status'], ['open', 'in_progress', 'closed'])) {
                $query->where('status', $filters['status']);
            }

            if (isset($filters['search']) && !empty($filters['search'])) {
                $query->where('title', 'like', '%' . $filters['search'] . '%');
            }

            return $query->paginate($perPage);
        } catch (Exception $e) {
            throw new Exception('Error retrieving tickets: ' . $e->getMessage());
        }
    }

    public function getById(string $id): ?Ticket
    {
        try {
            return Ticket::find($id);
        } catch (Exception $e) {
            throw new Exception('Error retrieving ticket: ' . $e->getMessage());
        }
    }

    public function update(string $id, array $data): ?Ticket
    {
        try {
            $ticket = Ticket::find($id);
            if ($ticket) {
                $ticket->update($data);
                return $ticket;
            }
            return null;
        } catch (Exception $e) {
            throw new Exception('Error updating ticket: ' . $e->getMessage());
        }
    }

    public function delete(string $id): bool
    {
        try {
            $ticket = Ticket::find($id);
            if ($ticket) {
                return $ticket->delete();
            }
            return false;
        } catch (Exception $e) {
            throw new Exception('Error deleting ticket: ' . $e->getMessage());
        }
    }
}
