<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    <!-- Alumni Card -->
    <div class="card rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-white">Total Alumni</h3>
            <div class="w-12 h-12 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center">
                <i class="fas fa-users text-xl"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-white mb-1">150</p>
        <p class="text-slate-400 text-sm">+12 alumni baru bulan ini</p>
    </div>
    
    <!-- Bekerja Card -->
    <div class="card rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-white">Status Bekerja</h3>
            <div class="w-12 h-12 rounded-lg bg-green-500/20 text-green-400 flex items-center justify-center">
                <i class="fas fa-briefcase text-xl"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-white mb-1">76%</p>
        <p class="text-slate-400 text-sm">+5% dibanding tahun lalu</p>
    </div>
    
    <!-- Studi Lanjut Card -->
    <div class="card rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-white">Studi Lanjut</h3>
            <div class="w-12 h-12 rounded-lg bg-purple-500/20 text-purple-400 flex items-center justify-center">
                <i class="fas fa-graduation-cap text-xl"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-white mb-1">24%</p>
        <p class="text-slate-400 text-sm">+2% dibanding tahun lalu</p>
    </div>
</div>

<!-- Quick Actions Card -->
<div class="card rounded-xl p-6 shadow-lg mb-6">
    <h3 class="text-xl font-semibold text-white mb-6">Aksi Cepat</h3>
    
    <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
        
        <a href="#" class="bg-slate-800 hover:bg-slate-700 p-4 rounded-lg flex flex-col items-center justify-center transition duration-300">
            <div class="w-12 h-12 rounded-full bg-green-600/20 text-green-400 flex items-center justify-center mb-3">
                <i class="fas fa-file-import text-xl"></i>
            </div>
            <span class="text-white text-sm font-medium">Import Data</span>
        </a>
        
        <a href="#" class="bg-slate-800 hover:bg-slate-700 p-4 rounded-lg flex flex-col items-center justify-center transition duration-300">
            <div class="w-12 h-12 rounded-full bg-amber-600/20 text-amber-400 flex items-center justify-center mb-3">
                <i class="fas fa-file-export text-xl"></i>
            </div>
            <span class="text-white text-sm font-medium">Export Data</span>
        </a>
        
    </div>
</div>

<!-- User Management Card -->
<div class="card rounded-xl p-6 shadow-lg mb-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-white">Kelola Pengguna</h3>
        <button id="openAddUserModal" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah User
        </button>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-800 text-gray-300 text-sm">
                <tr>
                    <th class="px-4 py-3 rounded-tl-lg">Nama</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 rounded-tr-lg text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-300">
                <tr class="border-b border-slate-700 hover:bg-slate-800/40">
                    <td class="px-4 py-3">Admin Utama</td>
                    <td class="px-4 py-3">admin@example.test</td>
                    <td class="px-4 py-3"><span class="bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">Admin</span></td>
                    <td class="px-4 py-3"><span class="bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">Aktif</span></td>
                    <td class="px-4 py-3 text-right">
                        <button class="text-blue-400 hover:text-blue-300 mr-3"><i class="fas fa-edit"></i></button>
                        <button class="text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr class="border-b border-slate-700 hover:bg-slate-800/40">
                    <td class="px-4 py-3">Budi Santoso</td>
                    <td class="px-4 py-3">budi@example.test</td>
                    <td class="px-4 py-3"><span class="bg-purple-500/20 text-purple-400 text-xs px-2 py-1 rounded">Alumni</span></td>
                    <td class="px-4 py-3"><span class="bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">Aktif</span></td>
                    <td class="px-4 py-3 text-right">
                        <button class="text-blue-400 hover:text-blue-300 mr-3"><i class="fas fa-edit"></i></button>
                        <button class="text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr class="border-b border-slate-700 hover:bg-slate-800/40">
                    <td class="px-4 py-3">Siti Nurhaliza</td>
                    <td class="px-4 py-3">siti@example.test</td>
                    <td class="px-4 py-3"><span class="bg-purple-500/20 text-purple-400 text-xs px-2 py-1 rounded">Alumni</span></td>
                    <td class="px-4 py-3"><span class="bg-red-500/20 text-red-400 text-xs px-2 py-1 rounded">Tidak Aktif</span></td>
                    <td class="px-4 py-3 text-right">
                        <button class="text-blue-400 hover:text-blue-300 mr-3"><i class="fas fa-edit"></i></button>
                        <button class="text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="flex justify-between items-center mt-4">
        <p class="text-sm text-gray-400">Menampilkan 3 dari 25 pengguna</p>
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
<!-- Modal - Add User -->
<div id="addUserModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative top-20 mx-auto max-w-xl bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold text-white">Tambah User Baru</h3>
            <button id="closeAddUserModal" class="text-gray-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form>
            <div class="grid grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-gray-300 mb-1">Nama</label>
                    <input type="text" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
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
                    <label class="block text-gray-300 mb-1">Role</label>
                    <select class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                        <option>Admin</option>
                        <option>Alumni</option>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="button" id="cancelAddUser" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg mr-2">
                    Batal
                </button>
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal handling
        const addUserModal = document.getElementById('addUserModal');
        const openAddUserModal = document.getElementById('openAddUserModal');
        const closeAddUserModal = document.getElementById('closeAddUserModal');
        const cancelAddUser = document.getElementById('cancelAddUser');
        
        if (openAddUserModal && addUserModal) {
            openAddUserModal.addEventListener('click', function() {
                addUserModal.classList.remove('hidden');
            });
        }
        
        if (closeAddUserModal) {
            closeAddUserModal.addEventListener('click', function() {
                addUserModal.classList.add('hidden');
            });
        }
        
        if (cancelAddUser) {
            cancelAddUser.addEventListener('click', function() {
                addUserModal.classList.add('hidden');
            });
        }
    });
</script>
@endpush