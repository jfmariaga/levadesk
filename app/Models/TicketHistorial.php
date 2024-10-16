<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketHistorial extends Model
{
    protected $table = 'ticket_historial';

    protected $fillable = ['ticket_id', 'estado_id', 'fecha_cambio'];
    public $timestamps = false;
}
