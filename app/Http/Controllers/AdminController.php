<?php

namespace App\Http\Controllers;

use View;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;


class AdminController extends Controller
{
    public function showDashboard()
    {
        $viewData = [
           'meta_title'=> 'Dashboard | Metrica, incident reporting system',
           'meta_desc'=> 'Metrica, hospital management, incident reporting, resource allocation, healthcare dashboard, emergency response system',
           'meta_image'=> url('assets/images/favicon.ico'),
        ];

        return view('admin.dashboard')->with($viewData);
    }
}