<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $prodis = Prodi::when($search, fn($query) => $query->where('prodi_name', 'like', "%{$search}%"))
                       ->paginate(10)
                       ->appends(['search' => $search]);
        return view('admin.prodi', compact('prodis')); // Sesuaikan dengan struktur folder
    }

    public function store(Request $request)
    {
        $request->validate([
            'prodi_name' => 'required|unique:prodis,prodi_name|max:255'
        ]);

        Prodi::create($request->only('prodi_name'));

        return redirect()->route('prodi.index')->with('success', 'Berhasil menambahkan data prodi');
    }

    public function update(Request $request, $id)
{
    $prodi = Prodi::find($id);
    if (!$prodi) {
        return redirect()->route('prodi.index')->with('error', 'Program studi tidak ditemukan');
    }

    $request->validate([
        'prodi_name' => 'required|unique:prodis,prodi_name,' . $id . '|max:255'
    ]);

    $prodi->update($request->only('prodi_name'));

    return redirect()->route('prodi.index')->with('success', 'Berhasil memperbarui program studi');
}

    public function destroy($id)
    {
        $prodi = Prodi::find($id);

        if (!$prodi) {
            return redirect()->route('prodi.index')->with('error', 'Program studi tidak ditemukan');
        }

        // Cek relasi dengan alumni (opsional)
        if ($prodi->alumnis()->exists()) {
            return redirect()->route('prodi.index')->with('error', 'Program studi tidak dapat dihapus karena memiliki alumni terkait');
        }

        $prodi->delete();

        return redirect()->route('prodi.index')->with('success', 'Berhasil menghapus program studi');
    }
}