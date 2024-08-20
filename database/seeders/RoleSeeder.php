<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::create(['name' => 'Admin']);
        $role2 = Role::create(['name' => 'Agente']);
        $role3 = Role::create(['name' => 'Usuario']);

        //Permisos Admin
        Permission::create(['name'=> 'home'])->syncRoles([$role1, $role2, $role3]);
        Permission::create(['name'=> 'sociedad'])->syncRoles([$role1]);
        Permission::create(['name'=> 'solicitud'])->syncRoles([$role1]);
        Permission::create(['name'=> 'categoria'])->syncRoles([$role1]);
        Permission::create(['name'=> 'subcategoria'])->syncRoles([$role1]);
        Permission::create(['name'=> 'ans'])->syncRoles([$role1]);
        Permission::create(['name'=> 'estado'])->syncRoles([$role1]);
        Permission::create(['name'=> 'cargo'])->syncRoles([$role1]);
        Permission::create(['name'=> 'grupo'])->syncRoles([$role1]);
        Permission::create(['name'=> 'urgencia'])->syncRoles([$role1]);
        Permission::create(['name'=> 'impacto'])->syncRoles([$role1]);

        //Permisos Usuario
        Permission::create(['name'=> 'ticket'])->syncRoles([$role1]);

        //permisos Agente
        Permission::create(['name'=> 'gestion'])->syncRoles([$role1,$role2]);

    }
}
