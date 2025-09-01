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
use App\Services\NotificationService;

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


/**
 * Assign incident to staff member
 */
public function assignIncident(Request $request, $id)
{
    $request->validate([
        'assigned_to' => 'required|exists:users,id',
    ]);

    $incident = Incident::with(['reporter', 'assignedUser'])->findOrFail($id);
    $assignedUser = User::findOrFail($request->assigned_to);
    $admin = Auth::user();

    // Update incident
    $incident->update([
        'assigned_to' => $request->assigned_to,
        'status' => 'assigned',
    ]);

    // Create action log
    IncidentAction::create([
        'incident_id' => $incident->id,
        'user_id' => $admin->id,
        'action_type' => 'assignment',
        'message' => "Incident assigned to {$assignedUser->name}",
    ]);

    // Send notification to assigned user
    NotificationService::createIncidentAssignedNotification($incident, $assignedUser, $admin);

    // Send notification to reporter
    if ($incident->reporter) {
        NotificationService::createIncidentUpdatedNotification(
            $incident, 
            $incident->reporter, 
            'status_change'
        );
    }

    return response()->json([
        'success' => true,
        'message' => 'Incident assigned successfully',
    ]);
}
}