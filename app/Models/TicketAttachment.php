<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketAttachment extends Model
{
 
    use HasFactory;

    protected $table = 'ticket_attachments';
    protected $fillable = ['ticket_id', 'ticket_update_id', 'user_id', 'file_path', 'original_file_name', 'mime_type', 'size', 'description'];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }

    public function ticketUpdate(): BelongsTo
    {
        return $this->belongsTo(TicketUpdate::class, 'ticket_update_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
