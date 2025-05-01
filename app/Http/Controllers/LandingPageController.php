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
        
        // Count alumni by status type - using direct database queries for reliability
        $bekerjaCount = Status::where('type', 'bekerja')
                              ->where('is_active', true)
                              ->distinct('alumni_id')
                              ->count('alumni_id');
        
        $studiLanjutCount = Status::where('type', 'kuliah')
                                 ->where('is_active', true)
                                 ->distinct('alumni_id')
                                 ->count('alumni_id');
        
        $wirausahaCount = Status::where('type', 'wirausaha')
                               ->where('is_active', true)
                               ->distinct('alumni_id')
                               ->count('alumni_id');
        
        $mengurusKeluargaCount = Status::where('type', 'mengurus keluarga')
                                      ->where('is_active', true)
                                      ->distinct('alumni_id')
                                      ->count('alumni_id');
        
        // Calculate percentages based on alumni with status only 
        // (we won't show alumni without status in the visualization)
        $alumniWithStatus = $bekerjaCount + $studiLanjutCount + $wirausahaCount + $mengurusKeluargaCount;
        
        $bekerjaPercent = $alumniWithStatus > 0 
            ? round(($bekerjaCount / $alumniWithStatus) * 100, 1) 
            : 0;
            
        $studiLanjutPercent = $alumniWithStatus > 0 
            ? round(($studiLanjutCount / $alumniWithStatus) * 100, 1) 
            : 0;
            
        $wirausahaPercent = $alumniWithStatus > 0 
            ? round(($wirausahaCount / $alumniWithStatus) * 100, 1) 
            : 0;
            
        $mengurusKeluargaPercent = $alumniWithStatus > 0 
            ? round(($mengurusKeluargaCount / $alumniWithStatus) * 100, 1) 
            : 0;
        
        // Get job type distribution for working alumni and entrepreneurs
        $jobTypeDistribution = $this->getJobTypeDistribution();
        
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
                    'studi_lanjut_percent' => 0,
                    'wirausaha' => 0,
                    'wirausaha_percent' => 0,
                    'mengurus_keluarga' => 0,
                    'mengurus_keluarga_percent' => 0
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
            
            // Count entrepreneurial alumni in this year
            $wirausahaInYear = Status::whereIn('alumni_id', $alumniIds)
                                    ->where('type', 'wirausaha')
                                    ->where('is_active', true)
                                    ->distinct('alumni_id')
                                    ->count('alumni_id');
            
            // Count family management alumni in this year
            $mengurusKeluargaInYear = Status::whereIn('alumni_id', $alumniIds)
                                           ->where('type', 'mengurus keluarga')
                                           ->where('is_active', true)
                                           ->distinct('alumni_id')
                                           ->count('alumni_id');
            
            // Calculate the number of alumni with status
            $alumniWithStatusInYear = $bekerjaInYear + $studiLanjutInYear + $wirausahaInYear + $mengurusKeluargaInYear;
            
            // Calculate percentages based on alumni with status
            if ($alumniWithStatusInYear > 0) {
                $bekerjaPercentInYear = round(($bekerjaInYear / $alumniWithStatusInYear) * 100, 1);
                $studiPercentInYear = round(($studiLanjutInYear / $alumniWithStatusInYear) * 100, 1);
                $wirausahaPercentInYear = round(($wirausahaInYear / $alumniWithStatusInYear) * 100, 1);
                $mengurusKeluargaPercentInYear = round(($mengurusKeluargaInYear / $alumniWithStatusInYear) * 100, 1);
            } else {
                $bekerjaPercentInYear = 0;
                $studiPercentInYear = 0;
                $wirausahaPercentInYear = 0;
                $mengurusKeluargaPercentInYear = 0;
            }
            
            // Store data for this year
            $yearlyData[$year] = [
                'total' => $totalInYear,
                'bekerja' => $bekerjaInYear,
                'bekerja_percent' => $bekerjaPercentInYear,
                'studi_lanjut' => $studiLanjutInYear,
                'studi_lanjut_percent' => $studiPercentInYear,
                'wirausaha' => $wirausahaInYear,
                'wirausaha_percent' => $wirausahaPercentInYear,
                'mengurus_keluarga' => $mengurusKeluargaInYear,
                'mengurus_keluarga_percent' => $mengurusKeluargaPercentInYear
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
            'wirausaha_count' => $wirausahaCount,
            'wirausaha_percent' => $wirausahaPercent,
            'mengurus_keluarga_count' => $mengurusKeluargaCount,
            'mengurus_keluarga_percent' => $mengurusKeluargaPercent,
            'yearly_data' => $yearlyData,
            'job_type_distribution' => $jobTypeDistribution,
        ];
    }
    
    /**
     * Get job type distribution for employed and entrepreneurial alumni.
     * Dynamically fetches all job types from the database.
     */
    private function getJobTypeDistribution()
    {
        // Get job type distribution for all alumni with jobs
        $jobTypes = Status::whereIn('type', ['bekerja', 'wirausaha'])
            ->where('is_active', true)
            ->whereNotNull('jenis_pekerjaan')
            ->select('jenis_pekerjaan', 'type', DB::raw('count(*) as count'))
            ->groupBy('jenis_pekerjaan', 'type')
            ->orderBy('count', 'desc')
            ->get();
        
        // Format the data
        $jobTypeDistribution = [];
        foreach ($jobTypes as $job) {
            $jobTypeDistribution[$job->jenis_pekerjaan] = [
                'count' => $job->count,
                'type' => $job->type
            ];
        }
        
        // If no data is found, provide sample data
        if (empty($jobTypeDistribution)) {
            $jobTypeDistribution = $this->getSampleJobTypes();
        }
        
        return $jobTypeDistribution;
    }
    
    /**
     * Get sample job types when no real data exists.
     */
    private function getSampleJobTypes()
    {
        return [
            'Guru PNS' => ['count' => 5, 'type' => 'bekerja'],
            'Guru Non PNS' => ['count' => 7, 'type' => 'bekerja'],
            'Tentor/Instruktur/Pengajar' => ['count' => 4, 'type' => 'bekerja'],
            'Pengelola Kursus' => ['count' => 2, 'type' => 'bekerja'],
            'Bisnis/Berjualan' => ['count' => 6, 'type' => 'wirausaha'],
            'Karyawan Swasta' => ['count' => 8, 'type' => 'bekerja'],
            'Tidak' => ['count' => 1, 'type' => 'bekerja'],
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
                'studi_lanjut_percent' => 0,
                'wirausaha' => 0,
                'wirausaha_percent' => 0,
                'mengurus_keluarga' => 0,
                'mengurus_keluarga_percent' => 0
            ];
        }
        
        return [
            'total_alumni' => 0,
            'total_with_status' => 0,
            'bekerja_count' => 0,
            'bekerja_percent' => 0,
            'studi_lanjut_count' => 0,
            'studi_lanjut_percent' => 0,
            'wirausaha_count' => 0,
            'wirausaha_percent' => 0,
            'mengurus_keluarga_count' => 0,
            'mengurus_keluarga_percent' => 0,
            'yearly_data' => $yearlyData,
            'job_type_distribution' => $this->getSampleJobTypes(),
        ];
    }
}