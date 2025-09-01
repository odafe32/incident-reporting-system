<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Incident;
use App\Models\IncidentAction;
use App\Models\Resource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Services\NotificationService;

class StaffController extends Controller
{
   /**
 * Show staff dashboard with role-specific content
 */
public function showDashboard()
{
    $user = Auth::user();
    
    // Get dashboard statistics - only for incidents user can see
    $stats = [
        'total_incidents' => Incident::where(function($query) use ($user) {
            $query->where('reported_by', $user->id)
                  ->orWhere('assigned_to', $user->id);
        })->count(),
        'my_reported_incidents' => Incident::where('reported_by', $user->id)->count(),
        'assigned_incidents' => Incident::where('assigned_to', $user->id)->count(),
        'pending_incidents' => Incident::where(function($query) use ($user) {
            $query->where('reported_by', $user->id)
                  ->orWhere('assigned_to', $user->id);
        })->where('status', 'pending')->count(),
        'critical_incidents' => Incident::where(function($query) use ($user) {
            $query->where('reported_by', $user->id)
                  ->orWhere('assigned_to', $user->id);
        })->where('severity', 'critical')->count(),
    ];

    // Get recent incidents - only ones user can see
    $recentIncidents = Incident::with(['reporter', 'assignedUser'])
        ->where(function($query) use ($user) {
            $query->where('reported_by', $user->id)
                  ->orWhere('assigned_to', $user->id);
        })
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    $viewData = [
        'meta_title' => 'Dashboard | Metrica, incident reporting system',
        'meta_desc' => 'Hospital management staff dashboard',
        'meta_image' => url('assets/images/favicon.ico'),
        'user' => $user,
        'stats' => $stats,
        'recentIncidents' => $recentIncidents,
    ];

    return view('staff.dashboard')->with($viewData);
}


/**
 * Show incidents page with tabs - filtered by user access
 */
public function showIncidents()
{
    $user = Auth::user();
    
    // Get incidents user can see (reported by them OR assigned to them)
    $userIncidents = Incident::with(['reporter', 'assignedUser', 'actions'])
        ->where(function($query) use ($user) {
            $query->where('reported_by', $user->id)
                  ->orWhere('assigned_to', $user->id);
        })
        ->orderBy('created_at', 'desc')
        ->get();

    // Get incidents reported by this user
    $reportedIncidents = $userIncidents->where('reported_by', $user->id);

    // Get incidents assigned to this user
    $assignedIncidents = $userIncidents->where('assigned_to', $user->id);

    $viewData = [
        'meta_title' => 'Incidents | Metrica, incident reporting system',
        'meta_desc' => 'Hospital incident management',
        'meta_image' => url('assets/images/favicon.ico'),
        'user' => $user,
        'allIncidents' => $userIncidents, // Only incidents user can access
        'reportedIncidents' => $reportedIncidents,
        'assignedIncidents' => $assignedIncidents,
    ];

    return view('staff.incidents')->with($viewData);
}

    /**
     * Store a new incident report
     */
    public function storeIncident(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'severity' => 'required|in:low,medium,high,critical',
            'location' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        DB::beginTransaction();
        try {
            // Extract title from message (first 50 characters)
            $title = strlen($request->message) > 50 
                ? substr($request->message, 0, 47) . '...' 
                : $request->message;

            // Create incident
            $incident = Incident::create([
                'title' => $title,
                'description' => $request->message,
                'reported_by' => $user->id,
                'severity' => $request->severity,
                'status' => Incident::STATUS_PENDING,
                'location' => $request->location,
            ]);

            // Create initial action (the report message)
            IncidentAction::create([
                'incident_id' => $incident->id,
                'user_id' => $user->id,
                'action_type' => IncidentAction::TYPE_MESSAGE,
                'message' => $request->message,
            ]);

            // Notify all admins about new incident
            $admins = User::where('role', User::ROLE_ADMIN)->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'incident_id' => $incident->id,
                    'title' => 'New Incident Reported',
                    'message' => "New {$request->severity} severity incident reported by {$user->name}",
                    'type' => Notification::TYPE_INCIDENT_UPDATED,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Incident reported successfully',
                'incident' => $incident->load(['reporter', 'actions.user']),
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating incident: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to report incident. Please try again.',
            ], 500);
        }
    }


