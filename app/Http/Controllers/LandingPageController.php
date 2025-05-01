<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Status;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingPageController extends Controller
{
    /**
     * Display the landing page with alumni statistics.
     */
    public function index()
    {
        // Get all alumni with their relations
        $alumni = Alumni::with(['status', 'prodi'])->get();
        
        // Calculate statistics
        $stats = $this->calculateStats($alumni);
        
        return view('landing-page', compact('alumni', 'stats'));
    }
    
    /**
     * Return JSON response with statistics for AJAX requests.
     */
    public function getStatistics()
    {
        $alumni = Alumni::with(['status', 'prodi'])->get();
        $stats = $this->calculateStats($alumni);
        return response()->json($stats);
    }
    
    /**
     * Calculate overall statistics for alumni.
     */
    private function calculateStats($alumni)
    {
        $totalAlumni = $alumni->count();
        
        // Count working and studying alumni - using direct database queries for reliability
        $bekerjaCount = Status::where('type', 'bekerja')
                              ->where('is_active', true)
                              ->distinct('alumni_id')
                              ->count('alumni_id');
        
        $studiLanjutCount = Status::where('type', 'kuliah')
                                 ->where('is_active', true)
                                 ->distinct('alumni_id')
                                 ->count('alumni_id');
        
        // Calculate percentages based on alumni with status only 
        // (we won't show alumni without status in the visualization)
        $alumniWithStatus = $bekerjaCount + $studiLanjutCount;
        
        $bekerjaPercent = $alumniWithStatus > 0 
            ? round(($bekerjaCount / $alumniWithStatus) * 100, 1) 
            : 0;
            
        $studiLanjutPercent = $alumniWithStatus > 0 
            ? round(($studiLanjutCount / $alumniWithStatus) * 100, 1) 
            : 0;
        
        // Create yearly data for chart - using direct queries instead of relationships
        $yearlyData = [];
        
        // Get range of years
        $minYear = Alumni::min('tahun_lulus') ?: 2019;
        $maxYear = max(Alumni::max('tahun_lulus') ?: 2019, (int)date('Y'));
        
        // Calculate data for each year
        for ($year = $minYear; $year <= $maxYear; $year++) {
            // Get alumni IDs for this year
            $alumniIds = Alumni::where('tahun_lulus', $year)->pluck('id')->toArray();
            
            // Skip if no alumni in this year
            if (empty($alumniIds)) {
                $yearlyData[$year] = [
                    'total' => 0,
                    'bekerja' => 0,
                    'bekerja_percent' => 0,
                    'studi_lanjut' => 0,
                    'studi_lanjut_percent' => 0
                ];
                continue;
            }
            
            // Total alumni for this year
            $totalInYear = count($alumniIds);
            
            // Count working alumni in this year
            $bekerjaInYear = Status::whereIn('alumni_id', $alumniIds)
                                   ->where('type', 'bekerja')
                                   ->where('is_active', true)
                                   ->distinct('alumni_id')
                                   ->count('alumni_id');
            
            // Count studying alumni in this year
            $studiLanjutInYear = Status::whereIn('alumni_id', $alumniIds)
                                      ->where('type', 'kuliah')
                                      ->where('is_active', true)
                                      ->distinct('alumni_id')
                                      ->count('alumni_id');
            
            // Calculate the number of alumni with status
            $alumniWithStatusInYear = $bekerjaInYear + $studiLanjutInYear;
            
            // Calculate percentages based on alumni with status
            if ($alumniWithStatusInYear > 0) {
                $bekerjaPercentInYear = round(($bekerjaInYear / $alumniWithStatusInYear) * 100, 1);
                $studiPercentInYear = round(($studiLanjutInYear / $alumniWithStatusInYear) * 100, 1);
            } else {
                $bekerjaPercentInYear = 0;
                $studiPercentInYear = 0;
            }
            
            // Store data for this year
            $yearlyData[$year] = [
                'total' => $totalInYear,
                'bekerja' => $bekerjaInYear,
                'bekerja_percent' => $bekerjaPercentInYear,
                'studi_lanjut' => $studiLanjutInYear,
                'studi_lanjut_percent' => $studiPercentInYear
            ];
        }
        
        // Sort years
        ksort($yearlyData);
        
        // Add default data if no actual data is found
        if ($totalAlumni === 0 || empty($yearlyData)) {
            return $this->getDefaultData();
        }
        
        return [
            'total_alumni' => $totalAlumni,
            'total_with_status' => $alumniWithStatus,
            'bekerja_count' => $bekerjaCount,
            'bekerja_percent' => $bekerjaPercent,
            'studi_lanjut_count' => $studiLanjutCount,
            'studi_lanjut_percent' => $studiLanjutPercent,
            'yearly_data' => $yearlyData,
        ];
    }
    
    /**
     * Get default data for display when no real data exists.
     */
    private function getDefaultData()
    {
        $yearlyData = [];
        
        for ($year = 2019; $year <= (int)date('Y'); $year++) {
            $yearlyData[$year] = [
                'total' => 0,
                'bekerja' => 0,
                'bekerja_percent' => 0,
                'studi_lanjut' => 0,
                'studi_lanjut_percent' => 0
            ];
        }
        
        return [
            'total_alumni' => 0,
            'total_with_status' => 0,
            'bekerja_count' => 0,
            'bekerja_percent' => 0,
            'studi_lanjut_count' => 0,
            'studi_lanjut_percent' => 0,
            'yearly_data' => $yearlyData,
        ];
    }
}