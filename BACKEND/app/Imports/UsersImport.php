<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Facades\Log;

class UsersImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    protected $categoryId;
    public $skippedRows = [];
    public $importedCount = 0;
    public $updatedCount = 0;

    public function __construct($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function model(array $row)
    {
        $user = User::withTrashed()->where('email', $row['email'])->first();

        if ($user) {
            if ($user->trashed()) {
                $user->restore();
                $this->updatedCount++;
            } else {
                $this->skippedRows[] = [
                    'row' => $row,
                    'reason' => 'Email already exists'
                ];
                return null;
            }
        } else {
            $user = new User();
            $this->importedCount++;
        }

        $user->fill([
            'name' => $row['name'],
            'email' => $row['email'],
            'phone_number' => $row['phone_number'],
            'gender' => $row['gender'],
            'category_id' => $this->categoryId,
            'affected_categories' => json_encode([$this->categoryId]),
            'creator' => auth()->user()->name ?? 'System',
            'editors' => json_encode([]),
        ]);

        $user->save();

        return $user;
    }
}
