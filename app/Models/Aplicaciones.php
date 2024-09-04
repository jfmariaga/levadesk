<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aplicaciones extends Model
{
    use HasFactory;

    protected $fillable = [
        'sociedad_id',
        'grupo',
        'nombre',
        'estado',
    ];

    public function mariaga(){
        return $this->belongsTo(Grupo::class, 'grupo');
    }

    public function sociedad(){
        return $this->belongsTo(Sociedad::class);
    }


}
