<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ImportTemplateExport implements WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    /**
     * Define headers for the template
     */
    public function headings(): array
    {
        return [
            'nama_lengkap',
            'nim',
            'jenis_kelamin',
            'tahun_lulus',
            'prodi',
            'number_phone',
            'alamat',
            'email',
            'status_kerja',
            'nama_perusahaan',
            'jabatan',
            'gaji',
            'tahun_mulai_kerja',
            'status_kuliah',
            'nama_universitas',
            'jenjang',
            'tahun_mulai_kuliah'
        ];
    }
    
    /**
     * Apply styling to the template
     */
    public function styles(Worksheet $sheet)
    {
        // Add notes to cells
        $sheet->setCellValue('A2', 'Contoh: John Doe');
        $sheet->setCellValue('B2', 'Contoh: 12345678');
        $sheet->setCellValue('C2', 'Ketik: Laki-laki atau Perempuan');
        $sheet->setCellValue('D2', 'Contoh: 2020');
        $sheet->setCellValue('E2', 'Contoh: PTIK');
        $sheet->setCellValue('F2', 'Contoh: 081234567890');
        $sheet->setCellValue('G2', 'Contoh: Jl. Pendidikan No. 123');
        $sheet->setCellValue('H2', 'Contoh: alumni@example.com');
        $sheet->setCellValue('I2', 'Ketik: Ya atau Tidak');
        $sheet->setCellValue('J2', 'Jika bekerja, isi nama perusahaan');
        $sheet->setCellValue('K2', 'Jika bekerja, isi jabatan');
        $sheet->setCellValue('L2', 'Contoh: 5000000');
        $sheet->setCellValue('M2', 'Contoh: 2021');
        $sheet->setCellValue('N2', 'Ketik: Ya atau Tidak');
        $sheet->setCellValue('O2', 'Jika kuliah, isi nama universitas');
        $sheet->setCellValue('P2', 'Contoh: S2');
        $sheet->setCellValue('Q2', 'Contoh: 2021');
        
        // Style the example row
        $sheet->getStyle('A2:Q2')->getFont()->setItalic(true)
            ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKBLUE));
            
        // Style the header row
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '0369A1']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
            'A2:Q2' => [
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => 'E0F2FE']],
            ],
        ];
    }
    
    /**
     * Register events for the export
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $workSheet = $event->sheet->getDelegate();
                
                // Add dropdown lists for predefined values
                $this->addDropdownList($workSheet, 'C3:C1000', ['Laki-laki', 'Perempuan']);
                $this->addDropdownList($workSheet, 'I3:I1000', ['Ya', 'Tidak']);
                $this->addDropdownList($workSheet, 'N3:N1000', ['Ya', 'Tidak']);
                $this->addDropdownList($workSheet, 'P3:P1000', ['S1', 'S2', 'S3', 'Profesi']);
                
                // Freeze header row
                $workSheet->freezePane('A3');
                
                // Add instructions
                $workSheet->insertNewRowBefore(1, 2);
                $workSheet->mergeCells('A1:Q1');
                $workSheet->setCellValue('A1', 'TEMPLATE IMPORT DATA ALUMNI - Silakan isi data di bawah baris contoh (mulai baris 4)');
                $workSheet->getStyle('A1')->getFont()->setBold(true)
                    ->setSize(14)
                    ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKBLUE));
                $workSheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $workSheet->getStyle('A1')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('E0F2FE');
            },
        ];
    }
    
    /**
     * Add dropdown list validation to cells
     */
    private function addDropdownList($sheet, $range, $options)
    {
        $validation = $sheet->getDataValidation($range);
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setFormula1('"' . implode(',', $options) . '"');
    }
}