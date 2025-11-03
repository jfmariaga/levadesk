<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SociedadSubcategoriaGrupo extends Model
{
    use HasFactory;

    protected $table = 'sociedad_subcategoria_grupo';

    protected $fillable = [
        'sociedad_id',
        'subcategoria_id',
        'grupo_id',
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function sociedad()
    {
        return $this->belongsTo(Sociedad::class);
    }

    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class, 'subcategoria_id');
    }
}
