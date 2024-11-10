<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'read categories']);
        Permission::create(['name' => 'delete categories']);
        Permission::create(['name' => 'create categories']);
        Permission::create(['name' => 'update categories']);

        Permission::create(['name' => 'read logs']);
        Permission::create(['name' => 'delete logs']);
        Permission::create(['name' => 'create logs']);
        Permission::create(['name' => 'update logs']);

        Permission::create(['name' => 'read sales']);
        Permission::create(['name' => 'delete sales']);
        Permission::create(['name' => 'create sales']);
        Permission::create(['name' => 'update sales']);

        Permission::create(['name' => 'read user']);
        Permission::create(['name' => 'delete user']);
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'update user']);

        Permission::create(['name' => 'read stock']);
        Permission::create(['name' => 'delete stock']);
        Permission::create(['name' => 'create stock']);
        Permission::create(['name' => 'update stock']);

        Permission::create(['name' => 'read setting']);
        Permission::create(['name' => 'delete setting']);
        Permission::create(['name' => 'create setting']);
        Permission::create(['name' => 'update setting']);

        // update cache to know about the newly created permissions (required if using WithoutModelEvents in seeders)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        // create roles and assign created permissions

        // this can be done as separate statements
        $role = Role::create(['name' => 'user']);
        $role = Role::create(['name' => 'cashier']);
        $role->givePermissionTo('create sales', 'read sales', 'update sales', 'delete sales', 'read stock', 'read categories', 'read logs');

        $roleadmin = Role::create(['name' => 'admin']);
        $roleadmin->givePermissionTo(Permission::all());

        $rolesuperadmin = Role::create(['name' => 'super-admin']);
        $rolesuperadmin->givePermissionTo(Permission::all());
    }
}
