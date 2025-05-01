<?php

namespace App\Exports;

use App\Models\Alumni;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class AlumniExport implements 
    FromQuery, 
    WithHeadings, 
    WithMapping, 
    WithStyles, 
    WithTitle,
    WithProperties,
    WithColumnFormatting,
    WithPreCalculateFormulas,
    ShouldAutoSize
{
    protected $request;
    protected $totalRecords = 0;
    
    public function __construct($request = null)
    {
        $this->request = $request;
    }
    
    /**
     * Set custom properties for the Excel file
     */
    public function properties(): array
    {
        return [
            'creator'        => 'Institut Prima Bangsa',
            'lastModifiedBy' => auth()->user()->name ?? 'Administrator',
            'title'          => 'Data Alumni Institut Prima Bangsa',
            'description'    => 'Data alumni yang diekspor pada ' . date('d/m/Y H:i:s'),
            'subject'        => 'Data Alumni',
            'keywords'       => 'alumni, export, data, ipb',
            'category'       => 'Alumni Data',
            'manager'        => 'Admin IPB',
            'company'        => 'Institut Prima Bangsa',
        ];
    }
    
    /**
     * Set the spreadsheet title
     */
    public function title(): string
    {
        return 'Data Alumni ' . date('d-m-Y');
    }
    
    /**
     * Apply query logic for the data export with enhanced filtering
     */
    public function query()
    {
        $query = Alumni::with(['prodi', 'status'])->orderBy('tahun_lulus', 'desc');
        
        // Apply filters if provided in request
        if ($this->request) {
            if ($this->request->has('search') && $this->request->search) {
                $search = $this->request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nim', 'like', "%{$search}%");
                });
            }
            
            if ($this->request->has('prodi_id') && $this->request->prodi_id) {
                $query->where('prodi_id', $this->request->prodi_id);
            }
            
            if ($this->request->has('tahun_lulus') && $this->request->tahun_lulus) {
                $query->where('tahun_lulus', $this->request->tahun_lulus);
            }
            
            if ($this->request->has('status') && $this->request->status) {
                $status = $this->request->status;
                
                if ($status === 'bekerja') {
                    $query->whereHas('status', function ($q) {
                        $q->where('type', 'bekerja')->where('is_active', true);
                    });
                } elseif ($status === 'studi') {
                    $query->whereHas('status', function ($q) {
                        $q->where('type', 'kuliah')->where('is_active', true);
                    });
                } elseif ($status === 'wirausaha') {
                    $query->whereHas('status', function ($q) {
                        $q->where('type', 'wirausaha')->where('is_active', true);
                    });
                } elseif ($status === 'mengurus_keluarga') {
                    $query->whereHas('status', function ($q) {
                        $q->where('type', 'mengurus keluarga')->where('is_active', true);
                    });
                } elseif ($status === 'belum') {
                    $query->whereDoesntHave('status');
                }
            }

            // Add new filters
            if ($this->request->has('jenis_kelamin') && $this->request->jenis_kelamin) {
                $query->where('jenis_kelamin', $this->request->jenis_kelamin);
            }
            
            if ($this->request->has('tahun_lulus_from') && $this->request->tahun_lulus_from) {
                $query->where('tahun_lulus', '>=', $this->request->tahun_lulus_from);
            }
            
            if ($this->request->has('tahun_lulus_to') && $this->request->tahun_lulus_to) {
                $query->where('tahun_lulus', '<=', $this->request->tahun_lulus_to);
            }
        }
        
        // Store total records count for summary
        $this->totalRecords = $query->count();
        
        return $query;
    }
    
    /**
     * Define spreadsheet headings with enhanced clarity
     */
    public function headings(): array
    {
        return [
            'NIM',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Program Studi',
            'Tahun Lulus',
            'Nomor Telepon',
            'Alamat',
            'Email',
            'Status Bekerja',
            'Tempat Bekerja',
            'Jabatan',
            'Gaji',
            'Tahun Mulai Bekerja',
            'Status Kuliah Lanjut',
            'Institusi Pendidikan',
            'Jenjang',
            'Tahun Mulai Kuliah',
            'Status Wirausaha',
            'Nama Usaha',
            'Jenis Usaha',
            'Tahun Mulai Usaha',
            'Status Keluarga',
            'Tanggal Update Terakhir'
        ];
    }
    
    /**
     * Map the data to be exported with additional fields
     */
    public function map($alumni): array
    {
        // Get status data
        $statusBekerja = $alumni->status()
            ->where('type', 'bekerja')
            ->where('is_active', true)
            ->first();
            
        $statusKuliah = $alumni->status()
            ->where('type', 'kuliah')
            ->where('is_active', true)
            ->first();
        
        $statusWirausaha = $alumni->status()
            ->where('type', 'wirausaha')
            ->where('is_active', true)
            ->first();
            
        $statusKeluarga = $alumni->status()
            ->where('type', 'mengurus keluarga')
            ->where('is_active', true)
            ->first();
        
        // Format gaji if available
        $gaji = $statusBekerja && $statusBekerja->gaji 
            ? $statusBekerja->gaji 
            : null;
        
        return [
            $alumni->nim,
            $alumni->nama_lengkap,
            ucfirst($alumni->jenis_kelamin),
            $alumni->prodi->prodi_name,
            $alumni->tahun_lulus,
            $alumni->number_phone ?? '-',
            $alumni->alamat ?? '-',
            $alumni->user ? $alumni->user->email : '-',
            $statusBekerja ? 'Ya' : 'Tidak',
            $statusBekerja ? $statusBekerja->nama : '-',
            $statusBekerja ? $statusBekerja->jabatan : '-',
            $gaji,
            $statusBekerja ? $statusBekerja->tahun_mulai : '-',
            $statusKuliah ? 'Ya' : 'Tidak',
            $statusKuliah ? $statusKuliah->nama : '-',
            $statusKuliah ? $statusKuliah->jenjang : '-',
            $statusKuliah ? $statusKuliah->tahun_mulai : '-',
            $statusWirausaha ? 'Ya' : 'Tidak',
            $statusWirausaha ? $statusWirausaha->nama : '-',
            $statusWirausaha ? $statusWirausaha->jenis_pekerjaan : '-',
            $statusWirausaha ? $statusWirausaha->tahun_mulai : '-',
            $statusKeluarga ? 'Ya' : 'Tidak',
            $alumni->updated_at ? $alumni->updated_at->format('d/m/Y') : date('d/m/Y'),
        ];
    }
    
    /**
     * Define column formatting
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT, // NIM 
            'E' => NumberFormat::FORMAT_NUMBER, // Tahun Lulus
            'F' => NumberFormat::FORMAT_TEXT, // Nomor Telepon
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Gaji
            'M' => NumberFormat::FORMAT_NUMBER, // Tahun Mulai Bekerja
            'Q' => NumberFormat::FORMAT_NUMBER, // Tahun Mulai Kuliah
            'U' => NumberFormat::FORMAT_NUMBER, // Tahun Mulai Usaha
            'W' => NumberFormat::FORMAT_DATE_DDMMYYYY, // Tanggal Update
        ];
    }
    
    /**
     * Apply enhanced styling to the Excel sheet with better visual hierarchy
     */
    public function styles(Worksheet $sheet)
    {
        // Add a summary at the top
        $sheet->insertNewRowBefore(1, 3);
        $sheet->mergeCells('A1:W1');
        $sheet->setCellValue('A1', 'DATA ALUMNI INSTITUT PRIMA BANGSA');
        $sheet->mergeCells('A2:W2');
        $sheet->setCellValue('A2', 'Diekspor pada: ' . date('d/m/Y H:i:s') . ' | Total Data: ' . $this->totalRecords . ' alumni');
        
        // Add filter elements
        if ($this->request) {
            $filterText = 'Filter: ';
            if ($this->request->search) {
                $filterText .= 'Pencarian: ' . $this->request->search . ' | ';
            }
            if ($this->request->prodi_id) {
                $prodi = \App\Models\Prodi::find($this->request->prodi_id);
                if ($prodi) {
                    $filterText .= 'Prodi: ' . $prodi->prodi_name . ' | ';
                }
            }
            if ($this->request->tahun_lulus) {
                $filterText .= 'Tahun Lulus: ' . $this->request->tahun_lulus . ' | ';
            }
            if ($this->request->status) {
                $statusMap = [
                    'bekerja' => 'Bekerja',
                    'studi' => 'Studi Lanjut',
                    'wirausaha' => 'Wirausaha',
                    'mengurus_keluarga' => 'Mengurus Keluarga',
                    'belum' => 'Belum Ada Status'
                ];
                $filterText .= 'Status: ' . ($statusMap[$this->request->status] ?? $this->request->status) . ' | ';
            }
            
            // Remove trailing separator
            $filterText = rtrim($filterText, ' | ');
            
            $sheet->mergeCells('A3:W3');
            $sheet->setCellValue('A3', $filterText);
        }
        
        // Group related columns with color-coding
        // Personal info: A-H (blue theme)
        // Employment info: I-M (green theme)
        // Education info: N-Q (purple theme)
        // Entrepreneurship info: R-U (orange theme)
        // Family status: V (pink theme)
        // Other: W (gray theme)
        
        // Apply styling for column groups
        $personalCols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        $employmentCols = ['I', 'J', 'K', 'L', 'M'];
        $educationCols = ['N', 'O', 'P', 'Q'];
        $entrepreneurCols = ['R', 'S', 'T', 'U'];
        $familyCols = ['V'];
        $otherCols = ['W'];
        
        // Define colors for each group
        $personalColor = '0369A1'; // Blue
        $employmentColor = '15803d'; // Green
        $educationColor = '7e22ce'; // Purple
        $entrepreneurColor = 'c2410c'; // Orange
        $familyColor = 'be185d'; // Pink
        $otherColor = '475569'; // Slate
        
        // Apply column group colors to headers (row 4 after insertion)
        foreach ($personalCols as $col) {
            $sheet->getStyle($col . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($personalColor);
        }
        
        foreach ($employmentCols as $col) {
            $sheet->getStyle($col . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($employmentColor);
        }
        
        foreach ($educationCols as $col) {
            $sheet->getStyle($col . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($educationColor);
        }
        
        foreach ($entrepreneurCols as $col) {
            $sheet->getStyle($col . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($entrepreneurColor);
        }
        
        foreach ($familyCols as $col) {
            $sheet->getStyle($col . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($familyColor);
        }
        
        foreach ($otherCols as $col) {
            $sheet->getStyle($col . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($otherColor);
        }
        
        // Apply zebra striping to data rows
        $dataRows = $this->totalRecords + 4; // Including header and info rows
        for ($i = 5; $i <= $dataRows; $i++) {
            if ($i % 2 == 0) { // Even rows
                $sheet->getStyle('A' . $i . ':W' . $i)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('f8fafc'); // Very light slate
            }
        }
        
        // Apply border to all cells
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'cbd5e1'],
                ],
            ],
        ];
        
        $sheet->getStyle('A4:W' . $dataRows)->applyFromArray($styleArray);
        
        // Return general styles
        return [
            // Title and summary styles
            'A1' => [
                'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '0369A1']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'f0f9ff']], // Light blue
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            'A2' => [
                'font' => ['size' => 12, 'color' => ['rgb' => '475569']], // Slate
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'f8fafc']], // Very light slate
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            'A3' => [
                'font' => ['italic' => true, 'size' => 11, 'color' => ['rgb' => '64748b']], // Slate
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'f1f5f9']], // Light slate
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            ],
            // Header row style
            '4' => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            ],
            // Row height for header
            '4' => ['height' => 30],
            // All cells text alignment
            'A5:W' . ($this->totalRecords + 4) => [
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }
}