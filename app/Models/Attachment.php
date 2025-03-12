<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    // app/Models/Ticket.php y app/Models/Comment.php
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
// app/Models/Attachment.php
    public function attachable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
