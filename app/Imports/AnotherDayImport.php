<?php

namespace Vanguard\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Vanguard\AnotherDay;

class AnotherDayImport implements ToModel, WithHeadingRow
{
    private $importedData = [];

    public function model(array $row)
    {
        $data = [
            'speaking_date' => $row['speaking_date'],
            'candidate_email' => $row['candidate_email'],
            'speaking_time' => $row['speaking_time'],
            'zoom_link' => $row['zoom_link'],
            'trainers_email' => $row['trainers_email'],
            'status' => 'on', // Default status as 'on'
        ];

        // Collect imported data for further processing (emails)
        $this->importedData[] = $data;

        return new AnotherDay($data);
    }

    public function getImportedData()
    {
        return $this->importedData;
    }

}
