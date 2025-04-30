<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAlumni = Alumni::count();

        $totalBekerja = Status::where('type', 'bekerja')->count();
        $totalStudiLanjut = Status::where('type', 'kuliah')->count();

        $persenBekerja = $totalAlumni > 0 ? round(($totalBekerja / $totalAlumni) * 100, 1) : 0;
        $persenStudiLanjut = $totalAlumni > 0 ? round(($totalStudiLanjut / $totalAlumni) * 100, 1) : 0;

        $admins = User::where('is_admin', true)->get();

        return view('admin.dashboard', compact('totalAlumni', 'persenBekerja', 'persenStudiLanjut', 'admins'));
    }
}
