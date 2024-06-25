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
                'name' => 'Admin',
                'description' => 'Administrator with full access to the system',
                'editor' => 'System',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Editor',
                'description' => 'User with permissions to edit content',
                'editor' => 'System',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Viewer',
                'description' => 'User with permissions to view content only',
                'editor' => 'System',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
