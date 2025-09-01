<?php

namespace App\Http\Livewire\Usuarios;

use App\Models\Sociedad;
use App\Models\User;
use App\Models\Ticket;
use App\Notifications\TicketReasignadoNotification;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class FormUsuarios extends Component
{
    public $name, $email, $rol = [], $sociedad_id, $estado, $aprobador_ti;
    public $usuario_old;
    public $sociedades = [];
    public $roles = [];

    // Tickets pendientes
    public $ticketsComoUsuario = [];
    public $ticketsComoAgente = [];
    public $reasignacionesUsuario = []; // ticket_id => nuevo usuario_id
    public $reasignacionesAgente = [];  // ticket_id => nuevo agente_id

    protected $listeners = ['editUsuarios'];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required',
        'rol' => 'required',
        'sociedad_id' => 'required',
        'estado' => 'required',
    ];

    public function mount()
    {
        $this->sociedades = Sociedad::where('estado', 0)->get();
        $this->roles = Role::all();
    }

    public function editUsuarios($id)
    {
        $this->usuario_old = User::find($id);
        $this->name = $this->usuario_old->name;
        $this->email  = $this->usuario_old->email;
        $this->rol = $this->usuario_old->roles->pluck('id')->toArray();
        $this->sociedad_id  = $this->usuario_old->sociedad_id;
        $this->estado  = $this->usuario_old->estado;
        $this->aprobador_ti  = $this->usuario_old->aprobador_ti;

        $this->emit('selectSociedad', $this->sociedad_id);
        $this->emit('selectRol', $this->rol);
    }

    public function actualizar()
    {
        $this->validate();

        if ($this->usuario_old) {
            // Caso especial: si se va a inactivar
            if ($this->usuario_old->estado == 1 && $this->estado == 0) {
                // Tickets como usuario
                $this->ticketsComoUsuario = Ticket::where('usuario_id', $this->usuario_old->id)
                    ->where('estado_id', '<>', 4)
                    ->with('estado', 'asignado')
                    ->get()
                    ->toArray();

                // Tickets como agente
                $this->ticketsComoAgente = Ticket::where('asignado_a', $this->usuario_old->id)
                    ->where('estado_id', '<>', 4)
                    ->with('estado', 'asignado')
                    ->get()
                    ->toArray();

                if (count($this->ticketsComoUsuario) > 0 || count($this->ticketsComoAgente) > 0) {
                    $this->emit('showTicketsPendientes');
                    return;
                }
            }

            // Guardar normalmente si no tiene tickets
            $this->guardarUsuario();
        }
    }

    public function guardarUsuario()
    {
        $this->usuario_old->name = $this->name;
        $this->usuario_old->email = $this->email;
        $this->usuario_old->sociedad_id = $this->sociedad_id;
        $this->usuario_old->estado = $this->estado;
        $this->usuario_old->aprobador_ti = $this->aprobador_ti ? $this->aprobador_ti : 0;
        $this->usuario_old->roles()->sync($this->rol);
        $this->usuario_old->update();

        $this->emit('usuario_actualizado');
        $this->emit('cargarUsuarios');
        $this->resetear();
    }

    public function confirmarReasignacion()
    {

        // dd($this->reasignacionesAgente,$this->ticketsComoUsuario);
        // Validar que todos los tickets fueron reasignados
        foreach ($this->ticketsComoUsuario as $ticket) {
            if (empty($this->reasignacionesUsuario[$ticket['id']])) {
                $this->emit('showToast', [
                    'type' => 'error',
                    'message' => "Debes reasignar todos los tickets (como usuario)."
                ]);
                return;
            }
        }
        foreach ($this->ticketsComoAgente as $ticket) {
            if (empty($this->reasignacionesAgente[$ticket['id']])) {
                $this->emit('showToast', [
                    'type' => 'error',
                    'message' => "Debes reasignar todos los tickets (como agente)."
                ]);
                return;
            }
        }

        // Reasignar tickets como usuario
        foreach ($this->reasignacionesUsuario as $ticketId => $nuevoUsuarioId) {
            // dd($nuevoUsuarioId);
            if ($nuevoUsuarioId) {
                $ticket = Ticket::find($ticketId);
                $ticket->usuario_id = $nuevoUsuarioId;
                $ticket->save();

                $nuevoUsuario = User::find($nuevoUsuarioId);
                if ($nuevoUsuario) {
                    $nuevoUsuario->notify(new TicketReasignadoNotification($ticket));
                }
            }
        }

        //Reasignar tickets como agente
        foreach ($this->reasignacionesAgente as $ticketId => $nuevoAgenteId) {
            if ($nuevoAgenteId) {
                $ticket = Ticket::find($ticketId);
                $ticket->asignado_a = $nuevoAgenteId;
                $ticket->save();

                $nuevoAgente = User::find($nuevoAgenteId);
                if ($nuevoAgente) {
                    $nuevoAgente->notify(new TicketReasignadoNotification($ticket));
                }
            }
        }

        // $debugData = []; // arreglo temporal para pruebas

        // foreach ($this->reasignacionesAgente as $ticketId => $nuevoAgenteId) {
        //     if ($nuevoAgenteId) {
        //         $ticket = Ticket::find($ticketId);
        //         $ticket->asignado_a = $nuevoAgenteId;
        //         $ticket->save();

        //         $nuevoAgente = User::find($nuevoAgenteId);

        //         if ($nuevoAgente) {
        //             $debugData[] = [
        //                 'ticket_id'      => $ticket->id,
        //                 'nomenclatura'   => $ticket->nomenclatura,
        //                 'titulo'         => $ticket->titulo,
        //                 'agente_id'      => $nuevoAgente->id,
        //                 'agente_nombre'  => $nuevoAgente->name,
        //                 'agente_email'   => $nuevoAgente->email,
        //             ];

        //             // comentar la notificación mientras pruebas
        //             $nuevoAgente->notify(new TicketReasignadoNotification($ticket));
        //         }
        //     }
        // }

        // // Mostrar TODO el resumen al final
        // dd($debugData);

        // después inactivar al usuario
        $this->estado = 0;
        $this->guardarUsuario();

        $this->emit('closeTicketsPendientes');
    }

    public function resetear()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function render()
    {
        $usuariosActivos = User::where('estado', 1)->get();
        return view('livewire.usuarios.form-usuarios', compact('usuariosActivos'));
    }
}
