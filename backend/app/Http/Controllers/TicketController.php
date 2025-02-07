<?php

namespace App\Http\Controllers;

use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    // Get all tickets with filters
    public function index(Request $request)
    {
        try {
            $filters = $request->only(['status', 'search']);
            $perPage = $request->get('per_page', 10);

            $tickets = $this->ticketService->getAll($filters, $perPage);

            return response()->json($tickets);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error retrieving tickets: ' . $e->getMessage()], 500);
        }
    }

    // Create a new ticket
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $data = $validator->validated();  // Get the validated data
            $ticket = $this->ticketService->create($data);

            return response()->json($ticket, 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error creating ticket: ' . $e->getMessage()], 500);
        }
    }

    // Get a specific ticket by ID
    public function show(string $id)
    {
        try {
            $ticket = $this->ticketService->getById($id);
            if (!$ticket) {
                return response()->json(['message' => 'Ticket not found'], 404);
            }
            return response()->json($ticket);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error retrieving ticket: ' . $e->getMessage()], 500);
        }
    }

    // Update a ticket
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:open,in_progress,closed',
        ]);

        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $data = $validator->validated();  // Get the validated data
            $ticket = $this->ticketService->update($id, $data);
            if (!$ticket) {
                return response()->json(['message' => 'Ticket not found'], 404);
            }

            return response()->json($ticket);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error updating ticket: ' . $e->getMessage()], 500);
        }
    }

    // Delete a ticket
    public function destroy(string $id)
    {
        try {
            $deleted = $this->ticketService->delete($id);
            if (!$deleted) {
                return response()->json(['message' => 'Ticket not found'], 404);
            }

            return response()->json(['message' => 'Ticket deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error deleting ticket: ' . $e->getMessage()], 500);
        }
    }
}
