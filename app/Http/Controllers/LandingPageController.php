<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Status;
use App\Models\Prodi;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        // Fetch all alumni with their relationships
        $alumni = Alumni::with(['statuses', 'prodi', 'user'])->get();
        
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
                // Check if alumni has active job status
                $hasJob = $alumnus->isBekerja();
                
                // Check if alumni has active study status
                $hasStudy = $alumnus->isKuliah();
                
                // Count alumni by status
                if ($hasJob) {
                    $stats['bekerja']++;
                }
                
                if ($hasStudy) {
                    $stats['studi_lanjut']++;
                }
            }
            
            // Calculate percentages
            $totalAlumni = $alumni->count();
            $stats['bekerja_percent'] = $totalAlumni > 0 ? round(($stats['bekerja'] / $totalAlumni) * 100) : 0;
            $stats['studi_lanjut_percent'] = $totalAlumni > 0 ? round(($stats['studi_lanjut'] / $totalAlumni) * 100) : 0;
            
            // Group alumni by graduation year and calculate statistics for each year
            $yearlyStats = $alumni->groupBy('tahun_lulus')->map(function ($yearAlumni, $year) {
                $totalInYear = $yearAlumni->count();
                
                $bekerja = $yearAlumni->filter(function ($alumnus) {
                    return $alumnus->isBekerja();
                })->count();
                
                $studi = $yearAlumni->filter(function ($alumnus) {
                    return $alumnus->isKuliah();
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
    
    /**
     * Get detailed statistics for the landing page charts
     */
    public function getStatistics()
    {
        // Total alumni count
        $totalAlumni = Alumni::count();
        
        // Count alumni by status
        $bekerjaCount = Alumni::whereHas('statuses', function($query) {
            $query->where('type', 'bekerja')->where('is_active', true);
        })->count();
        
        $studiCount = Alumni::whereHas('statuses', function($query) {
            $query->where('type', 'kuliah')->where('is_active', true);
        })->count();
        
        // Calculate percentages
        $bekerjaPercent = $totalAlumni > 0 ? round(($bekerjaCount / $totalAlumni) * 100) : 0;
        $studiPercent = $totalAlumni > 0 ? round(($studiCount / $totalAlumni) * 100) : 0;
        
        // Get alumni count by year
        $alumniByYear = Alumni::select('tahun_lulus', DB::raw('count(*) as total'))
            ->groupBy('tahun_lulus')
            ->orderBy('tahun_lulus')
            ->get()
            ->pluck('total', 'tahun_lulus');
        
        // Get bekerja status by year
        $bekerjaByYear = [];
        $studiByYear = [];
        
        foreach ($alumniByYear->keys() as $year) {
            // Get alumni IDs for this year
            $alumniIds = Alumni::where('tahun_lulus', $year)->pluck('id');
            
            // Count bekerja
            $bekerjaInYear = Status::whereIn('alumni_id', $alumniIds)
                ->where('type', 'bekerja')
                ->where('is_active', true)
                ->count();
            
            // Count studi
            $studiInYear = Status::whereIn('alumni_id', $alumniIds)
                ->where('type', 'kuliah')
                ->where('is_active', true)
                ->count();
            
            // Calculate percentage
            $totalInYear = $alumniByYear[$year];
            $bekerjaByYear[$year] = $totalInYear > 0 ? round(($bekerjaInYear / $totalInYear) * 100) : 0;
            $studiByYear[$year] = $totalInYear > 0 ? round(($studiInYear / $totalInYear) * 100) : 0;
        }
        
        return response()->json([
            'totalAlumni' => $totalAlumni,
            'bekerjaPercent' => $bekerjaPercent,
            'studiPercent' => $studiPercent,
            'alumniByYear' => $alumniByYear,
            'bekerjaByYear' => $bekerjaByYear,
            'studiByYear' => $studiByYear,
        ]);
    }
}