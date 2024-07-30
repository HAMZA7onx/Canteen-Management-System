<?php
namespace App\Imports;
use App\Models\AdminBadge;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class adminRfidImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new AdminBadge([
            'rfid' => $row['rfid'],
            'status' => 'available',
            'creator' => auth()->user()->email,
            'editors' => json_encode([]),
        ]);
    }
}
