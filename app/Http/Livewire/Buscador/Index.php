<?php

namespace App\Http\Livewire\Buscador;

use App\Models\Ticket;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = "bootstrap";

    public $search = '';
    public $startDate;
    public $endDate;

    public function mount()
    {
        // Establecer las fechas de inicio y fin al mes actual
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStartDate()
    {
        $this->resetPage();
    }

    public function updatingEndDate()
    {
        $this->resetPage();
    }

    public function render()
    {
        $tickets = Ticket::with(['comentarios', 'tareas'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where(function ($query) {
                $query->where('nomenclatura', 'LIKE', '%' . $this->search . '%')
                      ->orWhereHas('comentarios', function ($query) {
                          $query->where('comentario', 'LIKE', '%' . $this->search . '%');
                      })
                      ->orWhereHas('tareas', function ($query) {
                          $query->where('descripcion', 'LIKE', '%' . $this->search . '%');
                      });
            })
            ->paginate(10);

        return view('livewire.buscador.index', compact('tickets'));
    }
}
