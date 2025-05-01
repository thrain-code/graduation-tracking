<?php

namespace App\Imports;

use App\Models\Alumni;
use App\Models\Prodi;
use App\Models\User;
use App\Models\Status;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\AfterImport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AlumniImport implements 
    ToModel, 
    WithHeadingRow, 
    WithValidation, 
    SkipsOnFailure, 
    WithBatchInserts, 
    WithChunkReading, 
    WithCalculatedFormulas,
    WithEvents,
    WithMultipleSheets
{
    use SkipsFailures, Importable, RegistersEventListeners;
    
    /**
     * @var array
     */
    protected $rows = [];
    protected $importedCount = 0;
    protected $prodiCache = [];
    
    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }
    
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip empty rows or example rows
        if (empty($row['nama_lengkap']) || empty($row['nim'])) {
            return null;
        }
        
        // Track rows for reporting
        $this->rows[] = $row;
        
        // Validate and sanitize data
        $row = $this->sanitizeRowData($row);
        
        try {
            DB::beginTransaction();
            
            // Find or create prodi - using cache to reduce DB queries
            $prodiName = $row['prodi'];
            if (!isset($this->prodiCache[$prodiName])) {
                $prodi = Prodi::firstOrCreate(['prodi_name' => $prodiName]);
                $this->prodiCache[$prodiName] = $prodi->id;
            }
            $prodiId = $this->prodiCache[$prodiName];
            
            // Create user account for the alumni
            $email = $row['email'] ?? $this->generateEmail($row['nama_lengkap']);
            $user = User::create([
                'name' => $row['nama_lengkap'],
                'email' => $email,
                'password' => Hash::make($row['nim']), // Use NIM as default password
                'is_admin' => false,
            ]);
            
            // Create alumni record
            $alumni = Alumni::create([
                'nama_lengkap' => $row['nama_lengkap'],
                'nim' => $row['nim'],
                'jenis_kelamin' => strtolower($row['jenis_kelamin']),
                'tahun_lulus' => $row['tahun_lulus'],
                'prodi_id' => $prodiId,
                'user_id' => $user->id,
                'number_phone' => $row['number_phone'] ?? null,
                'alamat' => $row['alamat'] ?? null,
            ]);
            
            // Create statuses based on data provided
            $this->createStatuses($alumni, $row);
            
            DB::commit();
            $this->importedCount++;
            
            return $alumni;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import Error for NIM '.$row['nim'].': '.$e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Sanitize and validate row data
     */
    protected function sanitizeRowData(array $row): array
    {
        // Trim all values
        foreach ($row as $key => $value) {
            $row[$key] = is_string($value) ? trim($value) : $value;
        }
        
        // Normalize jenis_kelamin
        if (isset($row['jenis_kelamin'])) {
            $gender = strtolower($row['jenis_kelamin']);
            
            if (in_array($gender, ['l', 'laki', 'laki laki', 'laki-laki', 'lakilaki', 'male', 'm', 'pria'])) {
                $row['jenis_kelamin'] = 'Laki-laki';
            } elseif (in_array($gender, ['p', 'perempuan', 'wanita', 'female', 'f'])) {
                $row['jenis_kelamin'] = 'Perempuan';
            }
        }
        
        // Normalize "Ya/Tidak" values
        foreach (['status_kerja', 'status_kuliah', 'status_wirausaha', 'status_keluarga'] as $field) {
            if (isset($row[$field])) {
                $value = strtolower(trim($row[$field]));
                $row[$field] = in_array($value, ['y', 'ya', 'yes', '1', 'true']) ? 'Ya' : 'Tidak';
            }
        }
        
        // Ensure numbers are properly formatted
        if (isset($row['gaji'])) {
            // Remove non-numeric characters and convert to integer
            $row['gaji'] = $this->parseGaji($row['gaji']);
        }
        
        return $row;
    }
    
    /**
     * Create status records for the alumni
     */
    protected function createStatuses($alumni, $row): void
    {
        // Handle employment status
        if (isset($row['status_kerja']) && strtolower($row['status_kerja']) === 'ya') {
            Status::create([
                'nama' => $row['nama_perusahaan'] ?? 'Tidak Disebutkan',
                'type' => 'bekerja',
                'alumni_id' => $alumni->id,
                'jabatan' => $row['jabatan'] ?? null,
                'jenis_pekerjaan' => $row['jenis_pekerjaan'] ?? null,
                'gaji' => $row['gaji'] ?? null,
                'tahun_mulai' => $row['tahun_mulai_kerja'] ?? $row['tahun_lulus'],
                'is_active' => true,
            ]);
        }
        
        // Handle education status
        if (isset($row['status_kuliah']) && strtolower($row['status_kuliah']) === 'ya') {
            Status::create([
                'nama' => $row['nama_universitas'] ?? 'Tidak Disebutkan',
                'type' => 'kuliah',
                'alumni_id' => $alumni->id,
                'jenjang' => $row['jenjang'] ?? 'S2',
                'tahun_mulai' => $row['tahun_mulai_kuliah'] ?? $row['tahun_lulus'],
                'is_active' => true,
            ]);
        }
        
        // Handle entrepreneurship status
        if (isset($row['status_wirausaha']) && strtolower($row['status_wirausaha']) === 'ya') {
            Status::create([
                'nama' => $row['nama_usaha'] ?? 'Tidak Disebutkan',
                'type' => 'wirausaha',
                'alumni_id' => $alumni->id,
                'jenis_pekerjaan' => $row['jenis_usaha'] ?? 'Wiraswasta',
                'tahun_mulai' => $row['tahun_mulai_usaha'] ?? $row['tahun_lulus'],
                'is_active' => true,
            ]);
        }
        
        // Handle family management status
        if (isset($row['status_keluarga']) && strtolower($row['status_keluarga']) === 'ya') {
            Status::create([
                'nama' => 'Mengurus Keluarga',
                'type' => 'mengurus keluarga',
                'alumni_id' => $alumni->id,
                'tahun_mulai' => $row['tahun_lulus'],
                'is_active' => true,
            ]);
        }
    }
    
    /**
     * Generate a simple email based on name
     */
    private function generateEmail($name)
    {
        // Convert to lowercase and remove special characters
        $simplifiedName = strtolower(preg_replace('/[^\p{L}\p{N}\s]/u', '', $name));
        
        // Replace spaces with dots, and remove consecutive dots
        $emailName = str_replace(' ', '.', $simplifiedName);
        $emailName = preg_replace('/\.{2,}/', '.', $emailName);
        
        // Make sure it's unique by adding a random string if needed
        $baseEmail = $emailName . '@alumnus.ac.id';
        
        // Check if email exists
        if (User::where('email', $baseEmail)->exists()) {
            $randomStr = Str::random(4);
            return $emailName . '.' . $randomStr . '@alumnus.ac.id';
        }
        
        return $baseEmail;
    }
    
    /**
     * Parse salary input in various formats
     */
    private function parseGaji($gaji)
    {
        // Check if null or empty
        if ($gaji === null || $gaji === '') {
            return null;
        }
        
        // If it's already a number, return it
        if (is_numeric($gaji)) {
            return (int) $gaji;
        }
        
        // Remove non-numeric characters
        $gajiNumeric = preg_replace('/[^0-9]/', '', $gaji);
        return empty($gajiNumeric) ? null : (int) $gajiNumeric;
    }
    
    /**
     * Define validation rules with improved error messages
     */
    public function rules(): array
    {
        return [
            'nama_lengkap' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:alumnis,nim',
            'jenis_kelamin' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $gender = strtolower($value);
                    $validGenders = [
                        'l', 'laki', 'laki laki', 'laki-laki', 'lakilaki', 'male', 'm', 'pria',
                        'p', 'perempuan', 'wanita', 'female', 'f'
                    ];
                    
                    if (!in_array($gender, $validGenders)) {
                        $fail('Jenis kelamin harus Laki-laki atau Perempuan.');
                    }
                },
            ],
            'tahun_lulus' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'prodi' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'number_phone' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'status_kerja' => 'nullable|in:Ya,Tidak,ya,tidak,Yes,No,yes,no,Y,N,y,n,1,0',
            'status_kuliah' => 'nullable|in:Ya,Tidak,ya,tidak,Yes,No,yes,no,Y,N,y,n,1,0',
            'status_wirausaha' => 'nullable|in:Ya,Tidak,ya,tidak,Yes,No,yes,no,Y,N,y,n,1,0',
            'status_keluarga' => 'nullable|in:Ya,Tidak,ya,tidak,Yes,No,yes,no,Y,N,y,n,1,0',
            'tahun_mulai_kerja' => 'nullable|integer|min:1990|max:' . (date('Y')),
            'tahun_mulai_kuliah' => 'nullable|integer|min:1990|max:' . (date('Y')),
            'tahun_mulai_usaha' => 'nullable|integer|min:1990|max:' . (date('Y')),
            'jenjang' => 'nullable|string|in:S1,S2,S3,Profesi,s1,s2,s3,profesi',
            'gaji' => 'nullable',
        ];
    }
    
    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'nama_lengkap.max' => 'Nama lengkap maksimal 255 karakter',
            'nim.required' => 'NIM wajib diisi',
            'nim.unique' => 'NIM sudah terdaftar dalam sistem',
            'nim.max' => 'NIM maksimal 20 karakter',
            'email.unique' => 'Email sudah terdaftar dalam sistem',
            'email.email' => 'Format email tidak valid',
            'jenis_kelamin.required' => 'Jenis kelamin wajib diisi',
            'tahun_lulus.required' => 'Tahun lulus wajib diisi',
            'tahun_lulus.integer' => 'Tahun lulus harus berupa angka',
            'tahun_lulus.min' => 'Tahun lulus minimal 1990',
            'tahun_lulus.max' => 'Tahun lulus maksimal tahun sekarang',
            'prodi.required' => 'Program studi wajib diisi',
        ];
    }
    
    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 100;
    }
    
    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
    }
    
    /**
     * @return array
     */
    public function rows(): array
    {
        return $this->rows;
    }
    
    /**
     * @return int
     */
    public function getImportedCount(): int
    {
        return $this->importedCount;
    }
    
    /**
     * Handle events before import
     */
    public static function beforeImport(BeforeImport $event)
    {
        Log::info('Starting alumni import process');
    }
    
    /**
     * Handle events after import
     */
    public static function afterImport(AfterImport $event)
    {
        Log::info('Alumni import process completed');
    }
}