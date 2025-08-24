<?php

namespace App\Http\Livewire;

use App\Models\Tarea;
use App\Models\Ticket;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Notifications extends Component
{
    public $usuarioId;
    public $aprobaciones;
    public $cambios;
    public $tareas;
    public $tareasCount;
    public $porFinalizar;

    public function getListeners()
    {
        return [
            'actualizarNotificaciones' => 'contarTickets',
        ];
    }

    public function mount()
    {
        $this->usuarioId = Auth::user();
        $this->contarTickets();
    }

    public function contarTickets()
    {
        // Obtener las aprobaciones funcionales y de TI con ticket_id y estado pendiente
        $aprobacionesFuncionales = $this->usuarioId->aprobacionesFuncionales()
            ->where('estado', 'pendiente')
            ->get();

        $aprobacionesTi = $this->usuarioId->aprobacionesTi()
            ->where('estado', 'aprobado_funcional')
            ->get();

        // Obtener las aprobaciones funcionales y de TI para cambios
        $aprobacionesFuncionalesCambios = $this->usuarioId->aprobacionesFuncionalesCambios()
            ->where('estado', 'pendiente')
            ->get();

        $aprobacionesTiCambios = $this->usuarioId->aprobacionesTiCambios()
            ->where(function ($query) {
                $query->where('estado', 'aprobado_funcional')
                    ->orWhere(function ($query) {
                        $query->where('check_aprobado', true)
                            ->where('check_aprobado_ti', false);
                    });
            })
            ->get();

        $this->tareas = Tarea::whereIn('estado', ['en_progreso', 'pendiente', 'Aprobada'])->where('user_id', Auth::user()->id)->latest()->get();
        $this->tareasCount = Tarea::where('aprobador_id', Auth::user()->id)->where('estado', 'pendiente')->get();
        // $this->porFinalizar = Ticket::where('finalizar',1)->get();
        $user = Auth::user();

        // Tickets con finalizar = 1
        $ticketQuery = DB::table('sociedad_subcategoria_grupo')
            ->join('tickets', function ($join) {
                $join->on('sociedad_subcategoria_grupo.sociedad_id', '=', 'tickets.sociedad_id')
                    ->on('sociedad_subcategoria_grupo.categoria_id', '=', 'tickets.categoria_id')
                    ->on('sociedad_subcategoria_grupo.subcategoria_id', '=', 'tickets.subcategoria_id');
            })
            ->where('tickets.finalizar', 1)
            ->select('tickets.id', 'sociedad_subcategoria_grupo.supervisor_id', 'sociedad_subcategoria_grupo.supervisor_id_2');

        // Obtener todos los resultados
        $ticketData = $ticketQuery->get();

        // Filtrar según lógica de supervisores
        $ticketIds = $ticketData->filter(function ($ticket) use ($user) {
            if ($ticket->supervisor_id_2) {
                return $ticket->supervisor_id_2 == $user->id;
            } elseif ($ticket->supervisor_id) {
                return $ticket->supervisor_id == $user->id;
            } else {
                return $user->hasRole('Admin');
            }
        })->pluck('id');

        $this->porFinalizar = Ticket::whereIn('id', $ticketIds)->get();
        // Unir las aprobaciones y los cambios usando concat() para mantener las relaciones intactas
        $this->aprobaciones = $aprobacionesFuncionales->concat($aprobacionesTi);
        $this->cambios = $aprobacionesFuncionalesCambios->concat($aprobacionesTiCambios);
    }

    public function render()
    {
        return view('livewire.notifications', [
            'aprobaciones' => $this->aprobaciones,
            'cambios' => $this->cambios,
            'tareas' => $this->tareas,
            'tareasCount' => $this->tareasCount,
            'porFinalizar' => $this->porFinalizar,
        ]);
    }
}
