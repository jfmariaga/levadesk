<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Excepciones extends Model
{
    use HasFactory;

    protected $table   = 'excepciones' ;
    protected $guarded = [];
    public $timestamps = false;

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
