<?php
namespace App\Imports;
use App\Models\AdminBadge;
use App\Models\Admin;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class adminRfidImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $rfid = $row['rfid'];
        $user = Admin::query()->orderBy('id')->first();

        if ($user) {
            return new AdminBadge([
                'admin_id' => $user->id,
                'rfid' => $rfid,
                'status' => 'active',
            ]);
        }
    }
}
