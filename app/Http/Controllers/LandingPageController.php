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
                } elseif ($hasStudy) {
                    $stats['studi_lanjut']++;
                }
            }
            
            // Calculate percentages
            $totalAlumni = $alumni->count();
            $stats['bekerja_percent'] = round(($stats['bekerja'] / $totalAlumni) * 100);
            $stats['studi_lanjut_percent'] = round(($stats['studi_lanjut'] / $totalAlumni) * 100);
            
            // Group alumni by graduation year and calculate statistics for each year
            $yearlyStats = $alumni->groupBy('tahun_lulus')->map(function ($yearAlumni, $year) {
                $totalInYear = $yearAlumni->count();
                $bekerja = $yearAlumni->filter(function ($alumnus) {
                    return $alumnus->pekerjaans->where('masih_bekerja', true)->count() > 0;
                })->count();
                
                $studi = $yearAlumni->filter(function ($alumnus) {
                    return $alumnus->pendidikanLanjutans->count() > 0;
                })->count();
                
                return [
                    'total' => $totalInYear,
                    'bekerja' => $bekerja,
                    'bekerja_percent' => $totalInYear > 0 ? round(($bekerja / $totalInYear) * 100) : 0,
                    'studi_lanjut' => $studi,
                    'studi_lanjut_percent' => $totalInYear > 0 ? round(($studi / $totalInYear) * 100) : 0,
                ];
            })->toArray();
            
            $stats['yearly_data'] = $yearlyStats;
        }
        
        // Return the view with statistics and alumni data
        return view('landing-page', compact('stats', 'alumni'));
    }
}