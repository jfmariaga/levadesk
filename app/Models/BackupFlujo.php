<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupFlujo extends Model
{
    use HasFactory;

    protected $table = 'backup_flujos';

    protected $fillable = [
        'agente_id',
        'flujo_id',
        'aplicacion_id',
        'backup_id',
    ];

    /**
     * Relaciones
     */

    // Agente principal (el que se va de vacaciones)
    public function agente()
    {
        return $this->belongsTo(User::class, 'agente_id');
    }

    // Agente backup (el que lo reemplaza)
    public function backup()
    {
        return $this->belongsTo(User::class, 'backup_id');
    }

    // Flujo o sociedad_subcategoria_grupo
    public function flujo()
    {
        return $this->belongsTo(SociedadSubcategoriaGrupo::class, 'flujo_id');
    }

    // Aplicación asociada
    public function aplicacion()
    {
        return $this->belongsTo(Aplicaciones::class, 'aplicacion_id');
    }
}
