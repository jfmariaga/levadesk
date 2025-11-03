<?php

namespace App\Http\Livewire\Home;

use App\Models\Estado;
use App\Models\Sociedad;
use Livewire\Component;
use App\Models\Ticket;
use App\Models\User;

class TicketsHome extends Component
{
    public $tickets;
    public $ticketsSolucionados;
    public $ticketsEnProceso;
    public $ticketsPorIniciar;
    public $totalHorasSoporte;
    public $fecha_desde, $fecha_hasta;
    public $item;
    public $estados, $SelectedEstado = [];
    public $usuarios, $selectedUsuario;
    public $agentes, $selectedAgente;
    public $sociedades, $selectedSociedad;

    protected $listeners = ['cargarDatos', 'gestionTicket'];
    protected $queryString = ['fecha_desde', 'fecha_hasta'];

    public function mount()
    {
        $this->iniciarFechas();
        // No dispares cargarDatos() aquí para no duplicar al cargar la vista.
        $this->estados    = Estado::select('id', 'nombre')->get();
        $this->usuarios   = User::select('id', 'name')->get();
        $this->agentes    = User::role(['Agente', 'Admin'])->select('id', 'name')->get();
        $this->sociedades = Sociedad::select('id', 'nombre')->get();
    }

    public function filtrarPorIniciar()
    {
        $this->SelectedEstado = [1];
        $this->cargarDatos();
    }

    public function cargarDatos()
    {
        // Aviso al front: vamos a (re)llenar la tabla
        $this->emit('ticketsStreamingStart');

        // Query base
        $q = Ticket::select(
            'id',
            'created_at',
            'titulo',
            'nomenclatura',
            'estado_id',
            'usuario_id',
            'asignado_a',
            'sociedad_id',
            'categoria_id',
            'subcategoria_id',
            'tipo_solicitud_id',
            'aplicacion_id',
            'urgencia_id'
        );

        // Filtros
        if ($this->fecha_desde && $this->fecha_hasta) {
            $desde = date('Y-m-d', strtotime($this->fecha_desde));
            $hasta = date('Y-m-d 23:59:59', strtotime($this->fecha_hasta));
            $q->whereBetween('created_at', [$desde, $hasta]);
        }
        if (is_array($this->SelectedEstado) && !empty($this->SelectedEstado)) {
            $q->whereIn('estado_id', $this->SelectedEstado);
        }
        if (!empty($this->selectedUsuario)) {
            $q->where('usuario_id', $this->selectedUsuario);
        }
        if (!empty($this->selectedAgente)) {
            $q->where('asignado_a', $this->selectedAgente);
        }
        if (!empty($this->selectedSociedad)) {
            $q->where('sociedad_id', $this->selectedSociedad);
        }

        // Orden estable para chunking
        $q->orderBy('id');

        // Tamaño de lote (ajústalo según tu servidor)
        $batchSize = 1500;

        // Carga por lotes y emite al front cada bloque
        $q->chunkById($batchSize, function ($ticketsChunk) {
            // Eager load para el lote actual (evita N+1)
            $ticketsChunk->load([
                'urgencia:id,nombre',
                'estado:id,nombre',
                'asignado:id,name',
                'categoria:id,nombre',
                'subcategoria:id,nombre',
                'usuario:id,name,area',
                'tipoSolicitud:id,nombre',
                'aplicacion:id,nombre',
                'sociedad:id,nombre',
            ]);

            // Serializa plano y ligero
            $payload = $ticketsChunk->map(function ($t) {
                return [
                    'id'             => $t->id,
                    'created_at'     => optional($t->created_at)->format('Y-m-d'),
                    'nomenclatura'   => $t->nomenclatura,
                    'titulo'         => $t->titulo,
                    'urgencia'       => $t->urgencia ? ['nombre' => $t->urgencia->nombre] : null,
                    'estado'         => $t->estado ? ['nombre' => $t->estado->nombre] : null,
                    'asignado'       => $t->asignado ? ['name' => $t->asignado->name] : ['name' => ''],
                    'categoria'      => $t->categoria ? ['nombre' => $t->categoria->nombre] : ['nombre' => ''],
                    'subcategoria'   => $t->subcategoria ? ['nombre' => $t->subcategoria->nombre] : ['nombre' => ''],
                    'usuario' => $t->usuario ? [
                        'name' => $t->usuario->name,
                        'area' => $t->usuario->area
                    ] : ['name' => '', 'area' => 'Sin seleccionar'],
                    'tipo_solicitud' => $t->tipoSolicitud ? ['nombre' => $t->tipoSolicitud->nombre] : null,
                    'aplicacion'     => $t->aplicacion ? ['nombre' => $t->aplicacion->nombre] : null,
                    'sociedad'       => $t->sociedad ? ['nombre' => $t->sociedad->nombre] : null,
                ];
            })->values()->toJson(JSON_UNESCAPED_UNICODE);

            // Enviar el lote
            $this->emit('ticketsStreamingAppend', $payload);
        });

        // Aviso final
        $this->emit('ticketsStreamingEnd');
    }

