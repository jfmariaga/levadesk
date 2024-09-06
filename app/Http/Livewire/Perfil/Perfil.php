<?php

namespace App\Http\Livewire\Perfil;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Perfil extends Component
{
    public $name, $email, $current_password, $password, $password_confirmation;

    // Se ejecuta al montar el componente
    public function mount()
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    // Método para actualizar el perfil del usuario
    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        session()->flash('message', 'Perfil actualizado con éxito.');
    }

    // Método para cambiar la contraseña del usuario
    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Verificar la contraseña actual
        if (!Hash::check($this->current_password, $user->password)) {
            session()->flash('error', 'La contraseña actual no es correcta.');
            return;
        }

        // Actualizar la contraseña
        $user->update([
            'password' => Hash::make($this->password),
        ]);

        session()->flash('message', 'Contraseña actualizada con éxito.');
    }
    public function render()
    {
        return view('livewire.perfil.perfil');
    }
}
