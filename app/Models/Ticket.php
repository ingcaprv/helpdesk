<?php

namespace App\Models;

use App\Enums\TicketCategory;
use App\Enums\TicketStatus;
use App\Notifications\TicketClosed;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Ticket extends Model
{
    protected $casts = [
        'status' => TicketStatus::class,
        'category' => TicketCategory::class
    ];

    // Método para obtener el label del estado
    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }

    // Método para obtener el label de la categoría
    public function getCategoryLabelAttribute(): string
    {
        return $this->category->label();
    }
    protected $fillable = [
        'title',
        'description',
        'status',
        'category',
        'user_id'
    ];

    protected $appends = ['time_elapsed', 'priority'];

    protected $with = ['user', 'comments'];

    // Relaciones
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    // Scopes
    public function scopeOpen($query): void
    {
        $query->where('status', 'open');
    }

    public function scopeClosed($query): void
    {
        $query->where('status', 'closed');
    }

    public function scopeCategory($query, string $category): void
    {
        $query->where('category', $category);
    }

    // Accessors
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => strtoupper($value),
            set: fn (string $value) => strtolower($value)
        );
    }

    protected function timeElapsed(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at->diffForHumans()
        );
    }

    protected function priority(): Attribute
    {
        return Attribute::make(
            get: function () {
                $hoursOpen = $this->created_at->diffInHours(now());
                $commentCount = $this->comments()->count();

                if ($hoursOpen < 24 && $commentCount > 5) return 'high';
                if ($hoursOpen < 48 && $commentCount > 2) return 'medium';
                return 'low';
            }
        );
    }

    // Métodos
    public function close(): void
    {
        $this->update(['status' => 'closed']);
        $this->user->notify(new TicketClosed($this));
    }

    public function reopen(): void
    {
        $this->update(['status' => 'open']);
    }

    // Event listeners
    protected static function booted(): void
    {
        static::deleting(function ($ticket) {
            $ticket->attachments->each(function ($attachment) {
                Storage::disk('public')->delete($attachment->path);
                $attachment->delete();
            });

            $ticket->comments()->delete();
        });
    }

    // App\Models\Ticket.php
    public function assignedTo()
    {
        // Ejemplo: si un Ticket pertenece a un User (asignado)
        return $this->belongsTo(User::class, 'assigned_to'); // Ajusta según tu estructura
    }
}