    // public function cargarDatos()
    // {
    //     $this->emit('ticketsStreamingStart');

    //     $q = Ticket::select(
    //         'id',
    //         'created_at',
    //         'titulo',
    //         'nomenclatura',
    //         'estado_id',
    //         'usuario_id',
    //         'asignado_a',
    //         'sociedad_id',
    //         'categoria_id',
    //         'subcategoria_id',
    //         'tipo_solicitud_id',
    //         'aplicacion_id',
    //         'urgencia_id',
    //         'ans_vencido',
    //         'tiempo_restante',
    //         'finalizar'
    //     );

    //     // ====== FILTROS ======
    //     if ($this->fecha_desde && $this->fecha_hasta) {
    //         $desde = date('Y-m-d', strtotime($this->fecha_desde));
    //         $hasta = date('Y-m-d 23:59:59', strtotime($this->fecha_hasta));
    //         $q->whereBetween('created_at', [$desde, $hasta]);
    //     }
    //     if (is_array($this->SelectedEstado) && !empty($this->SelectedEstado)) {
    //         $q->whereIn('estado_id', $this->SelectedEstado);
    //     }
    //     if (!empty($this->selectedUsuario)) {
    //         $q->where('usuario_id', $this->selectedUsuario);
    //     }
    //     if (!empty($this->selectedAgente)) {
    //         $q->where('asignado_a', $this->selectedAgente);
    //     }
    //     if (!empty($this->selectedSociedad)) {
    //         $q->where('sociedad_id', $this->selectedSociedad);
    //     }

    //     $q->orderBy('id');
    //     $batchSize = 1500;

    //     $q->chunkById($batchSize, function ($ticketsChunk) {
    //         $ticketsChunk->load([
    //             'urgencia:id,nombre',
    //             'estado:id,nombre',
    //             'asignado:id,name',
    //             'categoria:id,nombre',
    //             'subcategoria:id,nombre',
    //             'usuario:id,name,area',
    //             'tipoSolicitud:id,nombre',
    //             'aplicacion:id,nombre',
    //             'sociedad:id,nombre',
    //         ]);

    //         $payload = $ticketsChunk->map(function ($t) {
    //             // === 🔍 Determinar estado ANS Solución ===
    //             $estado_ANS = 'En curso';

    //             // Si está finalizado
    //             if ($t->estado_id == 4) {
    //                 if ($t->ans_vencido == 1) {
    //                     $estado_ANS = 'No cumplido';
    //                 } else {
    //                     $estado_ANS = 'Cumplido';
    //                 }
    //             }

    //             return [
    //                 'id'             => $t->id,
    //                 'created_at'     => optional($t->created_at)->format('Y-m-d'),
    //                 'nomenclatura'   => $t->nomenclatura,
    //                 'titulo'         => $t->titulo,
    //                 'urgencia'       => $t->urgencia ? ['nombre' => $t->urgencia->nombre] : null,
    //                 'estado'         => $t->estado ? ['nombre' => $t->estado->nombre] : null,
    //                 'asignado'       => $t->asignado ? ['name' => $t->asignado->name] : ['name' => ''],
    //                 'categoria'      => $t->categoria ? ['nombre' => $t->categoria->nombre] : ['nombre' => ''],
    //                 'subcategoria'   => $t->subcategoria ? ['nombre' => $t->subcategoria->nombre] : ['nombre' => ''],
    //                 'usuario' => $t->usuario ? [
    //                     'name' => $t->usuario->name,
    //                     'area' => $t->usuario->area
    //                 ] : ['name' => '', 'area' => 'Sin seleccionar'],
    //                 'tipo_solicitud' => $t->tipoSolicitud ? ['nombre' => $t->tipoSolicitud->nombre] : null,
    //                 'aplicacion'     => $t->aplicacion ? ['nombre' => $t->aplicacion->nombre] : null,
    //                 'sociedad'       => $t->sociedad ? ['nombre' => $t->sociedad->nombre] : null,
    //                 'estado_ans'     => $estado_ANS, // 👈 Nueva columna
    //             ];
    //         })->values()->toJson(JSON_UNESCAPED_UNICODE);

    //         $this->emit('ticketsStreamingAppend', $payload);
    //     });

    //     $this->emit('ticketsStreamingEnd');
    // }


    public function iniciarFechas()
    {
        $this->fecha_desde = date('Y-m-01');
        $this->fecha_hasta = date('Y-m-d');
    }

    public function render()
    {
        if (!$this->fecha_desde && !$this->fecha_hasta) {
            $this->iniciarFechas();
        }
        return view('livewire.home.tickets-home');
    }
}
