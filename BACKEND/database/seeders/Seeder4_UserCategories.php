<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Seeder4_UserCategories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_category')->insert([
            [
                'name' => 'Stuff',
                'description' => 'Stuff with full access to the system',
                'creator' => 'system@example.com',
                'editors' => json_encode([]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Formateur',
                'description' => 'Formateur with permissions to edit content',
                'creator' => 'system@example.com',
                'editors' => json_encode([]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Manager',
                'description' => 'Manager with permissions to view content only',
                'creator' => 'system@example.com',
                'editors' => json_encode([]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
