<!-- resources/views/admin/alumni/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Data Alumni')

@section('page-title', 'Data Alumni')

@section('content')
<!-- Alumni Card -->
<div class="card rounded-xl p-6 shadow-lg mb-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h3 class="text-xl font-semibold text-white">Daftar Alumni</h3>
        
        <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
            <button id="openAddAlumniModal" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                <i class="fas fa-plus mr-2"></i> Tambah Alumni
            </button>
            
            <div class="flex gap-2">
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-import mr-2"></i> Import
                </button>
                
                <button class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-export mr-2"></i> Export
                </button>
            </div>
        </div>
    </div>
    
    <!-- Filter and Search -->
    <div class="flex flex-col md:flex-row gap-4 mb-6">
        <div class="flex-1">
            <div class="relative">
                <input type="text" placeholder="Cari alumni..." class="w-full bg-slate-700 border border-slate-600 rounded-lg pl-10 pr-4 py-2 text-white focus:outline-none focus:border-primary-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-2">
            <select class="bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                <option value="">Semua Prodi</option>
                <option value="1">PTIK</option>
                <option value="2">Teknik Sipil</option>
                <option value="3">Teknik Mesin</option>
            </select>
            
            <select class="bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                <option value="">Semua Tahun Lulus</option>
                <option value="2025">2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
            </select>
            
            <select class="bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                <option value="">Semua Status</option>
                <option value="bekerja">Bekerja</option>
                <option value="studi">Studi Lanjut</option>
                <option value="bekerja_studi">Bekerja & Studi</option>
                <option value="belum">Belum Terdata</option>
            </select>
        </div>
    </div>
    
    <!-- Alumni Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-800 text-gray-300 text-sm">
                <tr>
                    <th class="px-4 py-3 rounded-tl-lg">Nama Lengkap</th>
                    <th class="px-4 py-3">NIM</th>
                    <th class="px-4 py-3">Jenis Kelamin</th>
                    <th class="px-4 py-3">Tahun Lulus</th>
                    <th class="px-4 py-3">Program Studi</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">No. Telepon</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 rounded-tr-lg text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-300">
                <tr class="border-b border-slate-700 hover:bg-slate-800/40">
                    <td class="px-4 py-3">Budi Santoso</td>
                    <td class="px-4 py-3">2019010001</td>
                    <td class="px-4 py-3">Laki-laki</td>
                    <td class="px-4 py-3">2023</td>
                    <td class="px-4 py-3">PTIK</td>
                    <td class="px-4 py-3">budi@example.test</td>
                    <td class="px-4 py-3">08123456789</td>
                    <td class="px-4 py-3"><span class="bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">Bekerja</span></td>
                    <td class="px-4 py-3 text-right">
                        <button class="text-blue-400 hover:text-blue-300 mr-2 show-detail-btn"><i class="fas fa-eye"></i></button>
                        <button class="text-red-400 hover:text-red-300 delete-btn"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr class="border-b border-slate-700 hover:bg-slate-800/40">
                    <td class="px-4 py-3">Siti Nurhaliza</td>
                    <td class="px-4 py-3">2019010002</td>
                    <td class="px-4 py-3">Perempuan</td>
                    <td class="px-4 py-3">2023</td>
                    <td class="px-4 py-3">PTIK</td>
                    <td class="px-4 py-3">siti@example.test</td>
                    <td class="px-4 py-3">08198765432</td>
                    <td class="px-4 py-3"><span class="bg-purple-500/20 text-purple-400 text-xs px-2 py-1 rounded">Studi Lanjut</span></td>
                    <td class="px-4 py-3 text-right">
                        <button class="text-blue-400 hover:text-blue-300 mr-2 show-detail-btn"><i class="fas fa-eye"></i></button>
                        <button class="text-red-400 hover:text-red-300 delete-btn"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr class="border-b border-slate-700 hover:bg-slate-800/40">
                    <td class="px-4 py-3">Ahmad Fauzi</td>
                    <td class="px-4 py-3">2019010003</td>
                    <td class="px-4 py-3">Laki-laki</td>
                    <td class="px-4 py-3">2023</td>
                    <td class="px-4 py-3">PTIK</td>
                    <td class="px-4 py-3">ahmad@example.test</td>
                    <td class="px-4 py-3">08187654321</td>
                    <td class="px-4 py-3"><span class="bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">Bekerja & Studi</span></td>
                    <td class="px-4 py-3 text-right">
                        <button class="text-blue-400 hover:text-blue-300 mr-2 show-detail-btn"><i class="fas fa-eye"></i></button>
                        <button class="text-red-400 hover:text-red-300 delete-btn"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr class="border-b border-slate-700 hover:bg-slate-800/40">
                    <td class="px-4 py-3">Dewi Putri</td>
                    <td class="px-4 py-3">2019010004</td>
                    <td class="px-4 py-3">Perempuan</td>
                    <td class="px-4 py-3">2023</td>
                    <td class="px-4 py-3">PTIK</td>
                    <td class="px-4 py-3">dewi@example.test</td>
                    <td class="px-4 py-3">08112345678</td>
                    <td class="px-4 py-3"><span class="bg-red-500/20 text-red-400 text-xs px-2 py-1 rounded">Belum Terdata</span></td>
                    <td class="px-4 py-3 text-right">
                        <button class="text-blue-400 hover:text-blue-300 mr-2 show-detail-btn"><i class="fas fa-eye"></i></button>
                        <button class="text-red-400 hover:text-red-300 delete-btn"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr class="hover:bg-slate-800/40">
                    <td class="px-4 py-3">Eko Prasetyo</td>
                    <td class="px-4 py-3">2019010005</td>
                    <td class="px-4 py-3">Laki-laki</td>
                    <td class="px-4 py-3">2023</td>
                    <td class="px-4 py-3">PTIK</td>
                    <td class="px-4 py-3">eko@example.test</td>
                    <td class="px-4 py-3">08156789012</td>
                    <td class="px-4 py-3"><span class="bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">Bekerja</span></td>
                    <td class="px-4 py-3 text-right">
                        <button class="text-blue-400 hover:text-blue-300 mr-2 show-detail-btn"><i class="fas fa-eye"></i></button>
                        <button class="text-red-400 hover:text-red-300 delete-btn"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4">
        <p class="text-sm text-gray-400">Menampilkan 5 dari 50 alumni</p>
        <div class="flex">
            <a href="#" class="text-gray-400 hover:text-white px-3 py-1 rounded-lg mr-1 bg-slate-800">Prev</a>
            <a href="#" class="text-white px-3 py-1 rounded-lg mr-1 bg-primary-600">1</a>
            <a href="#" class="text-gray-400 hover:text-white px-3 py-1 rounded-lg mr-1 bg-slate-800">2</a>
            <a href="#" class="text-gray-400 hover:text-white px-3 py-1 rounded-lg mr-1 bg-slate-800">3</a>
            <a href="#" class="text-gray-400 hover:text-white px-3 py-1 rounded-lg bg-slate-800">Next</a>
        </div>
    </div>
