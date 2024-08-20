<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    use HasFactory;


    protected $table = 'subcategorias';

    protected $fillable = ['categoria_id', 'nombre', 'codigo', 'descripcion','estado', 'grupo_id'];

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

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }
}
