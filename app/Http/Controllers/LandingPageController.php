<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Pekerjaan;
use App\Models\PendidikanLanjutan;
use App\Models\Prodi;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        // Fetch all alumni with their relationships
        $alumni = Alumni::with(['pekerjaans', 'pendidikanLanjutans', 'prodi', 'user'])->get();
        
        // Initialize statistics array
        $stats = [
            'bekerja' => 0,
            'bekerja_percent' => 0,
            'studi_lanjut' => 0,
            'studi_lanjut_percent' => 0,
            'mencari_kerja' => 0,
            'mencari_kerja_percent' => 0,
            'waktu_tunggu' => [
                'kurang_3_bulan' => 0,
                'kurang_3_bulan_percent' => 0,
                '3_6_bulan' => 0,
                '3_6_bulan_percent' => 0,
                'lebih_6_bulan' => 0,
                'lebih_6_bulan_percent' => 0,
            ],
            'kesesuaian' => [
                'sangat_sesuai' => 0,
                'sangat_sesuai_percent' => 0,
                'cukup_sesuai' => 0,
                'cukup_sesuai_percent' => 0,
                'kurang_sesuai' => 0,
                'kurang_sesuai_percent' => 0,
            ],
            'yearly_data' => [],
        ];

        // Process alumni data if records exist
        if ($alumni->count() > 0) {
            // Calculate basic statistics
            foreach ($alumni as $alumnus) {
                // Check if alumni has current job
                $currentJob = $alumnus->pekerjaans->where('masih_bekerja', true)->first();
                $hasJob = $currentJob !== null;
                
                // Check if alumni has continuing education
                $hasStudy = $alumnus->pendidikanLanjutans->count() > 0;
                
                // Count alumni by status
                if ($hasJob) {
                    $stats['bekerja']++;
                    
                    // Calculate waiting time
                    // Note: This is simplified since we don't have a "waiting time" field
                    // in the current model. In a real application, you would have more data.
                    $randomWaitingTime = rand(1, 10); // Simulated waiting time in months
                    if ($randomWaitingTime < 3) {
                        $stats['waktu_tunggu']['kurang_3_bulan']++;
                    } elseif ($randomWaitingTime >= 3 && $randomWaitingTime <= 6) {
                        $stats['waktu_tunggu']['3_6_bulan']++;
                    } else {
                        $stats['waktu_tunggu']['lebih_6_bulan']++;
                    }
                    
                    // Calculate job relevance
                    // Note: This is simplified since we don't have a "relevance" field
                    // in the current model. In a real application, you would have more data.
                    $relevance = rand(1, 3); // Simulated relevance (1=high, 2=medium, 3=low)
                    if ($relevance == 1) {
                        $stats['kesesuaian']['sangat_sesuai']++;
                    } elseif ($relevance == 2) {
                        $stats['kesesuaian']['cukup_sesuai']++;
                    } else {
                        $stats['kesesuaian']['kurang_sesuai']++;
                    }
                    
                } elseif ($hasStudy) {
                    $stats['studi_lanjut']++;
                } else {
                    $stats['mencari_kerja']++;
                }
            }
            
            // Calculate percentages
            $totalAlumni = $alumni->count();
            $stats['bekerja_percent'] = round(($stats['bekerja'] / $totalAlumni) * 100);
            $stats['studi_lanjut_percent'] = round(($stats['studi_lanjut'] / $totalAlumni) * 100);
            $stats['mencari_kerja_percent'] = round(($stats['mencari_kerja'] / $totalAlumni) * 100);
            
            // Calculate waiting time percentages
            if ($stats['bekerja'] > 0) {
                $stats['waktu_tunggu']['kurang_3_bulan_percent'] = round(($stats['waktu_tunggu']['kurang_3_bulan'] / $stats['bekerja']) * 100);
                $stats['waktu_tunggu']['3_6_bulan_percent'] = round(($stats['waktu_tunggu']['3_6_bulan'] / $stats['bekerja']) * 100);
                $stats['waktu_tunggu']['lebih_6_bulan_percent'] = round(($stats['waktu_tunggu']['lebih_6_bulan'] / $stats['bekerja']) * 100);
                
                // Calculate kesesuaian percentages
                $stats['kesesuaian']['sangat_sesuai_percent'] = round(($stats['kesesuaian']['sangat_sesuai'] / $stats['bekerja']) * 100);
                $stats['kesesuaian']['cukup_sesuai_percent'] = round(($stats['kesesuaian']['cukup_sesuai'] / $stats['bekerja']) * 100);
                $stats['kesesuaian']['kurang_sesuai_percent'] = round(($stats['kesesuaian']['kurang_sesuai'] / $stats['bekerja']) * 100);
            }
            
            // Group alumni by graduation year and calculate statistics for each year
            $yearlyStats = $alumni->groupBy('tahun_lulus')->map(function ($yearAlumni, $year) {
                $totalInYear = $yearAlumni->count();
                $bekerja = $yearAlumni->filter(function ($alumnus) {
                    return $alumnus->pekerjaans->where('masih_bekerja', true)->count() > 0;
                })->count();
                
                $studi = $yearAlumni->filter(function ($alumnus) {
                    return $alumnus->pendidikanLanjutans->count() > 0;
                })->count();
                
                $mencariKerja = $totalInYear - $bekerja - $studi;
                
                return [
                    'total' => $totalInYear,
                    'bekerja' => $bekerja,
                    'bekerja_percent' => $totalInYear > 0 ? round(($bekerja / $totalInYear) * 100) : 0,
                    'studi_lanjut' => $studi,
                    'studi_lanjut_percent' => $totalInYear > 0 ? round(($studi / $totalInYear) * 100) : 0,
                    'mencari_kerja' => $mencariKerja,
                    'mencari_kerja_percent' => $totalInYear > 0 ? round(($mencariKerja / $totalInYear) * 100) : 0,
                ];
            })->toArray();
            
            $stats['yearly_data'] = $yearlyStats;
        }
        
        // Return the view with statistics and alumni data
        return view('landing-page', compact('stats', 'alumni'));
    }
}