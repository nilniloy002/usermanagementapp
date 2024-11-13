<?php

namespace Vanguard\Imports;

use Vanguard\MockTestResult;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class MockTestResultImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {
        $date = $row['mocktest_date'];

    // Check if date is numeric (Excel date format)
    if (is_numeric($date)) {
        // Convert from Excel date to PHP date
        $carbonDate = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date));
    } else {
        // Otherwise, parse as a standard date format
        $carbonDate = Carbon::parse($date);
    }

    return new MockTestResult([
        'name' => $row['name'],
        'mobile' => $row['mobile'],
        'mocktest_date' => $carbonDate->format('Y-m-d'),
        'lstn_cor_ans' => $row['lstn_cor_ans'],
        'lstn_score' => $row['lstn_score'],
        'speak_score' => $row['speak_score'],
        'read_cor_ans' => $row['read_cor_ans'],
        'read_score' => $row['read_score'],
        'wrt_task1' => $row['wrt_task1'],
        'wrt_task2' => $row['wrt_task2'],
        'wrt_score' => $row['wrt_score'],
        'overall_score' => $row['overall_score'],
        'status' => $row['status'],
    ]);
    }
}


