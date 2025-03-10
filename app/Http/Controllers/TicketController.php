<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // app/Http/Controllers/TicketController.php
    public function index()
    {
        return view('tickets.index', [
            'tickets' => auth()->user()->tickets()->with('comments')->latest()->get()
        ]);
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        return view('tickets.show', [
            'ticket' => $ticket->load('comments.user')
        ]);
    }

    public function destroy(Ticket $ticket)
    {
        $this->authorize('delete', $ticket);

        $ticket->delete();
        return redirect()->route('tickets.index');
    }
    // En TicketController
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'category' => 'required',
            'attachments.*' => 'file|max:5120|mimes:jpg,png,pdf,docx' // 5MB máximo
        ]);

        $ticket = auth()->user()->tickets()->create($validated);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $this->storeAttachment($file, $ticket);
            }
        }

        return redirect()->route('tickets.show', $ticket);
    }

    private function storeAttachment($file, $model)
    {
        $originalName = $file->getClientOriginalName();
        $filename = Str::uuid() . '.' . $file->extension();
        $path = $file->storeAs('attachments/' . date('Y/m'), $filename, 'public');

        return $model->attachments()->create([
            'original_name' => $originalName,
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'user_id' => auth()->id()
        ]);
    }

    // Método para cambiar estado
    public function toggleStatus(Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $ticket->update([
            'status' => $ticket->status === 'open' ? 'closed' : 'open'
        ]);

        return back()->with('status', 'Estado actualizado');
    }

// Método para asignar tickets (admin)
    public function assign(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $ticket->update(['assigned_to' => $validated['user_id']]);

        return redirect()->route('tickets.show', $ticket);
    }
}
