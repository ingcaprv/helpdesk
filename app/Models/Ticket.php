<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    // app/Http/Controllers/TicketController.php
    public function index()
    {
        // Asegúrate de usar la relación 'tickets' (no 'ticket' ni otro nombre)
        $tickets = auth()->user()->tickets()->with('comments')->latest()->get();

        return view('tickets.index', compact('tickets'));
    }
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => strtoupper($value),
        );
    }

    // app/Models/Ticket.php
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    protected static function booted()
    {
        static::deleting(function ($ticket) {
            foreach ($ticket->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->path);
                $attachment->delete();
            }
        });
    }

    // Scopes para filtros
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

// Método para cerrar tickets
    public function close()
    {
        $this->update(['status' => 'closed']);
        $this->user->notify(new TicketClosed($this));
    }

// Método para calcular tiempo transcurrido
    public function getTimeElapsedAttribute()
    {
        return $this->created_at->diffForHumans();
    }

// Método para prioridad (ejemplo con lógica)
    public function getPriorityAttribute()
    {
        if ($this->created_at->diffInHours() < 24 && $this->comments()->count() > 5) {
            return 'high';
        }
        return 'normal';
    }
}
