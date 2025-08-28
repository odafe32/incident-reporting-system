<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
    /**
     * Show staff dashboard with role-specific content
     */
    public function showDashboard()
    {
        $user = Auth::user();
       

        $viewData = [
            'meta_title' => 'Staff Dashboard | Metrica, incident reporting system',
            'meta_desc' => 'Hospital management staff dashboard',
            'meta_image' => url('assets/images/favicon.ico'),
            'user' => $user,
           
        ];

        return view('staff.dashboard')->with($viewData);
    }

}