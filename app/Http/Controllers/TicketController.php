<?php

namespace App\Http\Controllers;

use App\Enums\TicketStatus;
use App\Enums\TicketCategory;
use App\Models\Ticket;
use App\Http\Requests\StoreTicketRequest;
use App\Notifications\TicketClosed;
use App\Notifications\TicketReopened;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(): View
    {
        return view('tickets.index', [
            'tickets' => auth()->user()->tickets()
                ->with(['comments', 'attachments', 'assignedTo'])
                ->latest()
                ->paginate(10)
        ]);
    }

    public function create(): View
    {
        return view('tickets.create', [
            'categories' => TicketCategory::cases()
        ]);
    }

    public function store(StoreTicketRequest $request): RedirectResponse
    {
        try {
            $ticket = $request->user()->tickets()->create([
                'title' => $request->validated('title'),
                'description' => $request->validated('description'),
                'category' => $request->validated('category'),
                'status' => TicketStatus::Open->value
            ]);

            $this->handleAttachments($request, $ticket);

            return redirect()->route('tickets.show', $ticket)
                ->with('success', 'Ticket creado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al crear ticket: ' . $e->getMessage());
            return back()->with('error', 'Error al crear el ticket');
        }
    }

    public function show(Ticket $ticket): View
    {
        $this->authorize('view', $ticket);

        return view('tickets.show', [
            'ticket' => $ticket->load([
                'comments.user',
                'attachments',
                'assignedTo',
                'user'
            ])
        ]);
    }

    public function destroy(Ticket $ticket): RedirectResponse
    {
        $this->authorize('delete', $ticket);

        try {
            $ticket->delete();
            return redirect()->route('tickets.index')
                ->with('success', 'Ticket eliminado correctamente');

        } catch (\Exception $e) {
            Log::error('Error eliminando ticket: ' . $e->getMessage());
            return back()->with('error', 'Error al eliminar el ticket');
        }
    }

    public function toggleStatus(Ticket $ticket): RedirectResponse
    {
        $this->authorize('update', $ticket);

        try {
            $newStatus = $ticket->status === TicketStatus::Open
                ? TicketStatus::Closed
                : TicketStatus::Open;

            $ticket->update(['status' => $newStatus]);
            $this->sendStatusNotification($ticket, $newStatus);

            return back()->with('success', "Estado actualizado a: " . TicketStatus::from($newStatus)->label());

        } catch (\Exception $e) {
            Log::error('Error cambiando estado: ' . $e->getMessage());
            return back()->with('error', 'Error al cambiar el estado');
        }
    }

    public function assign(Request $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('assign', $ticket);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        try {
            $ticket->update(['assigned_to' => $validated['user_id']]);
            return redirect()->route('tickets.show', $ticket)
                ->with('success', 'Ticket asignado correctamente');

        } catch (\Exception $e) {
            Log::error('Error asignando ticket: ' . $e->getMessage());
            return back()->with('error', 'Error al asignar el ticket');
        }
    }

    private function handleAttachments($request, $model): void
    {
        try {
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $this->storeAttachment($file, $model);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error manejando adjuntos: ' . $e->getMessage());
            throw $e;
        }
    }

    private function storeAttachment($file, $model): void
    {
        try {
            $path = $file->store(
                'attachments/' . date('Y/m'),
                'public'
            );

            $model->attachments()->create([
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'user_id' => auth()->id()
            ]);

        } catch (\Exception $e) {
            Log::error('Error subiendo archivo: ' . $e->getMessage());
            throw $e;
        }
    }

    private function sendStatusNotification(Ticket $ticket, string $status): void
    {
        try {
            $notification = $status === TicketStatus::Closed
                ? new TicketClosed($ticket)
                : new TicketReopened($ticket);

            $ticket->user->notify($notification);

            if ($ticket->assigned_to) {
                $ticket->assignedTo->notify($notification);
            }

        } catch (\Exception $e) {
            Log::error('Error enviando notificación: ' . $e->getMessage());
        }
    }
}
