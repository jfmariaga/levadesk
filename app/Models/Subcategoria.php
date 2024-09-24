<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    use HasFactory;


    protected $table = 'subcategorias';

    protected $fillable = ['categoria_id', 'nombre', 'codigo', 'descripcion','estado'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function ans()
    {
        return $this->hasMany(ANS::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // public function grupo()
    // {
    //     return $this->belongsTo(Grupo::class);
    // }

    public function gruposPorSociedad($sociedad_id, $categoria_id)
{
    return $this->belongsToMany(Grupo::class, 'sociedad_subcategoria_grupo', 'subcategoria_id', 'grupo_id')
                ->wherePivot('sociedad_id', $sociedad_id)
                ->wherePivot('categoria_id', $categoria_id);
}

}
