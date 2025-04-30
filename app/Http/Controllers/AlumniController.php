<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Prodi;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $prodi_id = $request->query('prodi_id');
        $tahun_lulus = $request->query('tahun_lulus');
        $status = $request->query('status');

        $query = Alumni::with(['prodi', 'user', 'status'])
            ->when($search, fn($q) => $q->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('nim', 'like', "%{$search}%"))
            ->when($prodi_id, fn($q) => $q->where('prodi_id', $prodi_id))
            ->when($tahun_lulus, fn($q) => $q->where('tahun_lulus', $tahun_lulus))
            ->when($status, function ($q) use ($status) {
                if ($status === 'bekerja') {
                    $q->whereHas('status', fn($q) => $q->bekerja()->active());
                } elseif ($status === 'studi') {
                    $q->whereHas('status', fn($q) => $q->kuliah()->active());
                } elseif ($status === 'belum') {
                    $q->whereDoesntHave('status');
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
            'status_type' => 'nullable|in:bekerja,kuliah',
            'status_nama' => 'required_if:status_type,bekerja,kuliah|string|max:255',
            'jabatan' => 'required_if:status_type,bekerja|string|max:255',
            'jenjang' => 'required_if:status_type,kuliah|string|max:255',
            'gaji' => 'nullable|integer|min:0',
            'tahun_mulai' => 'required_if:status_type,bekerja,kuliah|integer|min:1900|max:' . date('Y'),
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

        // Create Status if provided
        if ($validated['status_type']) {
            Status::create([
                'nama' => $validated['status_nama'],
                'type' => $validated['status_type'],
                'alumni_id' => $alumni->id,
                'jabatan' => $validated['jabatan'] ?? null,
                'jenjang' => $validated['jenjang'] ?? null,
                'gaji' => $validated['gaji'] ?? null,
                'tahun_mulai' => $validated['tahun_mulai'],
                'is_active' => true,
            ]);
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

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:alumnis,nim,' . $id,
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'tahun_lulus' => 'required|integer|min:1900|max:' . date('Y'),
            'prodi_id' => 'required|exists:prodis,id',
            'email' => 'required|email|max:255|unique:users,email,' . $alumni->user_id,
            'number_phone' => 'required|string|max:15',
            'alamat' => 'required|string|max:500',
            'status_type' => 'nullable|in:bekerja,kuliah',
            'status_nama' => 'required_if:status_type,bekerja,kuliah|string|max:255',
            'jabatan' => 'required_if:status_type,bekerja|string|max:255',
            'jenjang' => 'required_if:status_type,kuliah|string|max:255',
            'gaji' => 'nullable|integer|min:0',
            'tahun_mulai' => 'required_if:status_type,bekerja,kuliah|integer|min:1900|max:' . date('Y'),
        ]);

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
        if ($validated['status_type']) {
            $alumni->status()->updateOrCreate(
                ['alumni_id' => $alumni->id],
                [
                    'nama' => $validated['status_nama'],
                    'type' => $validated['status_type'],
                    'jabatan' => $validated['jabatan'] ?? null,
                    'jenjang' => $validated['jenjang'] ?? null,
                    'gaji' => $validated['gaji'] ?? null,
                    'tahun_mulai' => $validated['tahun_mulai'],
                    'is_active' => true,
                ]
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

    public function import(Request $request)
    {
        return redirect()->route('alumni.index')->with('success', 'Fitur import belum diimplementasikan');
    }

    public function export(Request $request)
    {
        return redirect()->route('alumni.index')->with('success', 'Fitur export belum diimplementasikan');
    }
}