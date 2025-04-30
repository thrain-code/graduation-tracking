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
        // Get all alumni
        $alumni = Alumni::with(['status', 'prodi'])->get();
        
        // Calculate statistics
        $stats = $this->calculateStats();
        
        return view('landing-page', compact('alumni', 'stats'));
    }
    
    /**
     * Return JSON response with statistics for AJAX requests.
     */
    public function getStatistics()
    {
        $stats = $this->calculateStats();
        return response()->json($stats);
    }
    
    /**
     * Calculate overall statistics for alumni.
     */
    private function calculateStats()
    {
        // Get all alumni count
        $totalAlumni = Alumni::count();
        
        // Count working alumni - direct database query
        $bekerjaCount = DB::table('status')
            ->where('type', 'bekerja')
            ->where('is_active', true)
            ->distinct('alumni_id')
            ->count('alumni_id');
        
        // Count studying alumni - direct database query
        $studiLanjutCount = DB::table('status')
            ->where('type', 'kuliah')
            ->where('is_active', true)
            ->distinct('alumni_id')
            ->count('alumni_id');
        
        // Calculate percentages
        $bekerjaPercent = $totalAlumni > 0 ? round(($bekerjaCount / $totalAlumni) * 100, 1) : 0;
        $studiLanjutPercent = $totalAlumni > 0 ? round(($studiLanjutCount / $totalAlumni) * 100, 1) : 0;
        
        // Create yearly data for chart
        $yearlyData = [];
        
        // Get the graduation years range
        $minYear = Alumni::min('tahun_lulus') ?: 2019;
        $maxYear = max(intval(date('Y')), Alumni::max('tahun_lulus') ?: intval(date('Y')));
        
        // Initialize data for all years in the range
        for ($year = $minYear; $year <= $maxYear; $year++) {
            // Get total alumni for this year
            $alumniInYear = Alumni::where('tahun_lulus', $year)->count();
            
            // Get working alumni for this year
            $bekerjaInYear = DB::table('alumnis')
                ->join('status', 'alumnis.id', '=', 'status.alumni_id')
                ->where('alumnis.tahun_lulus', $year)
                ->where('status.type', 'bekerja')
                ->where('status.is_active', true)
                ->distinct('alumnis.id')
                ->count('alumnis.id');
            
            // Get studying alumni for this year
            $studiInYear = DB::table('alumnis')
                ->join('status', 'alumnis.id', '=', 'status.alumni_id')
                ->where('alumnis.tahun_lulus', $year)
                ->where('status.type', 'kuliah')
                ->where('status.is_active', true)
                ->distinct('alumnis.id')
                ->count('alumnis.id');
            
            // Calculate percentages
            $bekerjaPercentInYear = $alumniInYear > 0 ? round(($bekerjaInYear / $alumniInYear) * 100, 1) : 0;
            $studiPercentInYear = $alumniInYear > 0 ? round(($studiInYear / $alumniInYear) * 100, 1) : 0;
            
            // Set data for this year
            $yearlyData[$year] = [
                'total' => $alumniInYear,
                'bekerja' => $bekerjaInYear,
                'bekerja_percent' => $bekerjaPercentInYear,
                'studi_lanjut' => $studiInYear,
                'studi_lanjut_percent' => $studiPercentInYear,
                'mencari_kerja' => $alumniInYear - $bekerjaInYear - $studiInYear,
                'mencari_kerja_percent' => 100 - $bekerjaPercentInYear - $studiPercentInYear
            ];
        }
        
        // Debugging - add seed data if no real data exists
        if ($totalAlumni === 0 || $bekerjaCount === 0 && $studiLanjutCount === 0) {
            return $this->getSampleData();
        }
        
        return [
            'total_alumni' => $totalAlumni,
            'bekerja_count' => $bekerjaCount,
            'bekerja_percent' => $bekerjaPercent,
            'studi_lanjut_count' => $studiLanjutCount,
            'studi_lanjut_percent' => $studiLanjutPercent,
            'yearly_data' => $yearlyData,
        ];
    }
    
    /**
     * Generate sample data for testing purposes.
     */
    private function getSampleData()
    {
        $yearlyData = [];
        
        // Create sample data for years 2019-2025
        for ($year = 2019; $year <= 2025; $year++) {
            // Generate random percentages that add up to at most 100
            $bekerjaPercent = rand(30, 60);
            $studiPercent = rand(10, 40);
            
            // Ensure total doesn't exceed 100
            if ($bekerjaPercent + $studiPercent > 100) {
                $total = $bekerjaPercent + $studiPercent;
                $bekerjaPercent = round(($bekerjaPercent / $total) * 100);
                $studiPercent = round(($studiPercent / $total) * 100);
            }
            
            $mencariKerjaPercent = 100 - $bekerjaPercent - $studiPercent;
            
            $yearlyData[$year] = [
                'total' => rand(20, 50),
                'bekerja' => rand(10, 30),
                'bekerja_percent' => $bekerjaPercent,
                'studi_lanjut' => rand(5, 15),
                'studi_lanjut_percent' => $studiPercent,
                'mencari_kerja' => rand(3, 10),
                'mencari_kerja_percent' => $mencariKerjaPercent
            ];
        }
        
        return [
            'total_alumni' => 150,
            'bekerja_count' => 90,
            'bekerja_percent' => 60,
            'studi_lanjut_count' => 45,
            'studi_lanjut_percent' => 30,
            'yearly_data' => $yearlyData,
        ];
    }
}