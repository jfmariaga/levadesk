<?php

namespace App\Http\Livewire\Perfil;

use App\Models\Historial;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Aplicaciones;
use App\Models\BackupFlujo;
use App\Models\SociedadSubcategoriaGrupo;
use App\Notifications\TicketAsignado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class Perfil extends Component
{
    use WithFileUploads;

    public $name, $email, $current_password, $password, $password_confirmation, $profile_photo, $area;
    public $activeSection = 'profile';
    public $nuevoAsignadoId;
    public $backupAsignaciones = [];
    public $en_vacaciones;
    public $backupsGuardados = false;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->area = $user->area;
        $this->en_vacaciones = $user->en_vacaciones;
        $this->activeSection = session('activeSection', 'profile');

        // Inicializar backupAsignaciones desde DB
        $this->backupAsignaciones = BackupFlujo::where('agente_id', $user->id)
            ->get()
            ->mapWithKeys(function ($b) {
                if ($b->flujo_id) {
                    return ['flujo:' . $b->flujo_id => $b->backup_id];
                } elseif ($b->aplicacion_id) {
                    return ['app:' . $b->aplicacion_id => $b->backup_id];
                }
                return [];
            })
            ->toArray();
    }

    public function setActiveSection($section)
    {
        $this->activeSection = $section;
        session()->put('activeSection', $section);
    }

    /** ------------------------------
     *  Actualizar perfil
     * -----------------------------*/
    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    $allowedDomains = ['panalsas.com', 'levapan.com', 'levapan.com.do', 'levapan.com.ec', 'levacolsas.com', 'levapan.com.pe'];
                    $emailDomain = substr(strrchr($value, "@"), 1);
                    if (!in_array($emailDomain, $allowedDomains)) {
                        $fail('Debes ingresar un correo corporativo.');
                    }
                }
            ],
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'area' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        if ($this->profile_photo) {
            $photoPath = $this->profile_photo->store('profile-photos', 'public');
            $user->profile_photo = $photoPath;
        }

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'area' => $this->area,
        ]);

        $this->emit('showToast', ['type' => 'success', 'message' => 'Perfil actualizado con éxito.']);
    }

    /** ------------------------------
     *  Actualizar contraseña
     * -----------------------------*/
    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&.]/',
                'confirmed',
            ],
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->emit('showToast', ['type' => 'error', 'message' => 'La contraseña actual no es correcta.']);
            return;
        }

        $user->update(['password' => Hash::make($this->password)]);
        $this->emit('showToast', ['type' => 'success', 'message' => 'Contraseña actualizada con éxito.']);
    }

    /** ------------------------------
     *  Asignar un backup global temporal
     * -----------------------------*/
    public function asignarBackupGlobal()
    {
        if (!$this->nuevoAsignadoId) {
            $this->emit('showToast', ['type' => 'warning', 'message' => 'Selecciona un agente global antes de continuar.']);
            return;
        }

        // Llenar todos los flujos y apps con el backup global
        $flujos = SociedadSubcategoriaGrupo::whereIn('grupo_id', Auth::user()->grupos->pluck('id'))->get();
        $apps = Aplicaciones::whereIn('grupo_id', Auth::user()->grupos->pluck('id'))->get();

        foreach ($flujos as $flujo) {
            $this->backupAsignaciones['flujo:' . $flujo->id] = $this->nuevoAsignadoId;
        }

        foreach ($apps as $app) {
            $this->backupAsignaciones['app:' . $app->id] = $this->nuevoAsignadoId;
        }

        $this->emit('showToast', ['type' => 'info', 'message' => 'Se asignó el backup global a todos los flujos y aplicaciones.']);
    }

    /** ------------------------------
     *  Guardar backups por flujo/aplicación
     * -----------------------------*/
    public function guardarBackups()
    {
        $usuario = Auth::user();
        BackupFlujo::where('agente_id', $usuario->id)->delete();

        foreach ($this->backupAsignaciones as $clave => $backupId) {
            if (!$backupId) continue;
            [$tipo, $id] = explode(':', $clave);

            BackupFlujo::create([
                'agente_id' => $usuario->id,
                'flujo_id' => $tipo === 'flujo' ? $id : null,
                'aplicacion_id' => $tipo === 'app' ? $id : null,
                'backup_id' => $backupId,
            ]);
        }

        $this->backupsGuardados = true;

        $this->emit('showToast', ['type' => 'success', 'message' => 'Backups guardados correctamente.']);
    }

    /** ------------------------------
     *  Marcar vacaciones y reasignar tickets
     * -----------------------------*/

    public function marcarVacaciones()
    {
        if (!$this->backupsGuardados) {
            $this->emit('showToast', [
                'type' => 'error',
                'message' => 'Debes guardar tus backups antes de marcar vacaciones.'
            ]);
            return;
        }

        $usuario = Auth::user();
        $usuario->en_vacaciones = true;
        $usuario->save();

        // Obtener todos los backups configurados
        $backups = BackupFlujo::where('agente_id', $usuario->id)->get();

        // Tickets asignados al usuario
        $tickets = Ticket::where('asignado_a', $usuario->id)
            ->whereNotIn('estado_id', [4, 5])
            ->get();

        $reasignados = 0;
        $sinBackup = [];

        foreach ($tickets as $ticket) {
            // Buscar un backup específico por flujo o aplicación
            $backup = $backups->first(function ($b) use ($ticket) {
                return ($b->flujo_id && (
                    $b->flujo_id == $ticket->grupo_id ||
                    $b->flujo_id == $ticket->subcategoria_grupo_id ||
                    $b->flujo_id == $ticket->sociedad_subcategoria_grupo_id
                )) ||
                    ($b->aplicacion_id && $b->aplicacion_id == $ticket->aplicacion_id);
            });

            // Si no hay backup específico, usar el backup global
            $nuevoBackupId = $backup->backup_id ?? $this->nuevoAsignadoId;

            if (!$nuevoBackupId) {
                // Guardamos log para diagnóstico
                $sinBackup[] = $ticket->id;
                Log::warning('Ticket sin backup', [
                    'ticket_id' => $ticket->id,
                    'grupo_id' => $ticket->grupo_id,
                    'subcategoria_grupo_id' => $ticket->subcategoria_grupo_id,
                    'aplicacion_id' => $ticket->aplicacion_id,
                ]);
                continue;
            }

            // 🔹 Marcar ticket como reasignado por vacaciones
            $ticket->update([
                'asignado_a' => $nuevoBackupId,
                'agente_original_id' => $usuario->id,
                'asignado_por_vacaciones' => true,
            ]);
            $reasignados++;

            // Notificar y registrar historial
            $nuevoAsignado = User::find($nuevoBackupId);
            if ($nuevoAsignado) {
                $nuevoAsignado->notify(new TicketAsignado($ticket));
            }

            Historial::create([
                'ticket_id' => $ticket->id,
                'user_id'   => $usuario->id,
                'accion'    => 'Asignado por vacaciones',
                'detalle'   => 'Ticket reasignado a ' . ($nuevoAsignado->name ?? 'Backup'),
            ]);
        }

        // Mostrar resumen
        if ($reasignados > 0) {
            $msg = "Se reasignaron {$reasignados} tickets correctamente.";
            if (!empty($sinBackup)) {
                $msg .= " (" . count($sinBackup) . " no se reasignaron por falta de backup)";
            }
            $this->emit('showToast', ['type' => 'success', 'message' => $msg]);
        } else {
            $this->emit('showToast', ['type' => 'warning', 'message' => 'Ningún ticket pudo ser reasignado.']);
        }
    }

    public function volverDelTrabajo()
    {
        $usuario = Auth::user();
        $usuario->en_vacaciones = false;
        $usuario->save();

        // Eliminar backups del agente
        BackupFlujo::where('agente_id', $usuario->id)->delete();

        // Recuperar solo los tickets del agente original
        $tickets = Ticket::where('agente_original_id', $usuario->id)
            ->where('asignado_por_vacaciones', true)
            ->whereNotIn('estado_id', [4, 5])
            ->get();

        foreach ($tickets as $ticket) {
            $ticket->update([
                'asignado_a' => $usuario->id,
                'asignado_por_vacaciones' => false,
                'agente_original_id' => null,
            ]);

            Historial::create([
                'ticket_id' => $ticket->id,
                'user_id'   => $usuario->id,
                'accion'    => 'Regreso de vacaciones',
                'detalle'   => 'El ticket volvió al agente original después de vacaciones.',
            ]);
        }

        $this->emit('showToast', [
            'type' => 'success',
            'message' => 'Bienvenido de vuelta 💪. Se restauraron tus tickets pendientes.',
        ]);
    }


    public function render()
    {
        $user = Auth::user();
        $grupos = $user->grupos;
        $sociedad = $user->sociedad;

        $agentes = User::role(['Agente', 'Admin'])
            ->where('en_vacaciones', false)
            ->where('id', '!=', $user->id)
            ->get();

        $flujos = SociedadSubcategoriaGrupo::whereIn('grupo_id', $user->grupos->pluck('id'))->get();
        $apps = Aplicaciones::whereIn('grupo_id', $user->grupos->pluck('id'))->get();

        return view('livewire.perfil.perfil', compact('grupos', 'sociedad', 'agentes', 'flujos', 'apps'));
    }
}
