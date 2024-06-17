<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadgesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate the table before seeding
        DB::table('badges')->truncate();

        // Get a list of user IDs
        $userIds = DB::table('users')->pluck('id')->toArray();

        // Generate some random RFID codes
        $rfidCodes = [
            'A1B2C3D4E5',
            'F6G7H8I9J0',
            'K1L2M3N4O5',
            'P6Q7R8S9T0',
            'U1V2W3X4Y5',
            '1234567890AB',
            'ABCDEF012345',
            '6789ABCDEF01',
            '23456789ABCD',
            'EF0123456789',
            'CDEF01234567',
            '89ABCDEF0123',
            '456789ABCDEF',
            '0123456789AB',
            'CDEF01234567',
            '89ABCDEF0123',
            '456789ABCDEF',
            '0123456789AB',
            'CDEF01234567',
            '89ABCDEF0123',
            '456789ABCDEF',
            '0123456789AB',
            'CDEF01234567',
            '89ABCDEF0123',
            '456789ABCDEF',
        ];

        // Seed the badges table
        foreach ($userIds as $userId) {
            foreach ($rfidCodes as $rfidCode) {
                DB::table('badges')->insert([
                    'user_id' => $userId,
                    'rfid' => $rfidCode,
                    'status' => ['active', 'inactive'][random_int(0, 1)], // Randomly set status as active or inactive
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
