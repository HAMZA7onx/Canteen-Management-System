<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'rfid' => '1111111111111111lzz',
            'name' => 'hamza meski',
            'email' => 'meskihamza5@gmail.com',
            'password' => Hash::make('meskihamza5@gmail.com'),
            'phone_number' => '1234567890',
            'gender' => 'male',
            'email_verified_at' => now(),
        ]);

        $role = Role::create(['name' => 'super admin']);

        $user->assignRole('super admin');
    }
}
