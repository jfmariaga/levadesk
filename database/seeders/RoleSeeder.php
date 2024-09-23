<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Deshabilitar las verificaciones de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncar las tablas relacionadas con roles y permisos
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();

        // Habilitar nuevamente las verificaciones de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // Crear roles
        $role1 = Role::create(['name' => 'Admin']);
        $role2 = Role::create(['name' => 'Agente']);
        $role3 = Role::create(['name' => 'Usuario']);
        $role4 = Role::create(['name' => 'Aprobador']);

        // Permisos compartidos entre todos los roles
        Permission::create(['name' => 'home'])->syncRoles([$role1, $role2, $role3, $role4]);

        // Permisos específicos de cada rol
        Permission::create(['name' => 'sociedad'])->syncRoles([$role1]);
        Permission::create(['name' => 'solicitud'])->syncRoles([$role1]);
        Permission::create(['name' => 'categoria'])->syncRoles([$role1]);
        Permission::create(['name' => 'subcategoria'])->syncRoles([$role1]);
        Permission::create(['name' => 'ans'])->syncRoles([$role1]);
        Permission::create(['name' => 'estado'])->syncRoles([$role1]);
        Permission::create(['name' => 'cargo'])->syncRoles([$role1]);
        Permission::create(['name' => 'grupo'])->syncRoles([$role1]);
        Permission::create(['name' => 'urgencia'])->syncRoles([$role1]);
        Permission::create(['name' => 'impacto'])->syncRoles([$role1]);
        
        Permission::create(['name' => 'relacion'])->syncRoles([$role1]);

        // Permisos compartidos para Admin, Agente, Usuario y Aprobador
        Permission::create(['name' => 'ticket'])->syncRoles([$role1, $role2, $role3, $role4]);
        Permission::create(['name' => 'verTicket'])->syncRoles([$role1, $role2, $role3, $role4]);

        // Permisos para agentes (Admin y Agente)
        Permission::create(['name' => 'gestion'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'gestionar'])->syncRoles([$role1, $role2]);

        // Permisos para Aprobador
        Permission::create(['name' => 'aprobacion'])->syncRoles([$role1, $role2, $role4]);
        Permission::create(['name' => 'aprobar'])->syncRoles([$role1, $role2, $role4]);

        // Permisos de cambios
        Permission::create(['name' => 'cambios'])->syncRoles([$role1, $role2, $role4]);
        Permission::create(['name' => 'cambio'])->syncRoles([$role1, $role2, $role4]);

        // Permiso para ver sociedades (solo Admin)
        Permission::create(['name' => 'sociedades'])->syncRoles([$role1]);

        // Permiso para ver y gestionar usuarios (solo Admin)
        Permission::create(['name' => 'usuarios'])->syncRoles([$role1]);
    }
}
