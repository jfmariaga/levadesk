<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketEstado extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'estado_id'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
