<?php

namespace App\Http\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FormRoles extends Component
{
    public $name;
    public $permissions = [];
    public $role_id;
    public $allPermissions;
    public $selectedPermissions = [];

    protected $listeners = ['editRole'];


    protected $rules = [
        'name' => 'required|string|max:255',
        'selectedPermissions' => 'array',
    ];

    public function mount()
    {
        // Cargar todos los permisos disponibles
        $this->allPermissions = Permission::all();
    }

    public function editRole($id)
    {
        $role = Role::findById($id);
        $this->role_id = $role->id;
        $this->name = $role->name;
        // Usar 'pluck' para obtener los nombres de los permisos, no los IDs
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
    }

    public function submit()
    {
        $this->validate();

        if ($this->role_id) {
            // Editar un rol existente
            $role = Role::findById($this->role_id);
            $role->name = $this->name;
            // Sincronizar usando los nombres de los permisos
            $role->syncPermissions($this->selectedPermissions);
            $this->emit('roleUpdated');
        } else {
            // Crear un nuevo rol
            $role = Role::create(['name' => $this->name]);
            // Sincronizar permisos
            $role->syncPermissions($this->selectedPermissions);
            $this->emit('roleOk');
        }

        // Resetear el formulario después de guardar
        $this->resetForm();
        $this->emit('cargarRoles');
    }

    public function resetForm()
    {
        $this->name = '';
        $this->selectedPermissions = [];
        $this->role_id = null;
    }

    public function render()
    {
        return view('livewire.roles.form-roles');
    }
}
