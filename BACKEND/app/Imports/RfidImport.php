<?php

namespace App\Imports;

use App\Models\Badge;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RfidImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $rfid = $row['rfid'];
        $user = User::query()->orderBy('id')->first();

        if ($user) {
            return new Badge([
                'user_id' => $user->id,
                'rfid' => $rfid,
                'status' => 'active',
            ]);
        }
    }
}
