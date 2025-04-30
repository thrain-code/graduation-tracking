<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlumniRequest;
use App\Models\Prodi;
use App\Services\AlumniService;
use App\Services\ProdiService;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    protected $alumniService;
    protected $prodiService;

    public function __construct(AlumniService $alumniService, ProdiService $prodiService)
    {
        $this->alumniService = $alumniService;
        $this->prodiService = $prodiService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $alumnis = $this->alumniService->getAll();
        
        return view('alumni.index', [
            "alumnis" => $alumnis
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $prodis = $this->prodiService->getAll();
        return view('alumni.create', ['prodis' => $prodis]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AlumniRequest $request)
    {
        $validatedData = $request->validated();
        
        $userData = [
            'name' => $validatedData['nama_lengkap'],
            'email' => $request->email,
            'password' => $request->password,
        ];
            
        $result = $this->alumniService->createWithUser($validatedData, $userData);
        
        if ($result) {
            return redirect()->route('alumni.index')->with('success', 'Data alumni berhasil ditambahkan');
        }
        
        return redirect()->back()->with('error', 'Data alumni gagal ditambahkan')->withInput();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $alumni = $this->alumniService->getDetailAlumni($id);
        $pekerjaans = $this->alumniService->getPekerjaanByAlumniId($id);
        $pendidikans = $this->alumniService->getPendidikanByAlumniId($id);
        
        return view('admin.alumni.show', [
            'alumni' => $alumni,
            'pekerjaans' => $pekerjaans,
            'pendidikans' => $pendidikans
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $alumni = $this->alumniService->showById($id);
        $prodis = $this->prodiService->getAll();
        
        return view('admin.alumni.edit', [
            'alumni' => $alumni,
            'prodis' => $prodis
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AlumniRequest $request, string $id)
    {
        $validatedData = $request->validated();
        
        $result = $this->alumniService->update($id, $validatedData);
        
        if ($result) {
            return redirect()->route('alumni.index')->with('success', 'Data alumni berhasil diperbarui');
        }
        
        return redirect()->back()->with('error', 'Data alumni gagal diperbarui')->withInput();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->alumniService->delete($id);
        
        if ($result) {
            return redirect()->route('alumni.index')->with('success', 'Data alumni berhasil dihapus');
        }
        
        return redirect()->back()->with('error', 'Data alumni gagal dihapus. Pastikan data tidak terkait dengan data lain.');
    }
    
    /**
     * Dashboard alumni untuk user
     */
    public function dashboard()
    {
        // Dapatkan user yang sedang login
        $user = auth()->user();
        
        // Dapatkan data alumni dari user yang login
        $alumni = $user->alumni;
        
        if (!$alumni) {
            return redirect()->route('dashboard')->with('error', 'Anda belum terhubung dengan data alumni');
        }
        
        $pekerjaans = $this->alumniService->getPekerjaanByAlumniId($alumni->id);
        $pendidikans = $this->alumniService->getPendidikanByAlumniId($alumni->id);
        
        return view('alumni.dashboard', [
            'alumni' => $alumni,
            'pekerjaans' => $pekerjaans,
            'pendidikans' => $pendidikans
        ]);
    }
    
    /**
     * Tampilkan profil alumni untuk user
     */
    public function profile()
    {
        // Dapatkan user yang sedang login
        $user = auth()->user();
        
        // Dapatkan data alumni dari user yang login
        $alumni = $user->alumni;
        
        if (!$alumni) {
            return redirect()->route('dashboard')->with('error', 'Anda belum terhubung dengan data alumni');
        }
        
        $prodis = $this->prodiService->getAll();
        
        return view('alumni.profile', [
            'alumni' => $alumni,
            'prodis' => $prodis
        ]);
    }
    
    /**
     * Update profil alumni untuk user
     */
    public function updateProfile(AlumniRequest $request)
    {
        // Dapatkan user yang sedang login
        $user = auth()->user();
        
        // Dapatkan data alumni dari user yang login
        $alumni = $user->alumni;
        
        if (!$alumni) {
            return redirect()->route('dashboard')->with('error', 'Anda belum terhubung dengan data alumni');
        }
        
        $validatedData = $request->validated();
        
        $result = $this->alumniService->update($alumni->id, $validatedData);
        
        if ($result) {
            return redirect()->route('alumni.profile')->with('success', 'Profil berhasil diperbarui');
        }
        
        return redirect()->back()->with('error', 'Profil gagal diperbarui')->withInput();
    }
}