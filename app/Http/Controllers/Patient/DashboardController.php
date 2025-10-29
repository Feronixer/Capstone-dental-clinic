<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the patient dashboard.
     */
    public function index()
    {
        return view('patient.dashboard');
    }
}

