<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'estado',        // Nuevo campo
        'sociedad_id',   // Nuevo campo
        'profile_photo',  // Nuevo campo
        'en_vacaciones'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupo_user', 'user_id', 'grupo_id');
    }


    public function ticketsAsignados()
    {
        return $this->hasMany(Ticket::class, 'asignado_a');
    }

    public function adminlte_image()
    {
        return $this->profile_photo ? asset('storage/' . $this->profile_photo) : 'https://picsum.photos/300/300';
    }

    public function adminlte_desc()
    {
        // Retorna una breve descripción o cargo del usuario.
        return $this->roles->first()->name ?? 'Visitante';
    }

    public function adminlte_profile_url()
    {
        // Aquí se puede retornar la URL del perfil del usuario.
        return route('perfil');
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    public function colaboradores()
    {
        return $this->belongsToMany(User::class, 'ticket_colaboradores');
    }

    public function recordatorios()
    {
        return $this->hasMany(Recordatorio::class);
    }

    public function colaboradors()
    {
        return $this->hasMany(Colaborador::class);
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    public function aprobacionesFuncionales()
    {
        return $this->hasMany(Aprobacion::class, 'aprobador_funcional_id');
    }

    // Relación para las aprobaciones donde el usuario es líder de TI
    public function aprobacionesTi()
    {
        return $this->hasMany(Aprobacion::class, 'aprobador_ti_id');
    }

    public function aprobacionesFuncionalesCambios()
    {
        return $this->hasMany(Cambios::class, 'aprobador_funcional_id');
    }

    // Relación para las aprobaciones donde el usuario es líder de TI
    public function aprobacionesTiCambios()
    {
        return $this->hasMany(Cambios::class, 'aprobador_ti_id');
    }

    public function sociedad()
    {
        return $this->belongsTo(Sociedad::class);
    }
}
