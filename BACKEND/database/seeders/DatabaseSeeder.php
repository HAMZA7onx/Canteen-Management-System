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
            Seeder4_UserCategories::class,
            Seeder5_Users::class,
            Seeder6_RecordsDashboard::class,
            Seeder7_WeekSchedule::class,
        ]);
    }
}