</div>
@endsection

@section('modals')
<!-- Modal - Add Alumni -->
<div id="addAlumniModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative top-20 mx-auto max-w-4xl bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700 max-h-[80vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold text-white">Tambah Alumni Baru</h3>
            <button id="closeAddAlumniModal" class="text-gray-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-300 mb-1">Nama Lengkap</label>
                    <input type="text" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                </div>
                
                <div>
                    <label class="block text-gray-300 mb-1">NIM</label>
                    <input type="text" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                </div>
                
                <div>
                    <label class="block text-gray-300 mb-1">Jenis Kelamin</label>
                    <select class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                        <option value="laki-laki">Laki-laki</option>
                        <option value="perempuan">Perempuan</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-gray-300 mb-1">Tahun Lulus</label>
                    <select class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                        <option value="2021">2021</option>
                        <option value="2020">2020</option>
                        <option value="2019">2019</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-gray-300 mb-1">Program Studi</label>
                    <select class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                        <option value="1">PTIK</option>
                        <option value="2">Teknik Sipil</option>
                        <option value="3">Teknik Mesin</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-gray-300 mb-1">Email</label>
                    <input type="email" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                </div>
                
                <div>
                    <label class="block text-gray-300 mb-1">Password</label>
                    <input type="password" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                </div>
                
                <div>
                    <label class="block text-gray-300 mb-1">Nomor Telepon</label>
                    <input type="text" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-gray-300 mb-1">Alamat</label>
                    <textarea rows="3" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500"></textarea>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="button" id="cancelAddAlumni" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg mr-2">
                    Batal
                </button>
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal - Show Detail Alumni -->
<div id="showAlumniModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative top-20 mx-auto max-w-4xl bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700 max-h-[80vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold text-white">Detail Alumni</h3>
            <button id="closeShowAlumniModal" class="text-gray-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="bg-slate-900 rounded-lg p-4 mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="md:w-1/3">
                    <div class="flex flex-col items-center p-4 bg-slate-800 rounded-lg">
                        <div class="w-32 h-32 bg-primary-800/30 rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-user text-primary-400 text-5xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-white">Budi Santoso</h4>
                        <p class="text-sm text-gray-400">NIM: 2019010001</p>
                        <div class="bg-green-500/20 text-green-400 text-xs px-3 py-1 rounded-full mt-2">
                            Bekerja
                        </div>
                    </div>
                </div>
                
                <div class="md:w-2/3">
                    <h4 class="text-lg font-medium text-white mb-3">Informasi Pribadi</h4>
                    
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-4">
                        <div>
                            <dt class="text-sm text-gray-400">Jenis Kelamin</dt>
                            <dd class="text-white">Laki-laki</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-400">Tahun Lulus</dt>
                            <dd class="text-white">2023</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-400">Program Studi</dt>
                            <dd class="text-white">PTIK</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-400">Email</dt>
                            <dd class="text-white">budi@example.test</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-400">Nomor Telepon</dt>
                            <dd class="text-white">08123456789</dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-sm text-gray-400">Alamat</dt>
                            <dd class="text-white">Jl. Pahlawan No. 123, Jakarta Selatan</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
        
        <!-- Tabs -->
        <div class="mb-4 border-b border-slate-700">
            <ul class="flex flex-wrap -mb-px">
                <li class="mr-2">
                    <a href="#" class="inline-block p-4 border-b-2 border-primary-500 text-primary-400 rounded-t-lg active" id="tab-pekerjaan">Riwayat Pekerjaan</a>
                </li>
                <li class="mr-2">
                    <a href="#" class="inline-block p-4 border-b-2 border-transparent hover:border-gray-600 text-gray-400 hover:text-gray-300 rounded-t-lg" id="tab-pendidikan">Riwayat Pendidikan</a>
                </li>
            </ul>
        </div>
        
        <!-- Riwayat Pekerjaan Tab -->
        <div id="pekerjaan-content" class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-medium text-white">Riwayat Pekerjaan</h4>
                <button class="bg-primary-600 hover:bg-primary-700 text-white px-3 py-1 rounded-lg text-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah
                </button>
            </div>
            
            <div class="space-y-4">
                <div class="bg-slate-800 p-4 rounded-lg">
                    <div class="flex justify-between">
                        <div>
                            <h5 class="font-medium text-white">Software Engineer</h5>
                            <p class="text-primary-400">PT Teknologi Maju</p>
                            <p class="text-sm text-gray-400">2023 - Sekarang (Masih Bekerja)</p>
                        </div>
                        <div class="text-right">
                            <p class="text-green-400 font-medium">Rp 8.000.000</p>
                            <div class="mt-2">
                                <button class="text-blue-400 hover:text-blue-300 mr-2"><i class="fas fa-edit"></i></button>
                                <button class="text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-slate-800 p-4 rounded-lg">
                    <div class="flex justify-between">
                        <div>
                            <h5 class="font-medium text-white">Web Developer (Internship)</h5>
                            <p class="text-primary-400">PT Digital Solusi</p>
                            <p class="text-sm text-gray-400">2022 - 2023</p>
                        </div>
                        <div class="text-right">
                            <p class="text-green-400 font-medium">Rp 3.500.000</p>
                            <div class="mt-2">
                                <button class="text-blue-400 hover:text-blue-300 mr-2"><i class="fas fa-edit"></i></button>
                                <button class="text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Riwayat Pendidikan Tab (Hidden by default) -->
        <div id="pendidikan-content" class="mb-6 hidden">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-medium text-white">Riwayat Pendidikan Lanjutan</h4>
                <button class="bg-primary-600 hover:bg-primary-700 text-white px-3 py-1 rounded-lg text-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah
                </button>
            </div>
            
            <div class="space-y-4">
                <div class="bg-slate-800 p-4 rounded-lg">
                    <div class="flex justify-between">
                        <div>
                            <h5 class="font-medium text-white">Master of Computer Science</h5>
                            <p class="text-primary-400">Universitas Indonesia</p>
                            <p class="text-sm text-gray-400">2023 - Sekarang</p>
                        </div>
                        <div class="text-right">
                            <p class="text-purple-400 font-medium">S2</p>
                            <div class="mt-2">
                                <button class="text-blue-400 hover:text-blue-300 mr-2"><i class="fas fa-edit"></i></button>
                                <button class="text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Buttons -->
        <div class="flex justify-end">
            <button id="cancelShowAlumni" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal - Delete Confirmation -->
