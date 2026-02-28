<?php
namespace Vanguard\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
{
    protected $students;
    protected $filters;

    public function __construct($students, $filters = [])
    {
        $this->students = $students;
        $this->filters = $filters;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return new Collection($this->students);
    }

    public function headings(): array
    {
        return [
            ['STS- STUDENT ADMISSIONS REPORT'],
            ['Generated on: ' . now()->format('d-m-Y H:i:s')],
            ['Total Students: ' . $this->students->count()],
            [], // Empty row
            [
                'SL No.',
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
                'Admission Date',
                'Admission Approval Date'
            ]
        ];
    }

    public function map($student): array
    {
        static $slNo = 0;
        $slNo++;
        
        return [
            $slNo,
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

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // SL No.
            'B' => 15,  // Application No.
            'C' => 15,  // Student ID
            'D' => 25,  // Name
            'E' => 15,  // Mobile
            'F' => 30,  // Email
            'G' => 20,  // Course
            'H' => 15,  // Batch
            'I' => 12,  // Course Fee
            'J' => 12,  // Deposit
            'K' => 12,  // Discount
            'L' => 12,  // Due
            'M' => 15,  // Payment Method
            'N' => 12,  // Status
            'O' => 15,  // Admission Date
            'P' => 18,  // Admission Approval Date
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->students->count() + 5; // 4 header rows + data rows + 1

        // Title row
        $sheet->mergeCells('A1:P1');
        $sheet->getStyle('A1:P1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '192335']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ]);

        // Generated date row
        $sheet->mergeCells('A2:P2');
        $sheet->getStyle('A2:P2')->applyFromArray([
            'font' => ['size' => 12, 'italic' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Total count row
        $sheet->mergeCells('A3:P3');
        $sheet->getStyle('A3:P3')->applyFromArray([
            'font' => ['size' => 12, 'bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Header row (row 5)
        $sheet->getStyle('A5:P5')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '192335']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ]
            ]
        ]);

        // Data rows
        $sheet->getStyle('A6:P' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ]);

        // Currency columns formatting
        $sheet->getStyle('I6:L' . $lastRow)->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);

        // Alternate row colors
        for ($i = 6; $i <= $lastRow; $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle('A' . $i . ':P' . $i)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F8F9FA');
            }
        }

        // Freeze header row
        $sheet->freezePane('A6');

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->setAutoFilter('A5:P5');
            },
        ];
    }
}