<?php
namespace App\Imports;
use App\Models\Badge;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RfidImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Badge([
            'rfid' => $row['rfid'],
            'status' => 'available',
            'creator' => auth()->user()->email,
            'editors' => json_encode([]),
        ]);
    }
}
