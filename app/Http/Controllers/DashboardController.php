<?php
namespace App\Http\Controllers;

use App\Services\AlumniService;
use App\Services\PekerjaanService;
use App\Services\PendidikanLanjutanService;
use App\Services\ProdiService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $alumniService;
    protected $pekerjaanService;
    protected $pendidikanService;
    protected $prodiService;

    public function __construct(
        AlumniService $alumniService,
        PekerjaanService $pekerjaanService = null,
        PendidikanLanjutanService $pendidikanService = null,
        ProdiService $prodiService = null
    ) {
        $this->alumniService = $alumniService;
        $this->pekerjaanService = $pekerjaanService;
        $this->pendidikanService = $pendidikanService;
        $this->prodiService = $prodiService;
    }

    public function index()
    {
        // Mendapatkan semua statistik dari AlumniService
        $statistics = $this->alumniService->getDashboardStatistics();
        
        // Jika butuh data tambahan
        $currentYear = Carbon::now()->year;
        $lastYear = $currentYear - 1;
        
        return view("admin.dashboard", [
            "totalAlumni" => $statistics['totalAlumni'],
            "newAlumniCount" => $statistics['newAlumniCount'],
            "bekerjaPercent" => $statistics['bekerjaPercent'],
            "bekerjaCompare" => $statistics['bekerjaCompare'],
            "studiPercent" => $statistics['studiPercent'],
            "studiCompare" => $statistics['studiCompare'],
            "currentYear" => $currentYear,
            "lastYear" => $lastYear
        ]);
    }
    
    /**
     * Menampilkan grafik untuk dashboard
     */
    public function getChartData()
    {
        // Data untuk grafik bisa diambil disini
        $currentYear = Carbon::now()->year;
        
        // Contoh: data alumni per tahun lulus untuk 5 tahun terakhir
        $alumniByYear = [];
        for ($i = 4; $i >= 0; $i--) {
            $year = $currentYear - $i;
            $count = $this->alumniService->getByTahunLulus($year)->count();
            $alumniByYear[$year] = $count;
        }
        
        return response()->json([
            'alumniByYear' => $alumniByYear,
            // Data lain untuk grafik bisa ditambahkan disini
        ]);
    }
}