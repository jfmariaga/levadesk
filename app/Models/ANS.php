<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ANS extends Model
{
    protected $table = 'a_n_s';

    protected $fillable = ['nivel','solicitud_id', 'h_atencion','t_asignacion_segundos','t_resolucion_segundos','t_aceptacion_segundos'];

    public function solicitud(){
        return $this->belongsTo(TipoSolicitud::class);
    }
    use HasFactory;
}