<div id="deleteConfirmModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative top-1/4 mx-auto max-w-md bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
        <div class="text-center mb-6">
            <div class="w-16 h-16 rounded-full bg-red-500/20 text-red-400 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-400">Apakah Anda yakin ingin menghapus data alumni ini? Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        
        <div class="flex justify-center gap-3">
            <button id="cancelDelete" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg">
                Batal
            </button>
            <button id="confirmDelete" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                Hapus
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add Alumni Modal
        const openAddAlumniModal = document.getElementById('openAddAlumniModal');
        const addAlumniModal = document.getElementById('addAlumniModal');
        const closeAddAlumniModal = document.getElementById('closeAddAlumniModal');
        const cancelAddAlumni = document.getElementById('cancelAddAlumni');
        
        if (openAddAlumniModal && addAlumniModal) {
            openAddAlumniModal.addEventListener('click', function() {
                addAlumniModal.classList.remove('hidden');
            });
        }
        
        if (closeAddAlumniModal) {
            closeAddAlumniModal.addEventListener('click', function() {
                addAlumniModal.classList.add('hidden');
            });
        }
        
        if (cancelAddAlumni) {
            cancelAddAlumni.addEventListener('click', function() {
                addAlumniModal.classList.add('hidden');
            });
        }
        
        // Show Alumni Detail Modal
        const showDetailButtons = document.querySelectorAll('.show-detail-btn');
        const showAlumniModal = document.getElementById('showAlumniModal');
        const closeShowAlumniModal = document.getElementById('closeShowAlumniModal');
        const cancelShowAlumni = document.getElementById('cancelShowAlumni');
        
        if (showDetailButtons.length > 0 && showAlumniModal) {
            showDetailButtons.forEach(button => {
                button.addEventListener('click', function() {
                    showAlumniModal.classList.remove('hidden');
                });
            });
        }
        
        if (closeShowAlumniModal) {
            closeShowAlumniModal.addEventListener('click', function() {
                showAlumniModal.classList.add('hidden');
            });
        }
        
        if (cancelShowAlumni) {
            cancelShowAlumni.addEventListener('click', function() {
                showAlumniModal.classList.add('hidden');
            });
        }
        
        // Delete Confirmation Modal
        const deleteButtons = document.querySelectorAll('.delete-btn');
        const deleteConfirmModal = document.getElementById('deleteConfirmModal');
        const cancelDelete = document.getElementById('cancelDelete');
        const confirmDelete = document.getElementById('confirmDelete');
        
        if (deleteButtons.length > 0 && deleteConfirmModal) {
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    deleteConfirmModal.classList.remove('hidden');
                });
            });
        }
        
        if (cancelDelete) {
            cancelDelete.addEventListener('click', function() {
                deleteConfirmModal.classList.add('hidden');
            });
        }
        
        if (confirmDelete) {
            confirmDelete.addEventListener('click', function() {
                // Here you would handle the delete action
                alert('Data alumni berhasil dihapus!');
                deleteConfirmModal.classList.add('hidden');
            });
        }
        
        // Tab switching
        const tabPekerjaan = document.getElementById('tab-pekerjaan');
        const tabPendidikan = document.getElementById('tab-pendidikan');
        const pekerjaanContent = document.getElementById('pekerjaan-content');
        const pendidikanContent = document.getElementById('pendidikan-content');
        
        if (tabPekerjaan && tabPendidikan && pekerjaanContent && pendidikanContent) {
            tabPekerjaan.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Update tab UI
                tabPekerjaan.classList.add('border-primary-500', 'text-primary-400');
                tabPekerjaan.classList.remove('border-transparent', 'text-gray-400');
                
                tabPendidikan.classList.remove('border-primary-500', 'text-primary-400');
                tabPendidikan.classList.add('border-transparent', 'text-gray-400');
                
                // Show/hide content
                pekerjaanContent.classList.remove('hidden');
                pendidikanContent.classList.add('hidden');
            });
            
            tabPendidikan.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Update tab UI
                tabPendidikan.classList.add('border-primary-500', 'text-primary-400');
                tabPendidikan.classList.remove('border-transparent', 'text-gray-400');
                
                tabPekerjaan.classList.remove('border-primary-500', 'text-primary-400');
                tabPekerjaan.classList.add('border-transparent', 'text-gray-400');
                
                // Show/hide content
                pendidikanContent.classList.remove('hidden');
                pekerjaanContent.classList.add('hidden');
            });
        }
    });
</script>
@endpush