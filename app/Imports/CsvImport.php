<?php
namespace App\Imports;

use App\Models\Brand;
use Livewire\Livewire;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CsvImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        try {
            // Process each row, creating a new model
            return new Brand([
                'name' => $row['name'],
                'position' => $row['position'],
                // etc.
            ]);
        } catch (\Exception $e) {
            // Optionally handle errors here (e.g., log them, save to a separate error table, etc.)
            // Log::error('Error on row: '.$row, ['exception' => $e]);
            throw $e;
        }
    }
}