<?php

namespace App\Http\Livewire\Transportes;

use Livewire\Component;
use App\Models\Tarea;
use App\Models\Estado;
use App\Models\User;
use App\Models\Sociedad;

class Index extends Component
{
    public $fecha_desde, $fecha_hasta;
    public $SelectedEstado = [];
    public $selectedUsuario;
    public $selectedAgente;
    public $selectedSociedad;

    public $totalTransportes;
    public $transportesCompletadas;
    public $transportesPendientes;
    public $transportesRechazados;

    public $estados, $usuarios, $agentes, $sociedades;

    protected $listeners = ['cargarDatos'];

    public function calcularTotales()
    {
        $base = Tarea::where('transporte', true);

        $this->totalTransportes       = (clone $base)->count();
        $this->transportesCompletadas = (clone $base)->where('estado', 'completado')->count();
        $this->transportesPendientes  = (clone $base)->whereIn('estado', ['pendiente', 'en_progreso','aprobada','editar'])->count();
        $this->transportesRechazados  = (clone $base)->where('estado', 'rechazada')->count();
    }

    public function mount()
    {
        $this->iniciarFechas();
        $this->calcularTotales();

        $this->estados    = Estado::select('id', 'nombre')->get();
        $this->usuarios   = User::select('id', 'name')->get();
        $this->agentes    = User::role(['Agente', 'Admin'])->select('id', 'name')->get();
        $this->sociedades = Sociedad::select('id', 'nombre')->get();
    }

    public function iniciarFechas()
    {
        $this->fecha_desde = date('Y-m-01');
        $this->fecha_hasta = date('Y-m-d');
    }

    public function cargarDatos()
    {
        $this->emit('ticketsStreamingStart');

        $q = Tarea::with([
            'ticket' => function ($q) {
                $q->with([
                    'estado:id,nombre',
                    'asignado:id,name',
                    'usuario:id,name,area',
                    'sociedad:id,nombre,codigo',
                    'urgencia:id,nombre',
                    'categoria:id,nombre',
                    'subcategoria:id,nombre',
                    'tipoSolicitud:id,nombre',
                    'aplicacion:id,nombre',
                ]);
            }
        ])->where('transporte', true);

        // filtros
        if ($this->fecha_desde && $this->fecha_hasta) {
            $desde = date('Y-m-d', strtotime($this->fecha_desde));
            $hasta = date('Y-m-d 23:59:59', strtotime($this->fecha_hasta));
            $q->whereHas('ticket', fn($sub) => $sub->whereBetween('created_at', [$desde, $hasta]));
        }
        if (is_array($this->SelectedEstado) && !empty($this->SelectedEstado)) {
            $q->whereHas('ticket', fn($sub) => $sub->whereIn('estado_id', $this->SelectedEstado));
        }
        if (!empty($this->selectedUsuario)) {
            $q->whereHas('ticket', fn($sub) => $sub->where('usuario_id', $this->selectedUsuario));
        }
        if (!empty($this->selectedAgente)) {
            $q->whereHas('ticket', fn($sub) => $sub->where('asignado_a', $this->selectedAgente));
        }
        if (!empty($this->selectedSociedad)) {
            $q->whereHas('ticket', fn($sub) => $sub->where('sociedad_id', $this->selectedSociedad));
        }

        $q->orderBy('id');
        $batchSize = 1500;

        $q->chunkById($batchSize, function ($chunk) {
            $payload = $chunk->map(function ($tarea) {
                $ticket = $tarea->ticket;

                // extraer consecutivos transporte
                preg_match_all('/LVDK[0-9]+/', $tarea->descripcion ?? '', $matches);
                $transportes = implode(', ', $matches[0]);

                // país por codigo de sociedad
                $pais = 'Otro';
                if ($ticket && $ticket->sociedad) {
                    switch ($ticket->sociedad->codigo) {
                        case 'PN':
                        case 'LP':
                        case 'LC':
                        case 'TL':
                            $pais = 'Colombia';
                            break;
                        case 'EC':
                            $pais = 'Ecuador';
                            break;
                        case 'RD':
                            $pais = 'República Dominicana';
                            break;
                    }
                }

                return [
                    'id'               => $ticket->id ?? null,
                    'created_at'       => optional($ticket->created_at)->format('Y-m-d'),
                    'nomenclatura'     => $ticket->nomenclatura ?? '',
                    'urgencia'         => $ticket->urgencia?->nombre,
                    'sociedad'         => $ticket->sociedad?->nombre,
                    'pais'             => $pais,
                    'tipo'             => $ticket->tipoSolicitud?->nombre,
                    'categoria'        => $ticket->categoria?->nombre,
                    'subcategoria'     => $ticket->subcategoria?->nombre,
                    'aplicacion'       => $ticket->aplicacion?->nombre,
                    'usuario'          => $ticket->usuario?->name,
                    'area'             => $ticket->usuario?->area,
                    'estado'           => $ticket->estado?->nombre,
                    'asignado'         => $ticket->asignado?->name,
                    'tarea_titulo'     => $tarea->titulo,
                    'tarea_desc'       => $tarea->descripcion,
                    'tarea_estado'     => $tarea->estado,
                    'tarea_responsable'=> $tarea->user?->name,
                    'tarea_created_at' => optional($tarea->created_at)->format('Y-m-d H:i'),
                    'tarea_updated_at' => optional($tarea->updated_at)->format('Y-m-d H:i'),
                    'tarea_fecha_cumplimiento' => $tarea->fecha_cumplimiento,
                    'transportes'      => $transportes,
                ];
            })->values()->toJson(JSON_UNESCAPED_UNICODE);

            $this->emit('ticketsStreamingAppend', $payload);
        });

        $this->emit('ticketsStreamingEnd');
    }

    public function render()
    {
        return view('livewire.transportes.index');
    }
}
