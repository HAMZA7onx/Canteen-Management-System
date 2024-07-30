<?php
namespace App\Imports;
use App\Models\Badge;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class RfidImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $existingBadge = Badge::where('rfid', $row['rfid'])->first();

        if ($existingBadge) {
            Log::info("RFID {$row['rfid']} already exists. Skipping.");
            return null;
        }

        return new Badge([
            'rfid' => $row['rfid'],
            'status' => 'available',
            'creator' => auth()->user()->email,
            'editors' => json_encode([]),
        ]);
    }
}
