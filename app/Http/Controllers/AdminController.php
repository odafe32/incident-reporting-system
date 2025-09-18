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
use App\Models\Incident;
use App\Models\IncidentAction;
use App\Models\User;
use App\Models\Resource;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function showDashboard()
    {
          $user = Auth::user();

     

        try {
            // Get basic statistics
            $totalIncidents = Incident::count();
            $pendingIncidents = Incident::where('status', Incident::STATUS_PENDING)->count();
            $criticalIncidents = Incident::where('severity', Incident::SEVERITY_CRITICAL)
                ->where('status', '!=', Incident::STATUS_RESOLVED)
                ->get();
            
            // Resource statistics
            $totalResources = Resource::count();
            $availableResources = Resource::where('status', Resource::STATUS_AVAILABLE)->count();
            $inUseResources = Resource::where('status', Resource::STATUS_IN_USE)->count();
            $maintenanceResources = Resource::where('status', Resource::STATUS_MAINTENANCE)->count();
            
            // Staff statistics
            $totalStaff = User::whereIn('role', ['doctor', 'nurse', 'staff'])->count();
            $activeStaff = User::whereIn('role', ['doctor', 'nurse', 'staff'])
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->count();
            
            // Calculate percentage changes (mock data for now - you can implement actual comparison)
            $incidentsChange = $this->calculatePercentageChange($totalIncidents, $totalIncidents * 0.9);
            
            // Recent incidents (last 10)
            $recentIncidents = Incident::with(['reporter', 'assignedUser'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            // Resource statistics by type
            $resourceStats = [
                'beds' => [
                    'total' => Resource::where('type', Resource::TYPE_BED)->count(),
                    'available' => Resource::where('type', Resource::TYPE_BED)
                        ->where('status', Resource::STATUS_AVAILABLE)->count(),
                    'in_use' => Resource::where('type', Resource::TYPE_BED)
                        ->where('status', Resource::STATUS_IN_USE)->count(),
                ],
                'equipment' => [
                    'total' => Resource::where('type', Resource::TYPE_EQUIPMENT)->count(),
                    'available' => Resource::where('type', Resource::TYPE_EQUIPMENT)
                        ->where('status', Resource::STATUS_AVAILABLE)->count(),
                    'in_use' => Resource::where('type', Resource::TYPE_EQUIPMENT)
                        ->where('status', Resource::STATUS_IN_USE)->count(),
                ],
                'staff' => [
                    'total' => Resource::where('type', Resource::TYPE_STAFF)->count(),
                    'available' => Resource::where('type', Resource::TYPE_STAFF)
                        ->where('status', Resource::STATUS_AVAILABLE)->count(),
                    'in_use' => Resource::where('type', Resource::TYPE_STAFF)
                        ->where('status', Resource::STATUS_IN_USE)->count(),
                ],
            ];
            
            // Chart data for incident trends (last 7 days)
            $trendLabels = [];
            $trendData = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $trendLabels[] = $date->format('M d');
                $trendData[] = Incident::whereDate('created_at', $date)->count();
            }
            
            // Severity distribution data
            $severityData = [
                Incident::where('severity', Incident::SEVERITY_CRITICAL)->count(),
                Incident::where('severity', Incident::SEVERITY_HIGH)->count(),
                Incident::where('severity', Incident::SEVERITY_MEDIUM)->count(),
                Incident::where('severity', Incident::SEVERITY_LOW)->count(),
            ];
            
            // Recent activities (from incident actions)
            $recentActivities = IncidentAction::with(['user', 'incident'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($action) {
                    return [
                        'title' => $this->getActivityTitle($action),
                        'description' => $this->getActivityDescription($action),
                        'time' => $action->created_at->diffForHumans(),
                        'type' => $this->getActivityType($action->action_type),
                        'icon' => $this->getActivityIcon($action->action_type),
                    ];
                });
            
            // Compile all statistics
            $stats = [
                'total_incidents' => $totalIncidents,
                'pending_incidents' => $pendingIncidents,
                'available_resources' => $availableResources,
                'total_resources' => $totalResources,
                'in_use_resources' => $inUseResources,
                'maintenance_resources' => $maintenanceResources,
                'active_staff' => $activeStaff,
                'total_staff' => $totalStaff,
                'online_staff' => rand(5, max(1, $activeStaff)), // Mock online count
                'incidents_change' => $incidentsChange,
            ];
            
            // Chart data
            $chartData = [
                'trends' => [
                    'labels' => $trendLabels,
                    'data' => $trendData,
                ],
                'severity' => $severityData,
            ];
            

            $viewData = [
                'meta_title'=> 'Dashboard | Metrica, incident reporting system',
                'meta_desc'=> 'Metrica, hospital management, incident reporting, resource allocation, healthcare dashboard, emergency response system',
                'meta_image'=> url('assets/images/favicon.ico'),
                'stats' => $stats,
                'recentIncidents' => $recentIncidents,
                'criticalIncidents' => $criticalIncidents,
                'resourceStats' => $resourceStats,
                'recentActivities' => $recentActivities,
                'chartData' => $chartData,
                'user' => $user,
            ];

            return view('admin.dashboard')->with($viewData);

        } catch (\Exception $e) {
            Log::error('Error loading dashboard: ' . $e->getMessage());
            
            // Return dashboard with empty data if there's an error
            $viewData = [
                'meta_title'=> 'Dashboard | Metrica, incident reporting system',
                'meta_desc'=> 'Metrica, hospital management, incident reporting, resource allocation, healthcare dashboard, emergency response system',
                'meta_image'=> url('assets/images/favicon.ico'),
                'stats' => [
                    'total_incidents' => 0,
                    'pending_incidents' => 0,
                    'available_resources' => 0,
                    'total_resources' => 0,
                    'active_staff' => 0,
                    'incidents_change' => 0,
                ],
                'recentIncidents' => collect([]),
                'criticalIncidents' => collect([]),
                'resourceStats' => [
                    'beds' => ['total' => 0, 'available' => 0],
                    'equipment' => ['total' => 0, 'available' => 0],
                    'staff' => ['total' => 0, 'available' => 0],
                ],
                'recentActivities' => collect([]),
                'chartData' => [
                    'trends' => ['labels' => [], 'data' => []],
                    'severity' => [0, 0, 0, 0],
                ],
            ];

            return view('admin.dashboard')->with($viewData);
        }
    }

    /**
     * Get dashboard data via AJAX
     */
    public function getDashboardData()
    {
        try {
            $stats = [
                'total_incidents' => Incident::count(),
                'pending_incidents' => Incident::where('status', Incident::STATUS_PENDING)->count(),
                'available_resources' => Resource::where('status', Resource::STATUS_AVAILABLE)->count(),
                'active_staff' => User::whereIn('role', ['doctor', 'nurse', 'staff'])->count(),
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting dashboard data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard data'
            ], 500);
        }
    }

    public function showIncidents()
    {
        try {
            // Get all incidents with relationships
            $allIncidents = Incident::with(['reporter', 'assignedUser', 'actions.user'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Get pending incidents
            $pendingIncidents = Incident::with(['reporter', 'assignedUser'])
                ->where('status', Incident::STATUS_PENDING)
                ->orderBy('created_at', 'desc')
                ->get();

            // Get critical incidents
            $criticalIncidents = Incident::with(['reporter', 'assignedUser'])
                ->where('severity', Incident::SEVERITY_CRITICAL)
                ->where('status', '!=', Incident::STATUS_RESOLVED)
                ->orderBy('created_at', 'desc')
                ->get();

            // Get staff members for assignment
            $staffMembers = User::whereIn('role', ['doctor', 'nurse', 'staff'])
                ->orderBy('name')
                ->get();

            $viewData = [
                'meta_title'=> 'Incidents | Metrica, incident reporting system',
                'meta_desc'=> 'Metrica, hospital management, incident reporting, resource allocation, healthcare dashboard, emergency response system',
                'meta_image'=> url('assets/images/favicon.ico'),
                'allIncidents' => $allIncidents,
                'pendingIncidents' => $pendingIncidents,
                'criticalIncidents' => $criticalIncidents,
                'staffMembers' => $staffMembers,
            ];


            
            return view('admin.incidents')->with($viewData);

        } catch (\Exception $e) {
            Log::error('Error loading incidents page: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', 'Failed to load incidents page');
        }
    }

    public function showReources()
    {
         $user = Auth::user();
        $viewData = [
           'meta_title'=> 'Create Resources | Metrica, incident reporting system',
           'meta_desc'=> 'Metrica, hospital management, incident reporting, resource allocation, healthcare dashboard, emergency response system',
           'meta_image'=> url('assets/images/favicon.ico'),
                 'user' => $user,
        ];

        return view('admin.create-resources')->with($viewData);
    }

    public function showviewResources()
    {
        try {
            // Get all resources with relationships
            $resources = Resource::with('currentIncident')->orderBy('name')->get();
            
            // Calculate statistics
            $stats = [
                'total' => $resources->count(),
                'available' => $resources->where('status', Resource::STATUS_AVAILABLE)->count(),
                'in_use' => $resources->where('status', Resource::STATUS_IN_USE)->count(),
                'maintenance' => $resources->where('status', Resource::STATUS_MAINTENANCE)->count(),
            ];

            $viewData = [
                'meta_title'=> 'Resources | Metrica, incident reporting system',
                'meta_desc'=> 'Metrica, hospital management, incident reporting, resource allocation, healthcare dashboard, emergency response system',
                'meta_image'=> url('assets/images/favicon.ico'),
                'resources' => $resources,
                'stats' => $stats,
            ];

            return view('admin.view-resources')->with($viewData);

        } catch (\Exception $e) {
            Log::error('Error loading resources page: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', 'Failed to load resources page');
        }
    }

    /**
     * Get incident details for admin
     */
    public function getIncident($id)
    {
        try {
            $incident = Incident::with([
                'reporter', 
                'assignedUser', 
                'actions.user',
                'actions' => function($query) {
                    $query->orderBy('created_at', 'asc');
                }
            ])->findOrFail($id);

            // Get staff members for assignment
            $staffMembers = User::whereIn('role', ['doctor', 'nurse', 'staff'])
                ->orderBy('name')
                ->get();

            // Get available resources
            $resources = Resource::where('status', Resource::STATUS_AVAILABLE)
                ->orderBy('type')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'incident' => [
                    'id' => $incident->id,
                    'title' => $incident->title,
                    'description' => $incident->description,
                    'severity' => $incident->severity,
                    'status' => $incident->status,
                    'display_status' => $incident->getDisplayStatus(),
                    'location' => $incident->location,
                    'time_elapsed' => $incident->getTimeElapsed(),
                    'created_at' => $incident->created_at->format('M d, Y g:i A'),
                    'reporter' => [
                        'id' => $incident->reporter->id,
                        'name' => $incident->reporter->name,
                        'role' => $incident->reporter->role,
                        'profile_image' => $incident->reporter->profile_image,
                    ],
                    'assigned_user' => $incident->assignedUser ? [
                        'id' => $incident->assignedUser->id,
                        'name' => $incident->assignedUser->name,
                        'role' => $incident->assignedUser->role,
                    ] : null,
                    'actions' => $incident->actions->map(function($action) {
                        return [
                            'id' => $action->id,
                            'action_type' => $action->action_type,
                            'message' => $action->message,
                            'created_at' => $action->created_at->toISOString(),
                            'user_id' => $action->user_id,
                            'user' => [
                                'id' => $action->user->id,
                                'name' => $action->user->name,
                                'role' => $action->user->role,
                                'profile_image' => $action->user->profile_image,
                            ]
                        ];
                    })
                ],
                'staffMembers' => $staffMembers,
                'resources' => $resources
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting incident: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load incident details'
            ], 500);
        }
    }

    /**
     * Add message to incident
     */
    public function addIncidentMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        try {
            $incident = Incident::findOrFail($id);
            $admin = Auth::user();

            // Create action log
            $action = IncidentAction::create([
                'incident_id' => $incident->id,
                'user_id' => $admin->id,
                'action_type' => IncidentAction::TYPE_MESSAGE,
                'message' => $request->message,
            ]);

            // Load user relationship
            $action->load('user');

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'action' => [
                    'id' => $action->id,
                    'action_type' => $action->action_type,
                    'message' => $action->message,
                    'created_at' => $action->created_at->toISOString(),
                    'user_id' => $action->user_id,
                    'user' => [
                        'id' => $action->user->id,
                        'name' => $action->user->name,
                        'role' => $action->user->role,
                        'profile_image' => $action->user->profile_image,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error adding incident message: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message'
            ], 500);
        }
    }

    /**
     * Assign incident to staff member
     */
    public function assignIncident(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $incident = Incident::with(['reporter', 'assignedUser'])->findOrFail($id);
            $assignedUser = User::findOrFail($request->assigned_to);
            $admin = Auth::user();

            // Update incident
            $incident->update([
                'assigned_to' => $request->assigned_to,
                'status' => Incident::STATUS_ASSIGNED,
            ]);

            // Create action log
            $message = "Incident assigned to {$assignedUser->name}";
            if ($request->notes) {
                $message .= ". Notes: {$request->notes}";
            }

            IncidentAction::create([
                'incident_id' => $incident->id,
                'user_id' => $admin->id,
                'action_type' => IncidentAction::TYPE_ASSIGNMENT,
                'message' => $message,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Incident assigned successfully',
            ]);

        } catch (\Exception $e) {
            Log::error('Error assigning incident: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign incident'
            ], 500);
        }
    }

    /**
     * Update incident status
     */
    public function updateIncidentStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,assigned,in_progress,resolved',
        ]);

        try {
            $incident = Incident::findOrFail($id);
            $admin = Auth::user();
            $oldStatus = $incident->status;
            $newStatus = $request->status;

            // Update incident status
            $incident->update(['status' => $newStatus]);

            // If status is resolved, free up allocated resources
            if ($newStatus === Incident::STATUS_RESOLVED) {
                $this->freeIncidentResources($incident);
            }

            // Create action log
            IncidentAction::create([
                'incident_id' => $incident->id,
                'user_id' => $admin->id,
                'action_type' => IncidentAction::TYPE_STATUS_CHANGE,
                'message' => "Status changed from {$oldStatus} to {$newStatus}",
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Incident status updated successfully',
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating incident status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update incident status'
            ], 500);
        }
    }

    /**
     * Allocate resources to incident
     */
    public function allocateResources(Request $request, $id)
    {
        $request->validate([
            'resources' => 'required|string',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $incident = Incident::findOrFail($id);
            $admin = Auth::user();
            $resourceIds = json_decode($request->resources, true);

            if (!is_array($resourceIds) || empty($resourceIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid resource selection'
                ], 400);
            }

            // Get resources and validate they're available
            $resources = Resource::whereIn('id', $resourceIds)
                ->where('status', Resource::STATUS_AVAILABLE)
                ->get();

            if ($resources->count() !== count($resourceIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some selected resources are no longer available'
                ], 400);
            }

            DB::transaction(function () use ($incident, $resources, $request, $admin) {
                // Mark resources as in use
                foreach ($resources as $resource) {
                    $resource->markAsInUse($incident->id);
                }

                // Update incident resources
                $currentResources = $incident->resources_allocated ?? [];
                $newResourceIds = $resources->pluck('id')->toArray();
                $incident->update([
                    'resources_allocated' => array_unique(array_merge($currentResources, $newResourceIds))
                ]);

                // Create action log
                $resourceNames = $resources->pluck('name')->join(', ');
                $message = "Resources allocated: {$resourceNames}";
                if ($request->notes) {
                    $message .= ". Notes: {$request->notes}";
                }

                IncidentAction::create([
                    'incident_id' => $incident->id,
                    'user_id' => $admin->id,
                    'action_type' => IncidentAction::TYPE_RESOURCE_ALLOCATION,
                    'message' => $message,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Resources allocated successfully',
            ]);

        } catch (\Exception $e) {
            Log::error('Error allocating resources: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to allocate resources'
            ], 500);
        }
    }

    /**
     * Get available resources
     */
    public function getAvailableResources()
    {
        try {
            $resources = Resource::where('status', Resource::STATUS_AVAILABLE)
                ->orderBy('type')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'resources' => $resources->map(function($resource) {
                    return [
                        'id' => $resource->id,
                        'name' => $resource->name,
                        'type' => $resource->type,
                        'location' => $resource->location,
                        'description' => $resource->description,
                    ];
                })
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting available resources: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load available resources'
            ], 500);
        }
    }

    /**
     * Store a single resource
     */
    public function storeResource(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bed,equipment,staff',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $resource = Resource::create([
                'name' => $request->name,
                'type' => $request->type,
                'location' => $request->location,
                'description' => $request->description,
                'status' => Resource::STATUS_AVAILABLE,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Resource created successfully',
                'resource' => $resource
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating resource: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create resource'
            ], 500);
        }
    }

    /**
     * Update a resource
     */
    public function updateResource(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:bed,equipment,staff',
            'location' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:available,in_use,maintenance',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $resource = Resource::findOrFail($id);
            
            // Check if resource is in use and trying to change critical fields
            if ($resource->status === Resource::STATUS_IN_USE && 
                $request->has('type') && $request->type !== $resource->type) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot change type of resource that is currently in use'
                ], 400);
            }

            // Prepare update data
            $updateData = [];
            if ($request->has('name')) $updateData['name'] = $request->name;
            if ($request->has('type')) $updateData['type'] = $request->type;
            if ($request->has('location')) $updateData['location'] = $request->location;
            if ($request->has('status')) $updateData['status'] = $request->status;
            if ($request->has('description')) $updateData['description'] = $request->description;

            $resource->update($updateData);

            // If status changed to available, clear incident association
            if ($request->has('status') && $request->status === Resource::STATUS_AVAILABLE && $resource->current_incident_id) {
                $resource->update(['current_incident_id' => null]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Resource updated successfully',
                'resource' => $resource->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating resource: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update resource: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a resource
     */
    public function deleteResource($id)
    {
        try {
            $resource = Resource::findOrFail($id);
            
            // Check if resource is in use
            if ($resource->status === Resource::STATUS_IN_USE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete resource that is currently in use'
                ], 400);
            }

            $resourceName = $resource->name;
            $resource->delete();

            return response()->json([
                'success' => true,
                'message' => "Resource '{$resourceName}' deleted successfully"
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting resource: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete resource'
            ], 500);
        }
    }

    /**
     * Free resources when incident is resolved
     */
    private function freeIncidentResources(Incident $incident)
    {
        if ($incident->resources_allocated) {
            Resource::whereIn('id', $incident->resources_allocated)
                ->where('current_incident_id', $incident->id)
                ->update([
                    'status' => Resource::STATUS_AVAILABLE,
                    'current_incident_id' => null
                ]);
        }
    }

    /**
     * Helper method to calculate percentage change
     */
    private function calculatePercentageChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        
        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * Helper method to get activity title
     */
    private function getActivityTitle($action)
    {
        switch ($action->action_type) {
            case IncidentAction::TYPE_ASSIGNMENT:
                return 'Incident Assigned';
            case IncidentAction::TYPE_RESOURCE_ALLOCATION:
                return 'Resources Allocated';
            case IncidentAction::TYPE_STATUS_CHANGE:
                return 'Status Updated';
            case IncidentAction::TYPE_RESOLUTION:
                return 'Incident Resolved';
            case IncidentAction::TYPE_MESSAGE:
                return 'New Message';
            default:
                return 'Activity';
        }
    }

    /**
     * Helper method to get activity description
     */
    private function getActivityDescription($action)
    {
        $userName = $action->user->name ?? 'Unknown User';
        $incidentTitle = Str::limit($action->incident->title ?? 'Unknown Incident', 30);
        
        switch ($action->action_type) {
            case IncidentAction::TYPE_ASSIGNMENT:
                return "{$userName} assigned incident: {$incidentTitle}";
            case IncidentAction::TYPE_RESOURCE_ALLOCATION:
                return "{$userName} allocated resources to: {$incidentTitle}";
            case IncidentAction::TYPE_STATUS_CHANGE:
                return "{$userName} updated status of: {$incidentTitle}";
            case IncidentAction::TYPE_RESOLUTION:
                return "{$userName} resolved incident: {$incidentTitle}";
            case IncidentAction::TYPE_MESSAGE:
                return "{$userName} added message to: {$incidentTitle}";
            default:
                return "{$userName} performed action on: {$incidentTitle}";
        }
    }

    /**
     * Helper method to get activity type for styling
     */
    private function getActivityType($actionType)
    {
        switch ($actionType) {
            case IncidentAction::TYPE_ASSIGNMENT:
                return 'info';
            case IncidentAction::TYPE_RESOURCE_ALLOCATION:
                return 'warning';
            case IncidentAction::TYPE_STATUS_CHANGE:
                return 'primary';
            case IncidentAction::TYPE_RESOLUTION:
                return 'success';
            case IncidentAction::TYPE_MESSAGE:
                return 'secondary';
            default:
                return 'primary';
        }
    }

    /**
     * Helper method to get activity icon
     */
    private function getActivityIcon($actionType)
    {
        switch ($actionType) {
            case IncidentAction::TYPE_ASSIGNMENT:
                return 'mdi-account-plus';
            case IncidentAction::TYPE_RESOURCE_ALLOCATION:
                return 'mdi-hospital-box';
            case IncidentAction::TYPE_STATUS_CHANGE:
                return 'mdi-update';
            case IncidentAction::TYPE_RESOLUTION:
                return 'mdi-check-circle';
            case IncidentAction::TYPE_MESSAGE:
                return 'mdi-message';
            default:
                return 'mdi-information';
        }
    }
        /**
     * Show profile page
     */
    public function ViewProfile()
    {
        $user = Auth::user();

        $viewData = [
            'meta_title' => 'Profile | Metrica, incident reporting system',
            'meta_desc' => 'Hospital management staff profile',
            'meta_image' => url('assets/images/favicon.ico'),
            'user' => $user,
        ];

        return view('admin.profile')->with($viewData);
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


}