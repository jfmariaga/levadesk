<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    protected $table = 'categorias';

    protected $fillable = ['solicitud_id', 'nombre', 'codigo', 'descripcion','estado'];

    public function solicitud()
    {
        return $this->belongsTo(TipoSolicitud::class);
    }

    public function subcategorias()
    {
        return $this->hasMany(Subcategoria::class);
    }

    public function ans()
    {
        return $this->hasMany(ANS::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
