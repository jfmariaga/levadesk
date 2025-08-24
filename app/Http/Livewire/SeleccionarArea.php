<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SeleccionarArea extends Component
{
    public $area = '';

    public function guardar()
    {
        $this->validate([
            'area' => 'required|string|max:255'
        ]);

        $user = Auth::user();
        $user->area = $this->area;
        $user->save();

        return redirect()->intended('/home');
    }
    
    public function render()
    {
        return view('livewire.seleccionar-area');
    }
}
