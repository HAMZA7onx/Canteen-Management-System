<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Admin;

class Seeder1_Admin extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Admin::create([
            'name' => 'hamza meski',
            'email' => 'meskihamza5@gmail.com',
            'password' => Hash::make('meskihamza5@gmail.com'),
            'email_verified_at' => now(),
        ]);

        $role = Role::create(['name' => 'SUPER_ADMIN', 'guard_name' => 'sanctum']);

        $allPermissions = Permission::all();

        $role->syncPermissions($allPermissions);

        $adminPermission = Permission::create(['name' => 'SUPER_ADMIN_PERMISSION', 'guard_name' => 'sanctum']);

        $role->givePermissionTo($adminPermission);


        $admin->assignRole($role);

        $this->command->info('All permissions have been assigned to the SUPER ADMIN role and the role its self assigned to meskihamza5@gmail.com.');
    }
}
