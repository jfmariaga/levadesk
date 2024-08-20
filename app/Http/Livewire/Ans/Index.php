<?php

namespace App\Http\Livewire\Ans;

use App\Models\ANS;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['cargarAns'];

    public function render()
    {
        return view('livewire.ans.index');
    }

    public function cargarAns(){
        $ans = ANS::with('solicitud')->get()->toArray();
        // dd($ans);
        $this->emit('cargarAnsTabla', json_encode($ans));
    }
}
