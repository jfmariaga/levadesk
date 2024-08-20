<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recordatorio extends Model
{
    use HasFactory;
    protected $table = 'recordatorios';

    protected $fillable = ['reminder_at', 'usuario_id','ticket_id','detalle'];

    public function ticket(){
        return $this->belongsTo(Ticket::class);
    }

    public function usuario(){
        return $this->belongsTo(User::class);
    }
}
