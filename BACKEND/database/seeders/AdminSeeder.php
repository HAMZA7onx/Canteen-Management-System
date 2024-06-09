<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a new user
        $user = Admin::create([
            'name' => 'hamza meski',
            'email' => 'meskihamza5@gmail.com',
            'password' => Hash::make('meskihamza5@gmail.com'),
            'email_verified_at' => now(),
        ]);

//        // Create a new role with the 'api' guard
//        $role = Role::create(['name' => 'super admin', 'guard_name' => 'sanctum']);
//
//        // Assign the role to the user with the 'api' guard
//        $user->assignRole($role);
    }
}
