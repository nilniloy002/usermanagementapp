<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DailyRevenueExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithEvents
{
    protected $students;
    protected $startDate;
    protected $endDate;
    protected $totalDeposit;
    protected $totalDiscount;
    protected $totalDue;

    public function __construct($students, $startDate, $endDate, $totalDeposit, $totalDiscount, $totalDue)
    {
        $this->students = $students;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->totalDeposit = $totalDeposit;
        $this->totalDiscount = $totalDiscount;
        $this->totalDue = $totalDue;
    }

    public function collection()
    {
        $data = [];
        
        foreach ($this->students as $student) {
            $data[] = [
                'Date' => $student->created_at->format('d-m-Y H:i'),
                'Invoice No.' => $student->application_number,
                'Student ID' => $student->student_id ?? 'N/A',
                'Name' => $student->name,
                'Paid' => $student->payment->deposit_amount ?? 0,
                'Discount' => $student->payment->discount_amount ?? 0,
                'Due' => $student->payment->due_amount ?? 0,
                'P.M' => $student->payment->payment_method ?? 'Admission',
                'R.B' => $student->payment->payment_received_by ?? 'N/A',
            ];
        }
        
        return new Collection($data);
    }

    public function headings(): array
    {
        return [
            ['DAILY REVENUE REPORT'],
            ['Period: ' . $this->startDate . ' to ' . $this->endDate],
            ['Generated on: ' . now()->format('d-m-Y H:i:s')],
            [], // Empty row
            ['Date', 'Invoice No.', 'Student ID', 'Name', 'Paid', 'Discount', 'Due', 'P.M', 'R.B']
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18, // Date
            'B' => 15, // Invoice No.
            'C' => 12, // Student ID
            'D' => 25, // Name
            'E' => 12, // Paid
            'F' => 12, // Discount
            'G' => 12, // Due
            'H' => 15, // P.M
            'I' => 15, // R.B
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->students->count() + 6; // 5 header rows + data rows
        
        // Header styles
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');
        
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ]);
        
        $sheet->getStyle('A2:I3')->applyFromArray([
            'font' => [
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ]);
        
        // Column headers (row 5)
        $sheet->getStyle('A5:I5')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '343a40']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ]
        ]);
        
        // Data rows
        $sheet->getStyle('A6:I' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ]
        ]);
        
        // Currency columns alignment and format
        $sheet->getStyle('E6:G' . $lastRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
            'numberFormat' => [
                'formatCode' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2
            ]
        ]);
        
        // Add totals row
        $totalsRow = $lastRow + 2;
        
        $sheet->setCellValue('D' . $totalsRow, 'GRAND TOTAL:');
        $sheet->setCellValue('E' . $totalsRow, $this->totalDeposit);
        $sheet->setCellValue('F' . $totalsRow, $this->totalDiscount);
        $sheet->setCellValue('G' . $totalsRow, $this->totalDue);
        
        $sheet->getStyle('D' . $totalsRow)->applyFromArray([
            'font' => ['bold' => true]
        ]);
        
        $sheet->getStyle('E' . $totalsRow . ':G' . $totalsRow)->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
            'numberFormat' => [
                'formatCode' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2
            ]
        ]);
        
        // Summary section
        $summaryRow = $lastRow + 4;
        $sheet->setCellValue('A' . $summaryRow, 'Summary');
        $sheet->mergeCells('A' . $summaryRow . ':B' . $summaryRow);
        $sheet->getStyle('A' . $summaryRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 14]
        ]);
        
        $sheet->setCellValue('A' . ($summaryRow + 1), 'Total Paid Amount:');
        $sheet->setCellValue('B' . ($summaryRow + 1), $this->totalDeposit);
        
        $sheet->setCellValue('A' . ($summaryRow + 2), 'Total Discount:');
        $sheet->setCellValue('B' . ($summaryRow + 2), $this->totalDiscount);
        
        $sheet->setCellValue('A' . ($summaryRow + 3), 'Total Due:');
        $sheet->setCellValue('B' . ($summaryRow + 3), $this->totalDue);
        
        $sheet->getStyle('B' . ($summaryRow + 1) . ':B' . ($summaryRow + 3))->applyFromArray([
            'numberFormat' => [
                'formatCode' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2
            ]
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // No additional events needed
            },
        ];
    }
}