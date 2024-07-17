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

    private function generateMatriculationNumber($category)
    {
        $year = date('Y');
        $categoryCode = strtoupper(substr($category->name, 0, 3));
        $lastUser = User::where('category_id', $this->categoryId)
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        $sequentialNumber = $lastUser ? (intval(substr($lastUser->matriculation_number, -4)) + 1) : 1;
        return $year . $categoryCode . str_pad($sequentialNumber, 4, '0', STR_PAD_LEFT);
    }


    public function model(array $row)
    {
        $user = User::withTrashed()->where('email', $row['email'])->first();
        $category = \App\Models\UserCategory::find($this->categoryId);

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
            'matriculation_number' => $this->generateMatriculationNumber($category),
        ]);
        $user->save();
        return $user;
    }
}
