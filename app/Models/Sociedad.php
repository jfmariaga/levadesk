<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sociedad extends Model
{
    use HasFactory;

    protected $table = 'sociedades';

    protected $fillable = ['nombre', 'descripcion','estado','codigo'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
