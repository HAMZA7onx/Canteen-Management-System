<?php
namespace App\Imports;
use App\Models\AdminBadge;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class adminRfidImport implements ToModel, WithHeadingRow
{
    private $insertedCount = 0;
    private $ignoredCount = 0;
    private $totalCount = 0;

    public function model(array $row)
    {
        $this->totalCount++;
        $existingBadge = AdminBadge::where('rfid', $row['rfid'])->first();
        if ($existingBadge) {
            $this->ignoredCount++;
            Log::info("RFID {$row['rfid']} already exists. Skipping.");
            return null;
        }

        $this->insertedCount++;
        return new AdminBadge([
            'rfid' => $row['rfid'],
            'status' => 'available',
            'creator' => auth()->user()->email,
            'editors' => json_encode([]),
        ]);
    }

    public function getResult()
    {
        return [
            'inserted' => $this->insertedCount,
            'ignored' => $this->ignoredCount,
            'total' => $this->totalCount,
        ];
    }
}
