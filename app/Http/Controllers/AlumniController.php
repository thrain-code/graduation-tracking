<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Prodi;
use App\Models\Status;
use App\Models\User;
use App\Exports\AlumniExport;
use App\Exports\ImportTemplateExport;
use App\Imports\AlumniImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $prodi_id = $request->query('prodi_id');
        $tahun_lulus = $request->query('tahun_lulus');
        $status = $request->query('status');

        $query = Alumni::with(['prodi', 'user', 'status'])
            ->when($search, function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('nim', 'like', "%{$search}%");
            })
            ->when($prodi_id, function ($q) use ($prodi_id) {
                $q->where('prodi_id', $prodi_id);
            })
            ->when($tahun_lulus, function ($q) use ($tahun_lulus) {
                $q->where('tahun_lulus', $tahun_lulus);
            })
            ->when($status, function ($q) use ($status) {
                if ($status === 'belum') {
                    $q->whereDoesntHave('status');
                } else {
                    $q->whereHas('status', function ($q) use ($status) {
                        $q->where('type', $status)->where('is_active', true);
                    });
                }
            });

        $alumnis = $query->paginate(10)->appends($request->query());
        $prodis = Prodi::all();
        $tahun_lulus_options = Alumni::select('tahun_lulus')
            ->distinct()
            ->orderBy('tahun_lulus', 'desc')
            ->pluck('tahun_lulus')
            ->toArray();

        return view('admin.alumni.index', compact('alumnis', 'prodis', 'tahun_lulus_options'));
    }


    public function create()
    {
        $prodis = Prodi::all();
        return view('admin.alumni.add', compact('prodis'));
    }


    public function store(Request $request)
    {
        // Debugging, hapus setelah yakin data masuk dengan benar
        // dd($request->all());

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nim' => 'required|string|unique:alumnis,nim|max:20', 
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'tahun_lulus' => 'required|integer|min:1900|max:' . date('Y'),
            'prodi_id' => 'required|exists:prodis,id',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8',
            'number_phone' => 'required|string|max:15',
            'alamat' => 'required|string|max:500',
            'status_type' => 'nullable|in:bekerja,kuliah,wirausaha,mengurus keluarga',
            // Validasi ketat: hanya relevan dengan status_type
            'bekerja_status_nama' => 'required_if:status_type,bekerja|string|max:100',
            'kuliah_status_nama' => 'required_if:status_type,kuliah|string|max:100',
            'wirausaha_status_nama' => 'required_if:status_type,wirausaha|string|max:100',
            'jabatan' => 'required_if:status_type,bekerja|string|max:50',
            'jenjang' => 'required_if:status_type,kuliah|in:S1,S2,S3',
            'bekerja_jenis_pekerjaan' => 'required_if:status_type,bekerja|string|max:255',
            'wirausaha_jenis_pekerjaan' => 'required_if:status_type,wirausaha|string|max:255',
            'gaji' => 'nullable|integer|min:0',
            'bekerja_tahun_mulai' => 'required_if:status_type,bekerja|integer|min:1900|max:' . date('Y'),
            'kuliah_tahun_mulai' => 'required_if:status_type,kuliah|integer|min:1900|max:' . date('Y'),
            'wirausaha_tahun_mulai' => 'required_if:status_type,wirausaha|integer|min:1900|max:' . date('Y'),
        ]);

        // Create User
        $user = User::create([
            'name' => $validated['nama_lengkap'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => false,
        ]);

        // Create Alumni
        $alumni = Alumni::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'nim' => $validated['nim'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tahun_lulus' => $validated['tahun_lulus'],
            'prodi_id' => $validated['prodi_id'],
            'user_id' => $user->id,
            'number_phone' => $validated['number_phone'],
            'alamat' => $validated['alamat'],
        ]);

        // Create Status if status_type is provided
        if ($validated['status_type'] && $validated['status_type'] !== 'mengurus keluarga') {
            $statusData = [
                'type' => $validated['status_type'],
                'alumni_id' => $alumni->id,
                'gaji' => $validated['gaji'] ?? null,
                'is_active' => true,
            ];

            if ($validated['status_type'] === 'bekerja') {
                $statusData['nama'] = $validated['bekerja_status_nama'];
                $statusData['jenis_pekerjaan'] = $validated['bekerja_jenis_pekerjaan'];
                $statusData['jabatan'] = $validated['jabatan'];
                $statusData['tahun_mulai'] = $validated['bekerja_tahun_mulai'];
            } elseif ($validated['status_type'] === 'kuliah') {
                $statusData['nama'] = $validated['kuliah_status_nama'];
                $statusData['jenjang'] = $validated['jenjang'];
                $statusData['tahun_mulai'] = $validated['kuliah_tahun_mulai'];
            } elseif ($validated['status_type'] === 'wirausaha') {
                $statusData['nama'] = $validated['wirausaha_status_nama'];
                $statusData['jenis_pekerjaan'] = $validated['wirausaha_jenis_pekerjaan'];
                $statusData['tahun_mulai'] = $validated['wirausaha_tahun_mulai'];
            }

            Status::create($statusData);
        }

        return redirect()->route('alumni.index')->with('success', 'Berhasil menambahkan alumni');
    }

    public function show($id)
    {
        $alumni = Alumni::with(['prodi', 'user', 'status'])->findOrFail($id);
        return view('admin.alumni.show', compact('alumni'));
    }

    public function edit($id)
    {
        $alumni = Alumni::with(['prodi', 'user', 'status'])->findOrFail($id);
        $prodis = Prodi::all();
        return view('admin.alumni.edit', compact('alumni', 'prodis'));
    }

    public function update(Request $request, $id)
    {
        $alumni = Alumni::findOrFail($id);
        // dd($request->all());

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:alumnis,nim,' . $id, 
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'tahun_lulus' => 'required|integer|min:1900|max:' . date('Y'),
            'prodi_id' => 'required|exists:prodis,id',
            'email' => 'required|email|max:255|unique:users,email,' . $alumni->user_id,
            'number_phone' => 'required|string|max:15',
            'alamat' => 'required|string|max:500',
            'status_type' => 'nullable|in:bekerja,kuliah,wirausaha,mengurus keluarga',
            // Validasi ketat: hanya relevan dengan status_type
            'bekerja_status_nama' => 'required_if:status_type,bekerja|string|max:100',
            'kuliah_status_nama' => 'required_if:status_type,kuliah|string|max:100',
            'wirausaha_status_nama' => 'required_if:status_type,wirausaha|string|max:100',
            'jabatan' => 'required_if:status_type,bekerja|string|max:50',
            'jenjang' => 'required_if:status_type,kuliah|in:S1,S2,S3',
            'bekerja_jenis_pekerjaan' => 'required_if:status_type,bekerja|string|max:255',
            'wirausaha_jenis_pekerjaan' => 'required_if:status_type,wirausaha|string|max:255',
            'gaji' => 'nullable|integer|min:0',
            'bekerja_tahun_mulai' => 'required_if:status_type,bekerja|integer|min:1900|max:' . date('Y'),
            'kuliah_tahun_mulai' => 'required_if:status_type,kuliah|integer|min:1900|max:' . date('Y'),
            'wirausaha_tahun_mulai' => 'required_if:status_type,wirausaha|integer|min:1900|max:' . date('Y'),
        ]);
        // dd($request->jenjang);

        // Update User
        $alumni->user->update([
            'name' => $validated['nama_lengkap'],
            'email' => $validated['email'],
        ]);

        // Update Alumni
        $alumni->update([
            'nama_lengkap' => $validated['nama_lengkap'],
            'nim' => $validated['nim'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tahun_lulus' => $validated['tahun_lulus'],
            'prodi_id' => $validated['prodi_id'],
            'number_phone' => $validated['number_phone'],
            'alamat' => $validated['alamat'],
        ]);

        // Update or Create Status
        if ($validated['status_type'] && $validated['status_type'] !== 'mengurus keluarga') {
            $statusData = [
                'type' => $validated['status_type'],
                'gaji' => $validated['gaji'] ?? null,
                'is_active' => true,
            ];

            if ($validated['status_type'] === 'bekerja') {
                $statusData['nama'] = $validated['bekerja_status_nama'];
                $statusData['jenis_pekerjaan'] = $validated['bekerja_jenis_pekerjaan'];
                $statusData['jabatan'] = $validated['jabatan'];
                $statusData['tahun_mulai'] = $validated['bekerja_tahun_mulai'];
            } elseif ($validated['status_type'] === 'kuliah') {
                $statusData['nama'] = $validated['kuliah_status_nama'];
                $statusData['jenjang'] = $validated['jenjang'];
                $statusData['tahun_mulai'] = $validated['kuliah_tahun_mulai'];
            } elseif ($validated['status_type'] === 'wirausaha') {
                $statusData['nama'] = $validated['wirausaha_status_nama'];
                $statusData['jenis_pekerjaan'] = $validated['wirausaha_jenis_pekerjaan'];
                $statusData['tahun_mulai'] = $validated['wirausaha_tahun_mulai'];
            }

            $alumni->status()->updateOrCreate(
                ['alumni_id' => $alumni->id],
                $statusData
            );
        } else {
            $alumni->status()->delete();
        }

        return redirect()->route('alumni.index')->with('success', 'Berhasil memperbarui alumni');
    }


    public function destroy($id)
    {
        $alumni = Alumni::findOrFail($id);

        // Delete associated user
        if ($alumni->user) {
            $alumni->user->delete();
        }

        // Status will be deleted automatically due to onDelete('cascade')
        $alumni->delete();

        return redirect()->route('alumni.index')->with('success', 'Berhasil menghapus alumni');
    }

    /**
     * Show the form for importing alumni
     */
    public function importForm()
    {
        return view('admin.alumni.import');
    }
    
    /**
     * Process the import file
     */
    public function importProcess(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);
        
        try {
            DB::beginTransaction();
            
            $import = new AlumniImport();
            Excel::import($import, $request->file('file'));
            
            $rowsImported = count($import->rows());
            $failures = $import->failures();
            
            DB::commit();
            
            if (count($failures) > 0) {
                return redirect()->route('alumni.import.form')->with([
                    'warning' => 'Import selesai dengan beberapa error. ' . count($failures) . ' data gagal diimpor.',
                    'failures' => $failures,
                    'success' => 'Berhasil mengimpor ' . ($rowsImported - count($failures)) . ' data alumni.'
                ]);
            }
            
            return redirect()->route('alumni.index')->with('success', 'Berhasil mengimpor ' . $rowsImported . ' data alumni.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import Error: ' . $e->getMessage());
            
            return redirect()->route('alumni.import.form')
                ->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }
    
    /**
     * Download template for import
     */
    public function downloadTemplate()
    {
        return Excel::download(new ImportTemplateExport, 'template_import_alumni.xlsx');
    }
    
    /**
     * Export alumni data to Excel
     */
    public function export(Request $request)
    {
        $fileName = 'data_alumni_' . date('Y-m-d_His') . '.xlsx';
        return Excel::download(new AlumniExport($request), $fileName);
    }
}