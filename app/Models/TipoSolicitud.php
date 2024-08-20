<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoSolicitud extends Model
{
    use HasFactory;

    protected $table = 'tipo_solicitudes';

    protected $fillable = ['nombre', 'codigo','estado'];

    public function categorias()
    {
        return $this->hasMany(Categoria::class);
    }

    public function ans(){
        return $this->hasMany(ANS::class);
    }
}
