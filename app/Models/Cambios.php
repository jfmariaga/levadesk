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
        'aprobador_user_id',
        'aprobador_final_ti_id',
        'check_aprobado',
        'check_aprobado_ti',
        'evidencia',
        'doc_tecnico',
        'tipo_cambio',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function aprobadorFuncionalCambio()
    {
        return $this->belongsTo(User::class, 'aprobador_funcional_id');
    }

    public function aprobadorTiCambio()
    {
        return $this->belongsTo(User::class, 'aprobador_ti_id');
    }

    public function aprobadorUser()
    {
        return $this->belongsTo(User::class, 'aprobador_user_id');
    }

    public function aprobadorFinalTi()
    {
        return $this->belongsTo(User::class, 'aprobador_final_ti_id');
    }

    public  function archivos(){
        return $this->hasMany(Archivo::class,'cambio_id');
    }
}
