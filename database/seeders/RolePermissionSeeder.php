<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $superAdmin = Role::findByName('Super Admin', 'web');

        $permissions = Permission::where('guard_name', 'web')->get();

        $superAdmin->syncPermissions($permissions);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
