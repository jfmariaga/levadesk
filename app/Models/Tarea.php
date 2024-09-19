<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'descripcion', 'user_id', 'fecha_cumplimiento', 'ticket_id', 'estado'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Cambiar tarea a "en_progreso"
    public function marcarEnProgreso()
    {
        $this->update(['estado' => 'en_progreso']);
    }

    // Cambiar tarea a "completado"
    public function completar()
    {
        $this->update(['estado' => 'completado']);
    }
}
