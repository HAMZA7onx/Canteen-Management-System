<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class Seeder5_RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Super Admin',
            'Administrator',
            'Manager',
            'Editor',
            'Author',
            'Contributor',
            'Moderator',
            'Subscriber',
            'Customer',
            'Guest',
            'Developer',
            'Designer',
            'Content Creator',
            'Marketing Specialist',
            'Sales Representative',
            'Human Resources',
            'Finance Manager',
            'Accountant',
            'Support Specialist',
            'Quality Assurance',
            'Project Manager',
            'Team Leader',
            'Analyst',
            'Data Scientist',
            'Product Owner',
            'Scrum Master',
            'UX Designer',
            'UI Designer',
            'SEO Specialist',
            'Social Media Manager',
        ];

        foreach ($roles as $role) {
            Role::create([
                'name' => $role,
                'guard_name' => 'sanctum'
            ]);
        }
    }
}
