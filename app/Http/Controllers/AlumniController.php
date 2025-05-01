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
        dd($request->all());

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
     * Export alumni data to Excel
     */
    public function export(Request $request)
    {
        // Set a custom filename with the current date/time
        $currentDate = date('Y-m-d_His');

        // Generate the filename based on filters
        $fileName = 'data_alumni';

        // Add filter info to filename if any filters are applied
        if ($request->has('prodi_id') && $request->prodi_id) {
            $prodi = Prodi::find($request->prodi_id);
            if ($prodi) {
                $fileName .= '_' . Str::slug($prodi->prodi_name);
            }
        }

        if ($request->has('tahun_lulus') && $request->tahun_lulus) {
            $fileName .= '_' . $request->tahun_lulus;
        }

        if ($request->has('status') && $request->status) {
            $fileName .= '_' . $request->status;
        }

        // Add date and extension
        $fileName .= '_' . $currentDate . '.xlsx';

        // Log the export activity for auditing
        Log::info('Alumni data export initiated by: ' . auth()->user()->name . ' with filters: ' . json_encode($request->all()));

        // Return the Excel download with the custom filename
        return Excel::download(new AlumniExport($request), $fileName);
    }

    /**
     * Show the form for importing alumni
     */
    public function importForm()
    {
        $prodis = Prodi::all();
        return view('admin.alumni.import', compact('prodis'));
    }

    /**
     * Download template for import
     */
    public function downloadTemplate()
    {
        return Excel::download(new ImportTemplateExport, 'template_import_alumni_' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Process the import file with enhanced error handling and validation
     */
    public function importProcess(Request $request)
    {
        // Validate the uploaded file
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // Limit to 10MB
        ], [
            'file.required' => 'File import wajib diunggah',
            'file.file' => 'Data harus berupa file',
            'file.mimes' => 'Format file harus xlsx, xls, atau csv',
            'file.max' => 'Ukuran file maksimal 10MB',
        ]);

        if ($validator->fails()) {
            return redirect()->route('alumni.import.form')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Start a database transaction
            DB::beginTransaction();

            // Initialize the import class
            $import = new AlumniImport();

            // Store the original file name for logging
            $originalFileName = $request->file('file')->getClientOriginalName();

            // Import the file with chained configurations
            Excel::import($import, $request->file('file'));

            // Get import statistics
            $rowsImported = $import->getImportedCount();
            $failures = $import->failures();

            // Commit the transaction if we reach this point
            DB::commit();

            // Log the successful import
            Log::info('Alumni import completed by: ' . auth()->user()->name . ' - File: ' . $originalFileName . ' - Imported: ' . $rowsImported . ' - Failed: ' . count($failures));

            // Determine the redirect based on failures
            if (count($failures) > 0) {
                // Group failures by row and reason for better display
                $groupedFailures = [];
                foreach ($failures as $failure) {
                    $row = $failure->row();
                    if (!isset($groupedFailures[$row])) {
                        $groupedFailures[$row] = [
                            'row' => $row,
                            'errors' => [],
                            'values' => $failure->values()
                        ];
                    }
                    $groupedFailures[$row]['errors'][$failure->attribute()] = $failure->errors()[0];
                }

                return redirect()->route('alumni.import.form')->with([
                    'warning' => 'Import selesai dengan beberapa error. ' . count($failures) . ' data gagal diimpor.',
                    'failures' => $groupedFailures,
                    'success' => 'Berhasil mengimpor ' . $rowsImported . ' data alumni.',
                    'import_summary' => [
                        'total_rows' => count($import->rows()),
                        'imported' => $rowsImported,
                        'failed' => count($failures),
                        'date' => now()->format('d M Y H:i:s')
                    ]
                ]);
            }

            // No failures, redirect with success message
            return redirect()->route('alumni.index')->with([
                'success' => 'Berhasil mengimpor ' . $rowsImported . ' data alumni.',
                'import_summary' => [
                    'total_rows' => count($import->rows()),
                    'imported' => $rowsImported,
                    'failed' => 0,
                    'date' => now()->format('d M Y H:i:s')
                ]
            ]);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            // Handle validation exception
            DB::rollBack();
            Log::error('Import Validation Error: ' . $e->getMessage());

            return redirect()->route('alumni.import.form')
                ->with('error', 'Terjadi kesalahan validasi saat mengimpor data. Silakan periksa format file Anda.')
                ->with('validation_failures', $e->failures());

        } catch (\Exception $e) {
            // Handle any other exceptions
            DB::rollBack();
            Log::error('Import Error: ' . $e->getMessage());

            return redirect()->route('alumni.import.form')
                ->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }
}