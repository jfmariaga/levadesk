<?php

namespace App\Http\Livewire\Perfil;

use App\Models\Historial;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketAsignado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use Livewire\WithFileUploads;

class Perfil extends Component
{
    use WithFileUploads;

    public $name, $email, $current_password, $password, $password_confirmation, $profile_photo, $area;
    public $activeSection = 'profile'; // Variable para almacenar la sección activa
    public $nuevoAsignadoId; // Declarar la propiedad nuevoAsignadoId
    // public $en_vacaciones; // Propiedad para manejar el estado de vacaciones
    public function mount()
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->area = Auth::user()->area;
        $this->activeSection = session('activeSection', 'profile'); // Carga la sección activa desde la sesión
        $this->nuevoAsignadoId = null; // Inicializar como null
        // $this->en_vacaciones = Auth::user()->en_vacaciones;
    }

    public function setActiveSection($section)
    {
        $this->activeSection = $section; // Actualiza la sección activa
        session()->put('activeSection', $section); // Guarda la sección activa en la sesión
    }

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
                        $fail('Debes de ingresar un correo corporativo');
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
        $this->resetValidation();
        $this->setActiveSection('profile'); // Mantiene la sección de perfil activa
        $this->render();
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',      // Debe contener al menos una minúscula
                'regex:/[A-Z]/',      // Debe contener al menos una mayúscula
                'regex:/[0-9]/',      // Debe contener al menos un número
                'regex:/[@$!%*#?&.]/', // Debe contener al menos un carácter especial
                'confirmed',
            ],
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->emit('showToast', ['type' => 'error', 'message' => 'La contraseña actual no es correcta.']);
            return;
        }

        $user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->emit('actualizarPerfil');
        $this->emit('showToast', ['type' => 'success', 'message' => 'Contraseña actualizada con éxito.']);
        $this->setActiveSection('password'); // Mantiene la sección de contraseña activa
        $this->render();
    }


    public function marcarVacaciones()
    {
        $usuario = Auth::user(); // Obtenemos al usuario autenticado


        // Si no se ha especificado un nuevo agente, mostramos un error
        if (!$this->nuevoAsignadoId) {
            $this->emit('showToast', ['type' => 'error', 'message' => 'Debes seleccionar un agente para reasignar los tickets.']);
            return;
        }

        // Añadir el agente de respaldo en la tabla pivote (sin eliminar los anteriores backups si los hay)
        $usuario->backups()->sync([$this->nuevoAsignadoId]);
        $usuario->en_vacaciones = true;  // Solo se actualiza cuando se confirma vacaciones
        $usuario->save();

        // Filtrar los tickets asignados al usuario actual que no están en estado "finalizado" o "rechazado"
        $ticketsAsignados = Ticket::where('asignado_a', $usuario->id)
            ->whereNotIn('estado_id', [4, 5])
            ->get();

        foreach ($ticketsAsignados as $ticket) {
            // Reasignar los tickets al agente especificado
            $nuevoAsignado = User::role(['Agente', 'Admin'])  // Verificar si el usuario tiene el rol de "Agente" o "Admin"
                ->where('id', $this->nuevoAsignadoId)
                ->where('en_vacaciones', false)  // Asegurarse de que no esté en vacaciones
                ->first();


            if ($nuevoAsignado) {
                $ticket->asignado_a = $nuevoAsignado->id;

                // Guardar los cambios en el ticket
                $ticket->save();

                // Notificar al nuevo usuario asignado
                $nuevoAsignado->notify(new TicketAsignado($ticket));

                // Registrar en el historial
                Historial::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => Auth::id(),
                    'accion' => 'Asignado por vacaciones',
                    'detalle' => 'Ticket reasignado a ' . $nuevoAsignado->name,
                ]);
            } else {
                // Si el agente especificado no es válido o está en vacaciones, mostramos un error
                $this->emit('showToast', ['type' => 'error', 'message' => 'El agente seleccionado no es válido o está en vacaciones.']);
                return;
            }
        }

        // Emitir evento para mostrar notificación en la interfaz
        $this->emit('showToast', ['type' => 'success', 'message' => 'Acción correcta se han reasignado los  tickets.']);
    }

    public function volverDelTrabajo(){
        $usuario = Auth::user(); // Obtenemos al usuario autenticado
        $usuario->en_vacaciones = false;  // Solo se actualiza cuando se confirma vacaciones
        $usuario->save();
        $this->emit('showToast', ['type' => 'success', 'message' => 'Bienvenido de vuelta 💪']);
    }



    public function render()
    {
        $user = Auth::user();
        $grupos = $user->grupos;
        $sociedad = $user->sociedad;

        // Obtener los usuarios con el rol de "Agente" y que no estén de vacaciones usando Spatie
        $agentes = User::role(['Agente', 'Admin'])  // Filtrar usuarios con rol de "Agente" o "Admin"
            ->where('en_vacaciones', false)  // Filtrar los que no están en vacaciones
            ->where('id', '!=', Auth::user()->id)  // Excluir al usuario actual
            ->get();


        return view('livewire.perfil.perfil', compact('grupos', 'sociedad', 'agentes'));
    }
}
