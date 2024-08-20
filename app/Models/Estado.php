<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;

    protected $table = 'estados';

    protected $fillable = ['nombre', 'descripcion'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
