<?php

namespace App\Http\Controllers;

use App\Services\AlumniService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $alumniService;
    public function __construct(AlumniService $alumniService)
    {
        $this->alumniService = $alumniService;
    }
    public function index()
    {
        $totalAlumni = $this->alumniService->getTotalAlumni();

        return view("admin.dashboard", [
            "totalAlumni" => $totalAlumni
        ]);
    }
}
