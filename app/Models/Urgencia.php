<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Urgencia extends Model
{
    use HasFactory;

    protected $table = 'urgencias';
    protected $fillable = ['nombre', 'puntuacion'];

    public function tickets(){
        return $this->HasMany(Ticket::class);
    }
}
