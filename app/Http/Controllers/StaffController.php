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
            'meta_title' => ' Dashboard | Metrica, incident reporting system',
            'meta_desc' => 'Hospital management staff dashboard',
            'meta_image' => url('assets/images/favicon.ico'),
            'user' => $user,
           
        ];

        return view('staff.dashboard')->with($viewData);
    }

    public function showIncidents()
    {
        $user = Auth::user();
       

        $viewData = [
            'meta_title' => 'Incidents | Metrica, incident reporting system',
            'meta_desc' => 'Hospital management staff dashboard',
            'meta_image' => url('assets/images/favicon.ico'),
            'user' => $user,
           
        ];

        return view('staff.incidents')->with($viewData);
    }

    public function showCreateIncidents()
    {
        $user = Auth::user();
       

        $viewData = [
            'meta_title' => 'Report Incidents | Metrica, incident reporting system',
            'meta_desc' => 'Hospital management staff dashboard',
            'meta_image' => url('assets/images/favicon.ico'),
            'user' => $user,
           
        ];

        return view('staff.report-incident')->with($viewData);
    }

    public function showAssignedIncidents()
    {
        $user = Auth::user();
       

        $viewData = [
            'meta_title' => 'Assigned Incidents | Metrica, incident reporting system',
            'meta_desc' => 'Hospital management staff dashboard',
            'meta_image' => url('assets/images/favicon.ico'),
            'user' => $user,
           
        ];

        return view('staff.assigned-incident')->with($viewData);
    }


    public function showResources()
    {
        $user = Auth::user();
       

        $viewData = [
            'meta_title' => 'Resources | Metrica, incident reporting system',
            'meta_desc' => 'Hospital management staff dashboard',
            'meta_image' => url('assets/images/favicon.ico'),
            'user' => $user,
           
        ];

        return view('staff.resources')->with($viewData);
    }

public function showProfile()
{
    $user = Auth::user();

    $viewData = [
        'meta_title' => 'Profile | Metrica, incident reporting system',
        'meta_desc' => 'Hospital management staff profile',
        'meta_image' => url('assets/images/favicon.ico'),
        'user' => $user,
    ];

    return view('staff.profile')->with($viewData);
}

public function updateProfile(Request $request)
{
    $user = Auth::user();
    
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'current_password' => 'nullable|required_with:password',
        'password' => 'nullable|min:8|confirmed',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
    ]);

    // Check current password if new password is provided
    if ($request->filled('password')) {
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
        }
    }

    // Handle profile image upload
    if ($request->hasFile('profile_image')) {
        // Delete old image if exists
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }
        
        // Store new image
        $imagePath = $request->file('profile_image')->store('profile-images', 'public');
        $user->profile_image = $imagePath;
    }

    // Update user data
    $user->name = $request->name;
    $user->email = $request->email;
    
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }
    
    $user->save();

    return back()->with('success', 'Profile updated successfully.');
}

}