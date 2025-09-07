<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\CompletarAreaNotification;

class NotificarUsuariosSinArea extends Command
{
    protected $signature = 'usuarios:notificar-sin-area';
    protected $description = 'Notifica a los usuarios que no han indicado su área';

    public function handle()
    {
        $usuarios = User::whereNull('area')->get();

        foreach ($usuarios as $usuario) {
            $usuario->notify(new CompletarAreaNotification());
        }

        // $this->info('Notificaciones enviadas a usuarios sin área.');
    }
}
