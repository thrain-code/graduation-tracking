<?php

namespace App\Exports;

use App\Models\Prodi;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ImportTemplateExport implements 
    WithHeadings, 
    WithStyles, 
    ShouldAutoSize, 
    WithEvents, 
    WithProperties,
    WithColumnFormatting
{
    /**
     * Define properties for the Excel file
     */
    public function properties(): array
    {
        return [
            'creator'        => 'Institut Prima Bangsa',
            'lastModifiedBy' => auth()->user()->name ?? 'Administrator',
            'title'          => 'Template Import Data Alumni',
            'description'    => 'Template untuk import data alumni Institut Prima Bangsa',
            'subject'        => 'Template Import',
            'keywords'       => 'alumni, import, template, ipb',
            'category'       => 'Template',
            'manager'        => 'Admin IPB',
            'company'        => 'Institut Prima Bangsa',
        ];
    }
    
    /**
     * Define column formatting
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT, // NIM
            'D' => NumberFormat::FORMAT_NUMBER, // Tahun Lulus
            'F' => NumberFormat::FORMAT_TEXT, // Nomor Telepon
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Gaji
            'M' => NumberFormat::FORMAT_NUMBER, // Tahun Mulai Kerja
            'Q' => NumberFormat::FORMAT_NUMBER, // Tahun Mulai Kuliah
        ];
    }
    
    /**
     * Define headers for the template with improved clarity
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
            'tahun_mulai_kuliah',
            'status_wirausaha',
            'nama_usaha',
            'jenis_usaha',
            'tahun_mulai_usaha',
            'status_keluarga'
        ];
    }
    
    /**
     * Apply enhanced styling to the template with visual grouping
     */
    public function styles(Worksheet $sheet)
    {
        // Remove old example row and add new, more detailed examples with categories
        $sheet->setCellValue('A2', 'Contoh: John Doe');
        $sheet->setCellValue('B2', 'Contoh: 12345678');
        $sheet->setCellValue('C2', 'Ketik: Laki-laki atau Perempuan');
        $sheet->setCellValue('D2', 'Contoh: 2020');
        $sheet->setCellValue('E2', 'Contoh: PTIK');
        $sheet->setCellValue('F2', 'Contoh: 081234567890');
        $sheet->setCellValue('G2', 'Contoh: Jl. Pendidikan No. 123, Jakarta');
        $sheet->setCellValue('H2', 'Contoh: alumni@example.com');
        $sheet->setCellValue('I2', 'Ketik: Ya atau Tidak');
        $sheet->setCellValue('J2', 'Jika bekerja, isi nama perusahaan');
        $sheet->setCellValue('K2', 'Jika bekerja, isi jabatan');
        $sheet->setCellValue('L2', 'Contoh: 5000000 (angka tanpa Rp atau titik)');
        $sheet->setCellValue('M2', 'Contoh: 2021');
        $sheet->setCellValue('N2', 'Ketik: Ya atau Tidak');
        $sheet->setCellValue('O2', 'Jika kuliah, isi nama universitas');
        $sheet->setCellValue('P2', 'Contoh: S1, S2, S3, Profesi');
        $sheet->setCellValue('Q2', 'Contoh: 2021');
        $sheet->setCellValue('R2', 'Ketik: Ya atau Tidak');
        $sheet->setCellValue('S2', 'Jika wirausaha, isi nama usaha');
        $sheet->setCellValue('T2', 'Contoh: Bisnis Online, Kuliner, dll');
        $sheet->setCellValue('U2', 'Contoh: 2022');
        $sheet->setCellValue('V2', 'Ketik: Ya atau Tidak');
        
        // Add a detailed example row with complete data
        $sheet->setCellValue('A3', 'Budi Santoso');
        $sheet->setCellValue('B3', '20210001');
        $sheet->setCellValue('C3', 'Laki-laki');
        $sheet->setCellValue('D3', '2023');
        $sheet->setCellValue('E3', 'PTIK');
        $sheet->setCellValue('F3', '08123456789');
        $sheet->setCellValue('G3', 'Jl. Pahlawan No. 10, Jakarta Selatan');
        $sheet->setCellValue('H3', 'budi.s@example.com');
        $sheet->setCellValue('I3', 'Ya');
        $sheet->setCellValue('J3', 'PT Maju Bersama');
        $sheet->setCellValue('K3', 'Software Developer');
        $sheet->setCellValue('L3', '8500000');
        $sheet->setCellValue('M3', '2023');
        $sheet->setCellValue('N3', 'Tidak');
        $sheet->setCellValue('O3', '');
        $sheet->setCellValue('P3', '');
        $sheet->setCellValue('Q3', '');
        $sheet->setCellValue('R3', 'Tidak');
        $sheet->setCellValue('S3', '');
        $sheet->setCellValue('T3', '');
        $sheet->setCellValue('U3', '');
        $sheet->setCellValue('V3', 'Tidak');
        
        // Add second example with different status
        $sheet->setCellValue('A4', 'Dewi Anggraini');
        $sheet->setCellValue('B4', '20210002');
        $sheet->setCellValue('C4', 'Perempuan');
        $sheet->setCellValue('D4', '2022');
        $sheet->setCellValue('E4', 'PTIK');
        $sheet->setCellValue('F4', '08598765432');
        $sheet->setCellValue('G4', 'Jl. Melati No. 5, Bogor');
        $sheet->setCellValue('H4', 'dewi.a@example.com');
        $sheet->setCellValue('I4', 'Tidak');
        $sheet->setCellValue('J4', '');
        $sheet->setCellValue('K4', '');
        $sheet->setCellValue('L4', '');
        $sheet->setCellValue('M4', '');
        $sheet->setCellValue('N4', 'Ya');
        $sheet->setCellValue('O4', 'Universitas Indonesia');
        $sheet->setCellValue('P4', 'S2');
        $sheet->setCellValue('Q4', '2023');
        $sheet->setCellValue('R4', 'Tidak');
        $sheet->setCellValue('S4', '');
        $sheet->setCellValue('T4', '');
        $sheet->setCellValue('U4', '');
        $sheet->setCellValue('V4', 'Tidak');
        
        // Define color coding for column groups
        // Personal info: A-H (blue theme)
        // Employment info: I-M (green theme)
        // Education info: N-Q (purple theme)
        // Entrepreneurship info: R-U (orange theme)
        // Family status: V (pink theme)
        
        $personalCols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        $employmentCols = ['I', 'J', 'K', 'L', 'M'];
        $educationCols = ['N', 'O', 'P', 'Q'];
        $entrepreneurCols = ['R', 'S', 'T', 'U'];
        $familyCols = ['V'];
        
        // Define colors for each group
        $personalColor = 'dbeafe'; // Light blue
        $employmentColor = 'dcfce7'; // Light green
        $educationColor = 'f3e8ff'; // Light purple
        $entrepreneurColor = 'ffedd5'; // Light orange
        $familyColor = 'fce7f3'; // Light pink
        
        // Apply color coding to instruction examples
        foreach ($personalCols as $col) {
            $sheet->getStyle($col . '2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($personalColor);
        }
        
        foreach ($employmentCols as $col) {
            $sheet->getStyle($col . '2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($employmentColor);
        }
        
        foreach ($educationCols as $col) {
            $sheet->getStyle($col . '2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($educationColor);
        }
        
        foreach ($entrepreneurCols as $col) {
            $sheet->getStyle($col . '2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($entrepreneurColor);
        }
        
        foreach ($familyCols as $col) {
            $sheet->getStyle($col . '2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($familyColor);
        }
        
        // Apply lighter shading to example rows
        $sheet->getStyle('A3:V3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('f8fafc'); // Very light gray
        $sheet->getStyle('A4:V4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('f1f5f9'); // Light gray
        
        // Add borders
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'cbd5e1'],
                ],
            ],
        ];
        
        $sheet->getStyle('A1:V4')->applyFromArray($borderStyle);
        
        // Style for required fields - make bold with a subtle indicator
        $requiredFields = ['A1', 'B1', 'C1', 'D1', 'E1'];
        foreach ($requiredFields as $cell) {
            $sheet->getStyle($cell)->getFont()->setBold(true);
            // Add a red asterisk to the cell value
            $currentValue = $sheet->getCell($cell)->getValue();
            $sheet->setCellValue($cell, $currentValue . ' *');
        }
        
        // Return style array with additional formatting
        return [
            // Header row styling
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '0369A1']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            // Example row styling
            2 => [
                'font' => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '334155']],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ],
            // Example data styling
            '3:4' => [
                'font' => ['size' => 10],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }
    
    /**
     * Register events for the export with enhanced functionality
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $workSheet = $event->sheet->getDelegate();
                
                // Get all programmatic studies for dropdown
                $prodis = Prodi::pluck('prodi_name')->toArray();
                
                // Add dropdown lists for predefined values
                $this->addDropdownList($workSheet, 'C5:C1000', ['Laki-laki', 'Perempuan']);
                $this->addDropdownList($workSheet, 'E5:E1000', $prodis);
                $this->addDropdownList($workSheet, 'I5:I1000', ['Ya', 'Tidak']);
                $this->addDropdownList($workSheet, 'N5:N1000', ['Ya', 'Tidak']);
                $this->addDropdownList($workSheet, 'P5:P1000', ['S1', 'S2', 'S3', 'Profesi']);
                $this->addDropdownList($workSheet, 'R5:R1000', ['Ya', 'Tidak']);
                $this->addDropdownList($workSheet, 'V5:V1000', ['Ya', 'Tidak']);
                
                // Add job type suggestions
                $jobTypes = [
                    'Guru PNS', 
                    'Guru Non PNS', 
                    'Tentor/Instruktur/Pengajar', 
                    'Pengelola Kursus',
                    'Karyawan Swasta', 
                    'PNS Non-Guru', 
                    'Wiraswasta', 
                    'Freelancer',
                    'Lainnya'
                ];
                $this->addDropdownList($workSheet, 'T5:T1000', $jobTypes);
                
                // Freeze panes
                $workSheet->freezePane('A5');
                
                // Add detailed instructions at the top
                $workSheet->insertNewRowBefore(1, 2);
                $workSheet->mergeCells('A1:V1');
                $workSheet->mergeCells('A2:V2');
                
                $workSheet->setCellValue('A1', 'TEMPLATE IMPORT DATA ALUMNI - INSTITUT PRIMA BANGSA');
                $workSheet->setCellValue('A2', 'Silakan isi data alumni di bawah baris contoh (mulai baris 7). Kolom dengan tanda bintang (*) wajib diisi.');
                
                $headerStyle = [
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => '0369A1'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'dbeafe'],
                    ],
                ];
                
                $subheaderStyle = [
                    'font' => [
                        'size' => 11,
                        'color' => ['rgb' => '475569'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'f1f5f9'],
                    ],
                ];
                
                $workSheet->getStyle('A1')->applyFromArray($headerStyle);
                $workSheet->getStyle('A2')->applyFromArray($subheaderStyle);
                
                // Add section headers
                $workSheet->insertNewRowBefore(5, 1);
                
                $workSheet->mergeCells('A5:H5');
                $workSheet->setCellValue('A5', 'DATA PRIBADI');
                $workSheet->getStyle('A5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('bfdbfe');
                $workSheet->getStyle('A5')->getFont()->setBold(true);
                $workSheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $workSheet->mergeCells('I5:M5');
                $workSheet->setCellValue('I5', 'INFORMASI PEKERJAAN');
                $workSheet->getStyle('I5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('bbf7d0');
                $workSheet->getStyle('I5')->getFont()->setBold(true);
                $workSheet->getStyle('I5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $workSheet->mergeCells('N5:Q5');
                $workSheet->setCellValue('N5', 'INFORMASI PENDIDIKAN LANJUT');
                $workSheet->getStyle('N5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('e9d5ff');
                $workSheet->getStyle('N5')->getFont()->setBold(true);
                $workSheet->getStyle('N5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $workSheet->mergeCells('R5:U5');
                $workSheet->setCellValue('R5', 'INFORMASI WIRAUSAHA');
                $workSheet->getStyle('R5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('fed7aa');
                $workSheet->getStyle('R5')->getFont()->setBold(true);
                $workSheet->getStyle('R5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $workSheet->mergeCells('V5:V5');
                $workSheet->setCellValue('V5', 'KELUARGA');
                $workSheet->getStyle('V5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('fbcfe8');
                $workSheet->getStyle('V5')->getFont()->setBold(true);
                $workSheet->getStyle('V5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Set column widths for better readability
                foreach (range('A', 'V') as $col) {
                    $width = 15; // Default width
                    
                    // Custom widths for specific columns
                    if (in_array($col, ['A', 'G', 'J', 'O', 'S'])) { 
                        $width = 25; // Wider columns for names and addresses
                    } elseif (in_array($col, ['T'])) {
                        $width = 20; // Medium width for job types
                    }
                    
                    $workSheet->getColumnDimension($col)->setWidth($width);
                }
                
                // Add comments/notes to help users
                $this->addCommentToCell($workSheet, 'L6', 'Masukkan nilai gaji dalam bentuk angka tanpa tanda koma atau titik. Contoh: 5000000');
                $this->addCommentToCell($workSheet, 'I6', 'Isi "Ya" jika alumni bekerja, dan "Tidak" jika tidak bekerja. Jika "Ya", kolom J-M harus diisi.');
                $this->addCommentToCell($workSheet, 'N6', 'Isi "Ya" jika alumni melanjutkan studi, dan "Tidak" jika tidak. Jika "Ya", kolom O-Q harus diisi.');
                $this->addCommentToCell($workSheet, 'E6', 'Pilih program studi dari daftar. Jika tidak ada, tambahkan program studi baru di sistem terlebih dahulu.');
                $this->addCommentToCell($workSheet, 'B6', 'NIM harus unik dan tidak boleh duplikat dengan alumni yang sudah ada.');
                $this->addCommentToCell($workSheet, 'H6', 'Email harus unik dan akan digunakan untuk login. Jika tidak diisi, sistem akan otomatis membuat email berdasarkan nama.');
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
        $validation->setErrorTitle('Input error');
        $validation->setError('Nilai tidak ada dalam daftar.');
        $validation->setPromptTitle('Pilih dari daftar');
        $validation->setPrompt('Silakan pilih nilai dari daftar yang tersedia.');
        $validation->setFormula1('"' . implode(',', $options) . '"');
    }
    
    /**
     * Add comment to cell
     */
    private function addCommentToCell($sheet, $cell, $text)
    {
        $comment = $sheet->getComment($cell);
        $comment->setAuthor('Admin IPB');
        $comment->getText()->createTextRun($text);
    }
}