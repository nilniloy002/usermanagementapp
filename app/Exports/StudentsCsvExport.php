<?php

namespace Vanguard\Exports;

use Vanguard\StudentAdmission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsCsvExport implements FromCollection, WithHeadings, WithMapping
{
    protected $students;

    public function __construct($students)
    {
        $this->students = $students;
    }

    public function collection()
    {
        return $this->students;
    }

    public function headings(): array
    {
        return [
            'Application No.',
            'Student ID',
            'Name',
            'Mobile',
            'Email',
            'Course',
            'Batch',
            'Course Fee',
            'Deposit',
            'Discount',
            'Due',
            'Payment Method',
            'Status',
            'Approved Date',
            'Last Updated'
        ];
    }

    public function map($student): array
    {
        return [
            $student->application_number,
            $student->student_id ?? 'N/A',
            $student->name,
            $student->mobile,
            $student->email,
            $student->course_name,
            $student->batch_code ?? 'N/A',
            $student->course_fee ?? 0,
            $student->payment->deposit_amount ?? 0,
            $student->payment->discount_amount ?? 0,
            $student->payment->due_amount ?? 0,
            $student->payment->payment_method_name ?? 'N/A',
            ucfirst($student->status),
            $student->approved_at ? $student->approved_at->format('d-m-Y') : 'N/A',
            $student->updated_at->format('d-m-Y H:i'),
        ];
    }
}