<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'ruta', 'tipo','comentario_id','cambio_id'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function comentario()
    {
        return $this->belongsTo(Comentario::class);
    }

    public function cambio(){
        return $this->belongsTo(Cambios::class);
    }
}
