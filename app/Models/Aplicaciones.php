<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aplicaciones extends Model
{
    use HasFactory;

    protected $fillable = [
        'sociedad_id',
        'grupo_id',
        'nombre',
        'estado',
    ];

    public function grupo(){
        return $this->belongsTo(Grupo::class,'grupo_id');
    }

    public function sociedad(){
        return $this->belongsTo(Sociedad::class);
    }

    public function tickets(){
        return $this->hasMany(Ticket::class);
    }


}
