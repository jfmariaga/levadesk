<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlujoTercero extends Model
{
    use HasFactory;
    protected $table = 'flujos_terceros';
    // protected $guarded = [];

    // public $timestamps = false;

    protected $fillable = [
        'aplicacion_id',
        'usuario_id',
        'tercero_id',
        'destinatarios',
        'activo',
    ];

    protected $casts = [
        'destinatarios' => 'array',
    ];

    public function aplicacion()
    {
        return $this->belongsTo(Aplicaciones::class, 'aplicacion_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function tercero()
    {
        return $this->belongsTo(Tercero::class, 'tercero_id');
    }
}
