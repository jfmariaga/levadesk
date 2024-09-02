<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cambios extends Model
{
    use HasFactory;


    protected $fillable = [
        'ticket_id',
        'aprobador_funcional_id',
        'aprobador_ti_id',
        'estado',
        'comentarios_funcional',
        'comentarios_ti',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function aprobadorFuncional()
    {
        return $this->belongsTo(User::class, 'aprobador_funcional_id');
    }

    public function aprobadorTi()
    {
        return $this->belongsTo(User::class, 'aprobador_ti_id');
    }

    public  function archivos(){
        return $this->hasMany(Archivo::class);
    }
}
