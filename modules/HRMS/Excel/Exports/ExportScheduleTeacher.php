<?php

namespace Modules\HRMS\Excel\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExportScheduleTeacher implements FromView, WithEvents, ShouldAutoSize, WithTitle
{
    use Exportable;

    public function title(): string
    {
        return 'Pengajuan jadwal guru';
    }

    public function view(): View
    {
        return view('administration::services.schedules_teacher.template.schedule');
    }

    private function getColumnName($index) {
        $column = '';
        while ($index >= 0) {
            $column = chr($index % 26 + 65) . $column;
            $index = intval($index / 26) - 1;
        }
        return $column;
    }

    public function iterationLoop($sheet, $callback, $row){
        $startIndex = 0;
        $endIndex = 34;

        for ($i = $startIndex; $i <= $endIndex; $i += 5) {
            $startColumn = $this->getColumnName($i);
            $endColumn = $this->getColumnName($i + 4);

            $sheet->mergeCells($startColumn . $row . ':' . $endColumn . $row);

            $callback($sheet, $startColumn, $row);
        }

        return $sheet;
    }

    private function date($sheet, $tr){
        $startDate = new \DateTime('today');
        $startDate->modify('-1 week')->modify('Monday this week');

        $this->iterationLoop($sheet, function($sheet, $startColumn, $row) use (&$startDate) {
            $cellToWrite = $startColumn . '2';
            $sheet->setCellValue($cellToWrite, $startDate->format('Y-m-d'));
            $startDate->modify('+1 day');

            $sheet->getStyle($cellToWrite)->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            $sheet->getStyle($cellToWrite)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('FFFF00');
        }, $tr);

    }

    private function label($sheet, $label = '', $tr){
        $this->iterationLoop($sheet, function($sheet, $startColumn, $row) use ($label) {
            $cellToWrite = $startColumn . 3;
            $sheet->setCellValue($cellToWrite, $label);

            $sheet->getStyle($cellToWrite)->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        }, $tr);
    }

    private function shift($sheet, $tr){
        $row = 4;
        $startIndex = 0;
        $endIndex = 34;

        for ($i = $startIndex; $i <= $endIndex; $i += 5) {
            $startColumn = $this->getColumnName($i);

            $sheet->getColumnDimension($startColumn)->setWidth(10);

            for ($shift = 1; $shift <= 5; $shift++) {
                $cellToWrite = $startColumn . $row;
                $sheet->setCellValue($cellToWrite, "$shift");

                $sheet->getStyle($cellToWrite)->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $startColumn = $this->getColumnName($i + $shift);

                $sheet->getColumnDimension($startColumn)->setWidth(10);
            }
        }
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ];

                $sheet->getStyle('A1:AI2')->applyFromArray($styleArray);
                $sheet->setCellValue('A1', 'Data Pengajar');

                $sheet->getStyle('A1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('FFFF00');

                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet = $event->sheet->getDelegate();

         //       $sheet->getProtection()->setSheet(true);

                 // Membuka proteksi pada rentang tertentu
                 $sheet->mergeCells('A1:AI1');

            // foreach (range('A', 'AI') as $column) {
            //     $sheet->getStyle($column . '1:' . $column . '1000')
            //         ->getProtection()
            //         ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
            // }

            $this->date($sheet, 2);
            $this->label($sheet, 'SHIFT', 3);
            $this->shift($sheet, 4);

            $sheet->setBreak('AJ1', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_COLUMN);

            $sheet->getSheetView()->setView(\PhpOffice\PhpSpreadsheet\Worksheet\SheetView::SHEETVIEW_PAGE_BREAK_PREVIEW);

            $sheet->getHeaderFooter()->setOddFooter('');
            $sheet->getHeaderFooter()->setEvenFooter('');
            $sheet->getHeaderFooter()->setOddHeader('');
            $sheet->getHeaderFooter()->setEvenHeader('');
            },
        ];
    }
}
