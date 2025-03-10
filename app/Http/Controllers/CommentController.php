<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // app/Http/Controllers/CommentController.php
    public function store(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'content' => 'required',
            'attachments.*' => 'file|max:5120|mimes:jpg,png,pdf,docx'
        ]);

        $comment = $ticket->comments()->create([
            'content' => $validated['content'],
            'user_id' => auth()->id()
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $this->storeAttachment($file, $comment);
            }
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
    }

    public function markAsSolution(Comment $comment)
    {
        $this->authorize('update', $comment->ticket);

        $comment->ticket->update([
            'solution_comment_id' => $comment->id,
            'status' => 'closed'
        ]);

        return back()->with('success', 'Marcado como solución');
    }
}
