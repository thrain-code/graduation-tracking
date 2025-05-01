<?php

namespace App\Exports;

use App\Models\Alumni;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AlumniExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $request;
    
    public function __construct($request = null)
    {
        $this->request = $request;
    }
    
    /**
     * Apply query logic for the data export
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
                } elseif ($status === 'belum') {
                    $query->whereDoesntHave('status');
                }
            }
        }
        
        return $query;
    }
    
    /**
     * Define spreadsheet headings
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
            'Tahun Mulai Kuliah'
        ];
    }
    
    /**
     * Map the data to be exported
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
        
        // Format gaji if available
        $gaji = $statusBekerja && $statusBekerja->gaji 
            ? 'Rp ' . number_format($statusBekerja->gaji, 0, ',', '.') 
            : '-';
        
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
        ];
    }
    
    /**
     * Apply styling to the Excel sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as headers
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '0369A1']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}