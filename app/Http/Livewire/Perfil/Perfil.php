<?php

namespace App\Http\Livewire\Perfil;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class Perfil extends Component
{
    use WithFileUploads;

    public $name, $email, $current_password, $password, $password_confirmation, $profile_photo;
    public $activeSection = 'profile'; // Variable para almacenar la sección activa

    public function mount()
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->activeSection = session('activeSection', 'profile'); // Carga la sección activa desde la sesión
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
                    $allowedDomains = ['panalsas.com', 'levapan.com'];
                    $emailDomain = substr(strrchr($value, "@"), 1);
                    if (!in_array($emailDomain, $allowedDomains)) {
                        $fail('El correo debe pertenecer a los dominios panalsas.com o levapan.com.');
                    }
                }
            ],
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        $user = Auth::user();

        if ($this->profile_photo) {
            $photoPath = $this->profile_photo->store('profile-photos', 'public');
            $user->profile_photo = $photoPath;
        }

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
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

        $this->emit('showToast', ['type' => 'success', 'message' => 'Contraseña actualizada con éxito.']);
        $this->setActiveSection('password'); // Mantiene la sección de contraseña activa
        $this->render();
    }

    public function render()
    {
        $user = Auth::user();
        $grupos = $user->grupos;
        $sociedad = $user->sociedad;

        return view('livewire.perfil.perfil', compact('grupos', 'sociedad'));
    }
}
