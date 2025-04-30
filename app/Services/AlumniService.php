<?php
namespace App\Services;
use App\Models\Alumni;
use App\Models\Pekerjaan;
use App\Models\PendidikanLanjutan;
use App\Models\User;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AlumniService
{
    // Mengambil semua data alumni
    public function getAll()
    {
        return Alumni::with(['prodi', 'user'])->get();
    }

    // Menambahkan data alumni
    public function add(array $data): bool
    {
        try {
            $alumni = Alumni::create($data);
            return $alumni ? true : false;
        } catch (Exception $e) {
            Log::error('Error adding alumni: ' . $e->getMessage());
            return false;
        }
    }

    // Menampilkan data alumni berdasarkan ID
    public function showById(int $id)
    {
        return Alumni::findOrFail($id);
    }

    // Update data alumni
    public function update(int $id, array $data): bool
    {
        try {
            $alumni = Alumni::findOrFail($id);
            return $alumni->update($data);
        } catch (Exception $e) {
            Log::error('Error updating alumni: ' . $e->getMessage());
            return false;
        }
    }

    // Menghapus data alumni
    public function delete(int $id): bool
    {
        try {
            $alumni = Alumni::findOrFail($id);
            return $alumni->delete();
        } catch (Exception $e) {
            Log::error('Error deleting alumni: ' . $e->getMessage());
            return false;
        }
    }

    // Membuat Alumni dengan usernya
    public function createWithUser(array $dataAlumni, array $dataUser)
    {
        try {
            $alumni = Alumni::create($dataAlumni);
            
            $dataUser['password'] = Hash::make($dataUser['password']);
            $dataUser['is_admin'] = false;
            
            $user = User::create($dataUser);
            
            // Update alumni with user_id
            $alumni->user_id = $user->id;
            $alumni->save();
            
            return true;
        } catch(Exception $e){
            Log::error('Error creating alumni with user: ' . $e->getMessage());
            return false;
        }
    }

    // Menampilkan riwayat pekerjaan berdasarkan ID alumni
    public function getPekerjaanByAlumniId(int $alumniId)
    {
        return Pekerjaan::where('alumni_id', $alumniId)->get();
    }

    // Menampilkan riwayat pendidikan lanjutan berdasarkan ID alumni
    public function getPendidikanByAlumniId(int $alumniId)
    {
        return PendidikanLanjutan::where('alumni_id', $alumniId)->get();
    }

    // Menampilkan user yang terhubung dengan alumni
    public function getUserByAlumniId(int $alumniId)
    {
        $alumni = Alumni::findOrFail($alumniId);
        return $alumni->user;
    }

    // Menampilkan data alumni lengkap beserta prodi, pekerjaan, dan pendidikan lanjutan
    public function getDetailAlumni(int $alumniId)
    {
        return Alumni::with(['prodi', 'pekerjaans', 'pendidikanLanjutans', 'user'])
                    ->findOrFail($alumniId);
    }

    // Filter alumni berdasarkan tahun lulus
    public function getByTahunLulus(int $tahun)
    {
        return Alumni::where('tahun_lulus', $tahun)->get();
    }

    // Filter alumni berdasarkan prodi
    public function getByProdi(int $prodiId)
    {
        return Alumni::where('prodi_id', $prodiId)->get();
    }

    // Hitung total alumni
    public function getTotalAlumni()
    {
        return Alumni::count();
    }

    // Mendapatkan alumni yang lulus tahun ini
    public function getNewAlumniByYear($year = null)
    {
        $year = $year ?? Carbon::now()->year;
        return Alumni::where('tahun_lulus', $year)->get();
    }

    // Hitung jumlah alumni baru tahun ini
    public function getTotalNewAlumniByYear($year = null)
    {
        $year = $year ?? Carbon::now()->year;
        return Alumni::where('tahun_lulus', $year)->count();
    }

    // Hitung persentase alumni yang bekerja
    public function getWorkingAlumniPercentage($year = null)
    {
        $totalAlumni = $this->getTotalAlumni();
        
        if ($totalAlumni === 0) {
            return 0;
        }

        // Menghitung alumni yang saat ini bekerja (memiliki pekerjaan dengan masih_bekerja = true)
        $workingCount = Alumni::whereHas('pekerjaans', function($query) {
            $query->where('masih_bekerja', true);
        })->count();
        
        return round(($workingCount / $totalAlumni) * 100, 2);
    }

    // Hitung persentase alumni yang melanjutkan studi
    public function getStudyingAlumniPercentage($year = null)
    {
        $totalAlumni = $this->getTotalAlumni();
        
        if ($totalAlumni === 0) {
            return 0;
        }

        $currentYear = Carbon::now()->year;
        
        // Menghitung alumni yang saat ini masih studi (tahun_selesai > tahun sekarang atau null)
        $studyingCount = Alumni::whereHas('pendidikanLanjutans', function($query) use ($currentYear) {
            $query->where(function($q) use ($currentYear) {
                $q->whereNull('tahun_selesai')
                  ->orWhere('tahun_selesai', '>=', $currentYear);
            });
        })->count();
        
        return round(($studyingCount / $totalAlumni) * 100, 2);
    }

    // Hitung persentase alumni yang bekerja tahun lalu
    public function getLastYearWorkingAlumniPercentage()
    {
        $lastYear = Carbon::now()->subYear()->year;
        $totalAlumniLastYear = Alumni::where('tahun_lulus', '<=', $lastYear)->count();
        
        if ($totalAlumniLastYear === 0) {
            return 0;
        }

        // Gunakan data pekerjaan yang dimulai sebelum atau pada tahun lalu
        $workingCountLastYear = Alumni::whereHas('pekerjaans', function($query) use ($lastYear) {
            $query->where('tahun_mulai', '<=', $lastYear)
                  ->where(function($q) use ($lastYear) {
                      $q->where('masih_bekerja', true)
                        ->orWhere('tahun_selesai', '>=', $lastYear);
                  });
        })->count();
        
        return round(($workingCountLastYear / $totalAlumniLastYear) * 100, 2);
    }

    // Hitung persentase alumni yang melanjutkan studi tahun lalu
    public function getLastYearStudyingAlumniPercentage()
    {
        $lastYear = Carbon::now()->subYear()->year;
        $totalAlumniLastYear = Alumni::where('tahun_lulus', '<=', $lastYear)->count();
        
        if ($totalAlumniLastYear === 0) {
            return 0;
        }

        // Gunakan data pendidikan yang sudah dimulai dan masih berlangsung tahun lalu
        $studyingCountLastYear = Alumni::whereHas('pendidikanLanjutans', function($query) use ($lastYear) {
            $query->where('tahun_mulai', '<=', $lastYear)
                  ->where(function($q) use ($lastYear) {
                      $q->whereNull('tahun_selesai')
                        ->orWhere('tahun_selesai', '>=', $lastYear);
                  });
        })->count();
        
        return round(($studyingCountLastYear / $totalAlumniLastYear) * 100, 2);
    }

    // Mendapatkan data statistik untuk dashboard
    public function getDashboardStatistics()
    {
        $currentYear = Carbon::now()->year;
        $totalAlumni = $this->getTotalAlumni();
        $newAlumniCount = $this->getTotalNewAlumniByYear($currentYear);
        
        // Persentase alumni yang bekerja
        $bekerjaPercent = $this->getWorkingAlumniPercentage();
        $bekerjaLastYear = $this->getLastYearWorkingAlumniPercentage();
        $bekerjaCompare = round($bekerjaPercent - $bekerjaLastYear, 2);
        
        // Persentase alumni yang studi lanjut
        $studiPercent = $this->getStudyingAlumniPercentage();
        $studiLastYear = $this->getLastYearStudyingAlumniPercentage();
        $studiCompare = round($studiPercent - $studiLastYear, 2);
        
        return [
            'totalAlumni' => $totalAlumni,
            'newAlumniCount' => $newAlumniCount,
            'bekerjaPercent' => $bekerjaPercent,
            'bekerjaCompare' => $bekerjaCompare,
            'studiPercent' => $studiPercent,
            'studiCompare' => $studiCompare,
        ];
    }
}