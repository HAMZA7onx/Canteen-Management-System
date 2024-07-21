<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Seeder4_UserCategories extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'New Member', 'Regular User', 'Premium User', 'VIP Member',
            'Bronze Tier', 'Silver Tier', 'Gold Tier', 'Platinum Tier',
            'Student', 'Professional', 'Educator', 'Researcher',
            'Small Business', 'Enterprise', 'Non-Profit', 'Government',
            'Developer', 'Designer', 'Manager', 'Executive',
            'Freelancer', 'Contractor', 'Consultant', 'Entrepreneur',
            'Beginner', 'Intermediate', 'Advanced', 'Expert',
            'Influencer', 'Brand Ambassador'
        ];

        foreach ($categories as $category) {
            // Check if the category already exists
            if (!DB::table('user_category')->where('name', $category)->exists()) {
                DB::table('user_category')->insert([
                    'name' => $category,
                    'description' => 'Description for ' . $category . ' category',
                    'creator' => 'System',
                    'editors' => json_encode(['System']),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
