<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Impacto extends Model
{
    use HasFactory;
    protected $table = 'impactos';
    protected $fillable = ['nombre', 'puntuacion'];

    public function tickets(){
        return $this->HasMany(Ticket::class);
    }
}
