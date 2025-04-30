<?php
namespace App\Http\Controllers;
use App\Http\Requests\ProdiRequest;
use App\Services\ProdiService;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    protected $prodiService;
    
    public function __construct(ProdiService $prodiService){
        $this->prodiService = $prodiService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prodis = $this->prodiService->getAll();
        return view("prodi.index", ["prodis" => $prodis]);
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("prodi.create");
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(ProdiRequest $request)
    {
        $prodi = $request->validated();
        $newProdi = $this->prodiService->add($prodi);
        if($newProdi) {
            return redirect()->route('prodi.index')->with('success', 'Data prodi berhasil ditambahkan');
        }
        return redirect()->back()->with('error', 'Data gagal ditambahkan');
    }
    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $prodi = $this->prodiService->showById($id);
        if($prodi){
            return view('prodi.show', ['prodi' => $prodi]);
        }
        return redirect()->back()->with('error', "Data dengan id: $id tidak ada");
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $prodi = $this->prodiService->showById($id);
        return view('prodi.edit', ['prodi' => $prodi]);
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(ProdiRequest $request, string $id)
    {
        $prodi = $request->validated();
        if($this->prodiService->update($id, $prodi)){
            return redirect()->route('prodi.index')->with('success', 'Data berhasil di update');
        }
        return redirect()->back()->with('error', 'Data gagal di update');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if($this->prodiService->delete($id)){
            return redirect()->route('prodi.index')->with('success','Data berhasil dihapus');
        }
        return redirect()->back()->with('error', 'Data gagal dihapus, pastikan data sudah tidak berkaitan dengan alumni');
    }
}