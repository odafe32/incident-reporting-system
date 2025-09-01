<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Incident;
use App\Models\IncidentAction;
use App\Models\Resource;
use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IncidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users for seeding
        $admins = User::where('role', User::ROLE_ADMIN)->get();
        $doctors = User::where('role', User::ROLE_DOCTOR)->get();
        $nurses = User::where('role', User::ROLE_NURSE)->get();
        $staff = User::where('role', User::ROLE_STAFF)->get();

        if ($admins->isEmpty() || $doctors->isEmpty() || $nurses->isEmpty()) {
            $this->command->error('Please run UserSeeder first to create users');
            return;
        }

        $this->createIncidents($admins, $doctors, $nurses, $staff);
    }

    /**
     * Create sample incidents
     */
    private function createIncidents($admins, $doctors, $nurses, $staff)
    {
        $incidentData = [
            [
                'title' => 'Cardiac arrest in ICU Ward A',
                'description' => 'Patient in bed 2 experiencing cardiac arrest. Immediate medical attention required.',
                'severity' => 'critical',
                'status' => 'resolved',
                'location' => 'ICU Ward A, Bed 2',
                'reporter_type' => 'nurse',
                'assigned_type' => 'doctor',
                'messages' => [
                    ['type' => 'message', 'content' => 'Patient in bed 2 experiencing cardiac arrest. Immediate medical attention required.', 'user_type' => 'nurse'],
                    ['type' => 'assignment', 'content' => 'Dr. Sarah Johnson assigned to handle cardiac emergency', 'user_type' => 'admin'],
                    ['type' => 'resource_allocation', 'content' => 'Defibrillator DF-002 and Cardiac Monitor CM-002 allocated', 'user_type' => 'admin'],
                    ['type' => 'message', 'content' => 'On my way to ICU Ward A. Preparing for emergency response.', 'user_type' => 'doctor'],
                    ['type' => 'message', 'content' => 'CPR initiated. Patient responding to treatment.', 'user_type' => 'doctor'],
                    ['type' => 'resolution', 'content' => 'Patient stabilized. Transferred to intensive monitoring.', 'user_type' => 'doctor'],
                ]
            ],
            [
                'title' => 'Equipment malfunction in OR 2',
                'description' => 'Ventilator V-005 showing error codes during surgery. Need immediate replacement.',
                'severity' => 'high',
                'status' => 'assigned',
                'location' => 'Operating Room 2',
                'reporter_type' => 'nurse',
                'assigned_type' => 'staff',
                'messages' => [
                    ['type' => 'message', 'content' => 'Ventilator V-005 showing error codes during surgery. Need immediate replacement.', 'user_type' => 'nurse'],
                    ['type' => 'assignment', 'content' => 'Equipment Technician assigned to resolve ventilator issue', 'user_type' => 'admin'],
                    ['type' => 'message', 'content' => 'Checking the ventilator now. Will provide backup unit immediately.', 'user_type' => 'staff'],
                ]
            ],
            [
                'title' => 'Patient fall in General Ward 1',
                'description' => 'Elderly patient fell while trying to get to bathroom. Possible hip injury.',
                'severity' => 'medium',
                'status' => 'in_progress',
                'location' => 'General Ward 1, Room 105',
                'reporter_type' => 'nurse',
                'assigned_type' => 'doctor',
                'messages' => [
                    ['type' => 'message', 'content' => 'Elderly patient fell while trying to get to bathroom. Possible hip injury.', 'user_type' => 'nurse'],
                    ['type' => 'assignment', 'content' => 'Dr. Michael Chen assigned to assess patient injury', 'user_type' => 'admin'],
                    ['type' => 'message', 'content' => 'Patient is conscious and alert. Ordering X-ray to check for fractures.', 'user_type' => 'doctor'],
                ]
            ],
            [
                'title' => 'Medication error in Pediatrics',
                'description' => 'Wrong dosage administered to 8-year-old patient. Need immediate assessment.',
                'severity' => 'high',
                'status' => 'assigned',
                'location' => 'Pediatrics Ward, Room 301',
                'reporter_type' => 'nurse',
                'assigned_type' => 'doctor',
                'messages' => [
                    ['type' => 'message', 'content' => 'Wrong dosage administered to 8-year-old patient. Need immediate assessment.', 'user_type' => 'nurse'],
                    ['type' => 'assignment', 'content' => 'Dr. Lisa Thompson assigned to handle medication error case', 'user_type' => 'admin'],
                ]
            ],
            [
                'title' => 'Fire alarm activation in Oncology',
                'description' => 'Fire alarm triggered in Oncology ward. Patients need to be evacuated as precaution.',
                'severity' => 'critical',
                'status' => 'pending',
                'location' => 'Oncology Ward, 3rd Floor',
                'reporter_type' => 'staff',
                'assigned_type' => null,
                'messages' => [
                    ['type' => 'message', 'content' => 'Fire alarm triggered in Oncology ward. Patients need to be evacuated as precaution.', 'user_type' => 'staff'],
                ]
            ],
            [
                'title' => 'Shortage of IV pumps in ICU',
                'description' => 'Running low on IV pumps in ICU. Need additional units for incoming patients.',
                'severity' => 'medium',
                'status' => 'pending',
                'location' => 'ICU Ward B',
                'reporter_type' => 'nurse',
                'assigned_type' => null,
                'messages' => [
                    ['type' => 'message', 'content' => 'Running low on IV pumps in ICU. Need additional units for incoming patients.', 'user_type' => 'nurse'],
                ]
            ],
        ];

        foreach ($incidentData as $data) {
            DB::beginTransaction();
            try {
                // Select reporter based on type
                $reporter = $this->selectUserByType($data['reporter_type'], $doctors, $nurses, $staff);
                
                // Select assigned user if specified
                $assignedUser = null;
                if ($data['assigned_type']) {
                    $assignedUser = $this->selectUserByType($data['assigned_type'], $doctors, $nurses, $staff);
                }

                // Create incident
                $incident = Incident::create([
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'reported_by' => $reporter->id,
                    'assigned_to' => $assignedUser?->id,
                    'severity' => $data['severity'],
                    'status' => $data['status'],
                    'location' => $data['location'],
                    'created_at' => now()->subHours(rand(1, 48)),
                ]);

                // Create incident actions (messages)
                foreach ($data['messages'] as $index => $message) {
                    $messageUser = $this->selectUserByType($message['user_type'], $doctors, $nurses, $staff, $admins);
                    
                    IncidentAction::create([
                        'incident_id' => $incident->id,
                        'user_id' => $messageUser->id,
                        'action_type' => $message['type'],
                        'message' => $message['content'],
                        'created_at' => $incident->created_at->addMinutes($index * 15),
                    ]);
                }

                // Create notifications for assigned users
                if ($assignedUser) {
                    Notification::create([
                        'user_id' => $assignedUser->id,
                        'incident_id' => $incident->id,
                        'title' => 'Incident Assigned',
                        'message' => "You have been assigned to incident: {$incident->title}",
                        'type' => Notification::TYPE_INCIDENT_ASSIGNED,
                        'created_at' => $incident->created_at->addMinutes(5),
                    ]);
                }

                // Allocate resources for resolved/in-progress incidents
                if (in_array($data['status'], ['resolved', 'in_progress', 'assigned'])) {
                    $this->allocateResources($incident);
                }

                DB::commit();
                $this->command->info("Created incident: {$incident->title}");

            } catch (\Exception $e) {
                DB::rollback();
                $this->command->error("Failed to create incident: {$data['title']} - " . $e->getMessage());
            }
        }
    }

    /**
     * Select user by type
     */
    private function selectUserByType($type, $doctors, $nurses, $staff, $admins = null)
    {
        return match($type) {
            'doctor' => $doctors->random(),
            'nurse' => $nurses->random(),
            'staff' => $staff->random(),
            'admin' => $admins ? $admins->random() : $doctors->random(),
            default => $nurses->random()
        };
    }

    /**
     * Allocate resources to incidents
     */
    private function allocateResources($incident)
    {
        $resourcesNeeded = [];

        // Allocate resources based on incident type and severity
        if (str_contains(strtolower($incident->title), 'cardiac')) {
            $resourcesNeeded = ['defibrillator', 'cardiac monitor'];
        } elseif (str_contains(strtolower($incident->title), 'equipment')) {
            $resourcesNeeded = ['equipment'];
        } elseif (str_contains(strtolower($incident->title), 'fall')) {
            $resourcesNeeded = ['wheelchair', 'x-ray'];
        }

        $allocatedResources = [];
        foreach ($resourcesNeeded as $resourceType) {
            $resource = Resource::where('name', 'like', "%{$resourceType}%")
                ->where('status', Resource::STATUS_AVAILABLE)
                ->first();

            if ($resource) {
                $resource->markAsInUse($incident->id);
                $allocatedResources[] = $resource->id;
            }
        }

        if (!empty($allocatedResources)) {
            $incident->update(['resources_allocated' => $allocatedResources]);
        }
    }
}