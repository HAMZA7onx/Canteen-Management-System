<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            Seeder0_Permissions::class,
            Seeder1_Admin::class,
            Seeder4_UserCategories::class,
            Seeder5_RoleSeeder::class,
        ]);
    }
}
