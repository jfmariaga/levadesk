<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;


    protected $table = 'grupos';

    protected $fillable = ['nombre', 'descripcion'];

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'grupo_user', 'grupo_id', 'user_id');
    }


    // public function subcategorias()
    // {
    //     return $this->hasMany(Subcategoria::class);
    // }

    public  function aplicaciones()
    {
        return $this->hasMany(Aplicaciones::class);
    }

    public function sociedadesSubcategoriasGrupos()
    {
        return $this->hasMany(SociedadSubcategoriaGrupo::class, 'grupo_id');
    }
}
