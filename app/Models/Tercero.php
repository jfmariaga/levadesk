<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tercero extends Model
{
    use HasFactory;

    protected $table = 'terceros';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
    ];

    public function flujos()
    {
        return $this->hasMany(FlujoTercero::class, 'tercero_id');
    }

    public function tickets(){
        return $this->hasMany(Ticket::class, 'tercero_id');
    }
}