/**
 * Get incident details with actions (chat messages) - with authorization
 */
public function getIncident($id)
{
    $user = Auth::user();
    
    $incident = Incident::with([
        'reporter', 
        'assignedUser', 
        'actions.user',
        'actions' => function($query) {
            $query->orderBy('created_at', 'asc');
        }
    ])->findOrFail($id);

    // Check if user has access to this incident
    if ($incident->reported_by !== $user->id && $incident->assigned_to !== $user->id) {
        return response()->json([
            'success' => false,
            'message' => 'You are not authorized to view this incident.',
        ], 403);
    }

    return response()->json([
        'success' => true,
        'incident' => $incident,
    ]);
}

    /**
     * Show resources page
     */
    public function showResources()
    {
        $user = Auth::user();
        
        $resources = Resource::with('currentIncident')
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        $viewData = [
            'meta_title' => 'Resources | Metrica, incident reporting system',
            'meta_desc' => 'Hospital resource management',
            'meta_image' => url('assets/images/favicon.ico'),
            'user' => $user,
            'resources' => $resources,
        ];

        return view('staff.resources')->with($viewData);
    }

    /**
     * Show profile page
     */
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

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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



    /**
     * Mark notification as read
     */
    public function markNotificationAsRead($id)
    {
        $user = Auth::user();
        
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead()
    {
        $user = Auth::user();
        
        $user->notifications()->unread()->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
        ]);
    }
    /**
 * Get user notifications
 */
public function getNotifications()
{
    $user = Auth::user();
    
    $notifications = $user->getRecentNotifications(20);

    return response()->json([
        'success' => true,
        'notifications' => $notifications->map(function($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'type' => $notification->type,
                'is_read' => $notification->is_read,
                'created_at' => $notification->created_at->toISOString(),
                'time_elapsed' => $notification->getTimeElapsed(),
                'icon' => $notification->getTypeIcon(),
                'badge_class' => $notification->getTypeBadgeClass(),
                'incident' => $notification->incident ? [
                    'id' => $notification->incident->id,
                    'title' => $notification->incident->title,
                    'severity' => $notification->incident->severity,
                ] : null
            ];
        }),
        'unread_count' => $user->getUnreadNotificationsCount(),
    ]);
}

/**
 * Add incident message with notification
 */
public function addIncidentMessage(Request $request, $id)
{
    $request->validate([
        'message' => 'required|string|max:1000',
    ]);

    $user = Auth::user();
    $incident = Incident::with(['reporter', 'assignedUser'])->findOrFail($id);

    // Check authorization
    if ($incident->reported_by !== $user->id && $incident->assigned_to !== $user->id) {
        return response()->json([
            'success' => false,
            'message' => 'You are not authorized to add messages to this incident.',
        ], 403);
    }

    // Create the action/message
    $action = IncidentAction::create([
        'incident_id' => $incident->id,
        'user_id' => $user->id,
        'action_type' => 'message',
        'message' => $request->message,
    ]);

    $action->load('user');

    // Send notification to the other party
    if ($incident->reported_by !== $user->id && $incident->reporter) {
        NotificationService::createIncidentUpdatedNotification(
            $incident, 
            $incident->reporter, 
            'message_added'
        );
    }

    if ($incident->assigned_to !== $user->id && $incident->assignedUser) {
        NotificationService::createIncidentUpdatedNotification(
            $incident, 
            $incident->assignedUser, 
            'message_added'
        );
    }

    return response()->json([
        'success' => true,
        'message' => 'Message sent successfully',
        'action' => $action,
    ]);
}
}