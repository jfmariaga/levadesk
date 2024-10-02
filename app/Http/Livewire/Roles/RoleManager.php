<?php

namespace App\Http\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleManager extends Component
{
    protected $listeners = ['cargarRoles'];

    public function cargarRoles(){
        $roles = Role::with('permissions')->get()->toArray();
        $this->emit('cargarRolesTabla', json_encode($roles));
    }
    public function render()
    {
        return view('livewire.roles.role-manager');
    }
}
