<?php

namespace App\Imports;

use App\Models\Alumni;
use App\Models\Prodi;
use App\Models\User;
use App\Models\Status;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
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
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AlumniImport implements
    ToModel,
    WithStartRow,
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
     * @return int
     */
    public function startRow(): int
    {
        // Mulai import dari baris ke-7 (setelah header dan instruksi)
        return 7;
    }

    /**
     * @return int
     */
    public function headingRow(): int
    {
        // Ambil header dari baris ke-3
        return 3;
    }

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
        Log::info("Processing row: " . json_encode($row));

        // Normalisasi struktur baris untuk mendukung header bernomor dan header standar
        $normalizedRow = $this->normalizeRow($row);
        Log::info("Normalized row: " . json_encode($normalizedRow));

        // Skip jika baris berisi instruksi atau header
        if ($this->isInstructionOrHeader($normalizedRow)) {
            Log::info("Skipping instruction or header row");
            return null;
        }

        // Skip jika tidak ada nama atau nim
        if (empty(trim($normalizedRow['nama_lengkap'] ?? '')) || empty(trim($normalizedRow['nim'] ?? ''))) {
            Log::info("Skipping row - nama_lengkap or nim empty after normalization");
            return null;
        }

        // Track rows for reporting
        $this->rows[] = $normalizedRow;

        // Validate and sanitize data
        $normalizedRow = $this->sanitizeRowData($normalizedRow);

        try {
            DB::beginTransaction();

            // Find or create prodi - using cache to reduce DB queries
            $prodiName = $normalizedRow['prodi'] ?? 'Tidak Disebutkan';
            if (!isset($this->prodiCache[$prodiName])) {
                $prodi = Prodi::firstOrCreate(['prodi_name' => $prodiName]);
                $this->prodiCache[$prodiName] = $prodi->id;
            }
            $prodiId = $this->prodiCache[$prodiName];

            // Create user account for the alumni
            $email = $normalizedRow['email'] ?? $this->generateEmail($normalizedRow['nama_lengkap']);
            $user = User::create([
                'name' => $normalizedRow['nama_lengkap'],
                'email' => $email,
                'password' => Hash::make($normalizedRow['nim']), // Use NIM as default password
                'is_admin' => false,
            ]);

            // Create alumni record - JANGAN sertakan ID (biarkan auto-increment)
            $alumniData = [
                'nama_lengkap' => $normalizedRow['nama_lengkap'],
                'nim' => $normalizedRow['nim'],
                'jenis_kelamin' => strtolower($normalizedRow['jenis_kelamin'] ?? 'laki-laki'),
                'tahun_lulus' => $normalizedRow['tahun_lulus'] ?? date('Y'),
                'prodi_id' => $prodiId,
                'user_id' => $user->id,
                'number_phone' => $normalizedRow['number_phone'] ?? null,
                'alamat' => $normalizedRow['alamat'] ?? null,
            ];

            // Pastikan tidak ada ID yang disertakan
            if (isset($alumniData['id'])) {
                unset($alumniData['id']);
            }

            $alumni = Alumni::create($alumniData);

            // Create statuses based on data provided
            $this->createStatuses($alumni, $normalizedRow);

            DB::commit();
            $this->importedCount++;
            Log::info("Successfully imported alumni: {$normalizedRow['nama_lengkap']} with NIM: {$normalizedRow['nim']}");

            return $alumni;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import Error for NIM '.$normalizedRow['nim'].': '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Check if row contains instructions or header text
     */
    protected function isInstructionOrHeader($row): bool
    {
        // Kata kunci yang menandakan ini adalah baris instruksi atau header
        $instructionKeywords = [
            'nama_lengkap *',
            'nim *',
            'silakan isi',
            'contoh',
            'kolom dengan tanda',
            'wajib diisi',
            'jenis_kelamin *',
            'tahun_lulus *',
            'prodi *'
        ];

        // Periksa apakah nama_lengkap (atau kolom lain) berisi kata kunci instruksi
        foreach ($instructionKeywords as $keyword) {
            if (isset($row['nama_lengkap']) &&
                is_string($row['nama_lengkap']) &&
                stripos($row['nama_lengkap'], $keyword) !== false) {
                return true;
            }

            if (isset($row['nim']) &&
                is_string($row['nim']) &&
                stripos($row['nim'], $keyword) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Normalize row to support both numbered headers and standard headers
     */
    protected function normalizeRow(array $row): array
    {
        $normalizedRow = [];

        // Periksa apakah ini adalah format dengan header judul panjang di kolom pertama
        // Misal: "template_import_data_alumni_institut_prima_bangsa" : "Nama Alumni"
        if (isset($row['template_import_data_alumni_institut_prima_bangsa'])) {
            // Kita gunakan nilai dari kolom pertama sebagai nama_lengkap
            $normalizedRow['nama_lengkap'] = $row['template_import_data_alumni_institut_prima_bangsa'];

            // Petakan dari angka ke nama kolom
            $columnMapping = [
                '1' => 'nim',
                '2' => 'jenis_kelamin',
                '3' => 'tahun_lulus',
                '4' => 'prodi',
                '5' => 'number_phone',
                '6' => 'alamat',
                '7' => 'email',
                '8' => 'status_kerja',
                '9' => 'nama_perusahaan',
                '10' => 'jabatan',
                '11' => 'gaji',
                '12' => 'tahun_mulai_kerja',
                '13' => 'status_kuliah',
                '14' => 'nama_universitas',
                '15' => 'jenjang',
                '16' => 'tahun_mulai_kuliah',
                '17' => 'status_wirausaha',
                '18' => 'nama_usaha',
                '19' => 'jenis_usaha',
                '20' => 'tahun_mulai_usaha',
                '21' => 'status_keluarga'
            ];

            // Map values from numbered keys to named keys
            foreach ($columnMapping as $number => $fieldName) {
                if (isset($row[$number])) {
                    $normalizedRow[$fieldName] = $row[$number];
                }
            }
        }
        // Dukungan untuk header bernomor tanpa teks panjang di kolom 1
        else if (isset($row['1']) && !isset($row['nim'])) {
            $columnMapping = [
                '0' => 'nama_lengkap',
                '1' => 'nim',
                '2' => 'jenis_kelamin',
                '3' => 'tahun_lulus',
                '4' => 'prodi',
                '5' => 'number_phone',
                '6' => 'alamat',
                '7' => 'email',
                '8' => 'status_kerja',
                '9' => 'nama_perusahaan',
                '10' => 'jabatan',
                '11' => 'gaji',
                '12' => 'tahun_mulai_kerja',
                '13' => 'status_kuliah',
                '14' => 'nama_universitas',
                '15' => 'jenjang',
                '16' => 'tahun_mulai_kuliah',
                '17' => 'status_wirausaha',
                '18' => 'nama_usaha',
                '19' => 'jenis_usaha',
                '20' => 'tahun_mulai_usaha',
                '21' => 'status_keluarga'
            ];

            foreach ($columnMapping as $number => $fieldName) {
                if (isset($row[$number])) {
                    $normalizedRow[$fieldName] = $row[$number];
                }
            }
        }
        // Jika menggunakan header standar (nama_lengkap, nim, dll)
        else {
            // Jika sudah menggunakan header yang benar, gunakan apa adanya
            $normalizedRow = $row;
        }

        // SANGAT PENTING: Hapus kolom ID jika ada, untuk mencegah konflik
        if (isset($normalizedRow['id'])) {
            unset($normalizedRow['id']);
        }

        return $normalizedRow;
    }

    /**
     * Sanitize and validate row data
     */
    protected function sanitizeRowData(array $row): array
    {
        // PENTING: Hapus kolom ID jika ada, untuk mencegah konflik
        if (isset($row['id'])) {
            unset($row['id']);
        }

        // Trim all values
        foreach ($row as $key => $value) {
            if ($value !== null && $value !== '') {
                $row[$key] = is_string($value) ? trim($value) : $value;
            } else {
                // Set null untuk nilai kosong
                $row[$key] = null;
            }
        }

        // Normalize jenis_kelamin
        if (isset($row['jenis_kelamin']) && $row['jenis_kelamin'] !== null) {
            $gender = strtolower($row['jenis_kelamin']);

            if (in_array($gender, ['l', 'laki', 'laki laki', 'laki-laki', 'lakilaki', 'male', 'm', 'pria'])) {
                $row['jenis_kelamin'] = 'laki-laki';
            } elseif (in_array($gender, ['p', 'perempuan', 'wanita', 'female', 'f'])) {
                $row['jenis_kelamin'] = 'perempuan';
            } else {
                // Default jika tidak valid
                $row['jenis_kelamin'] = 'laki-laki';
            }
        } else {
            // Default jika kosong
            $row['jenis_kelamin'] = 'laki-laki';
        }

        // Pastikan tahun_lulus ada nilai dan bertipe integer
        if (!isset($row['tahun_lulus']) || empty($row['tahun_lulus']) || !is_numeric($row['tahun_lulus'])) {
            $row['tahun_lulus'] = date('Y');
        } else {
            // Pastikan tahun_lulus berupa integer
            $row['tahun_lulus'] = (int) $row['tahun_lulus'];
        }

        // Normalize "Ya/Tidak" values
        foreach (['status_kerja', 'status_kuliah', 'status_wirausaha', 'status_keluarga'] as $field) {
            if (isset($row[$field]) && $row[$field] !== null && $row[$field] !== '') {
                $value = strtolower(trim($row[$field]));
                $row[$field] = in_array($value, ['y', 'ya', 'yes', '1', 'true']) ? 'Ya' : 'Tidak';
            } else {
                $row[$field] = 'Tidak';
            }
        }

        // Ensure numbers are properly formatted
        if (isset($row['gaji']) && $row['gaji'] !== null && $row['gaji'] !== '') {
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
            // Minimal validation - just check uniqueness
            'nim' => 'unique:alumnis,nim',
            'email' => 'nullable|email|unique:users,email',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'nim.unique' => 'NIM sudah terdaftar dalam sistem',
            'email.unique' => 'Email sudah terdaftar dalam sistem',
            'email.email' => 'Format email tidak valid',
        ];
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 1; // Kurangi ukuran batch untuk hindari konflik ID
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
     * Register events untuk menangani pengolahan sheet
     */
    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function(BeforeImport $event) {
                Log::info('Starting alumni import process');
            },

            AfterImport::class => function(AfterImport $event) {
                Log::info('Alumni import process completed');
            },

            BeforeSheet::class => function(BeforeSheet $event) {
                $worksheet = $event->getSheet();
                $title = $worksheet->getTitle();
                Log::info("Processing sheet: $title");
            },

            AfterSheet::class => function(AfterSheet $event) {
                Log::info('Finished processing sheet: ' . $event->getSheet()->getTitle());
            },
        ];
    }
}
