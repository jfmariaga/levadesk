<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'usuario_id',
        'asignado_a',
        'grupo_id',
        'sociedad_id',
        'tipo_solicitud_id',
        'categoria_id',
        'subcategoria_id',
        'estado_id',
        'ans_id',
        'impacto_id',
        'urgencia_id',
        'nomenclatura',
        'prioridad',
        'notificado',
        'ans_vencido',
        'ans_inicial_vencido',
        'escalar',
        'aplicacion_id',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function asignado()
    {
        return $this->belongsTo(User::class, 'asignado_a');
    }


    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function sociedad()
    {
        return $this->belongsTo(Sociedad::class);
    }

    public function tipoSolicitud()
    {
        return $this->belongsTo(TipoSolicitud::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class);
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    public function ans()
    {
        return $this->belongsTo(ANS::class);
    }

    public function urgencia()
    {
        return $this->belongsTo(Urgencia::class);
    }

    public function impacto()
    {
        return $this->belongsTo(Impacto::class);
    }

    public function archivos()
    {
        return $this->hasMany(Archivo::class);
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    public function historial()
    {
        return $this->hasMany(Historial::class);
    }

    public function solucion()
    {
        return $this->comentarios()->where('tipo', 2)->first();
    }

    public function recordatorios()
    {
        return $this->hasMany(Recordatorio::class);
    }

    public function colaboradors()
    {
        return $this->hasMany(Colaborador::class);
    }

    public function colaboradores()
    {
        return $this->belongsToMany(User::class, 'colaboradors', 'ticket_id', 'user_id');
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    public function aprobacion()
    {
        return $this->hasOne(Aprobacion::class);
    }

    public function cambio()
    {
        return $this->hasOne(Cambios::class);
    }

    public function aplicacion()
    {
        return $this->belongsTo(Aplicaciones::class);
    }

    public function excepcion()
    {
        return $this->hasOne(Excepciones::class);
    }
}
