<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Incident;
use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereIn('role', [User::ROLE_DOCTOR, User::ROLE_NURSE, User::ROLE_STAFF])->get();
        $incidents = Incident::with('reporter')->get();

        if ($users->isEmpty() || $incidents->isEmpty()) {
            $this->command->error('Please run UserSeeder and IncidentSeeder first');
            return;
        }

        $this->createNotifications($users, $incidents);
    }

    /**
     * Create sample notifications
     */
    private function createNotifications($users, $incidents)
    {
        $notificationTemplates = [
            [
                'type' => Notification::TYPE_INCIDENT_ASSIGNED,
                'title' => 'New Incident Assigned',
                'message_template' => 'You have been assigned to handle incident: {incident_title}',
            ],
            [
                'type' => Notification::TYPE_INCIDENT_UPDATED,
                'title' => 'Incident Updated',
                'message_template' => 'Incident "{incident_title}" has been updated with new information',
            ],
            [
                'type' => Notification::TYPE_RESOURCE_ALLOCATED,
                'title' => 'Resources Allocated',
                'message_template' => 'Resources have been allocated for incident: {incident_title}',
            ],
            [
                'type' => Notification::TYPE_INCIDENT_RESOLVED,
                'title' => 'Incident Resolved',
                'message_template' => 'Incident "{incident_title}" has been successfully resolved',
            ],
        ];

        $createdCount = 0;

        foreach ($users as $user) {
            // Create 3-7 notifications per user
            $notificationCount = rand(3, 7);
            
            for ($i = 0; $i < $notificationCount; $i++) {
                $template = $notificationTemplates[array_rand($notificationTemplates)];
                $incident = $incidents->random();
                
                // Skip if user is the reporter (they don't get notifications for their own reports)
                if ($incident->reported_by === $user->id) {
                    continue;
                }

                $message = str_replace('{incident_title}', $incident->title, $template['message_template']);

                Notification::create([
                    'user_id' => $user->id,
                    'incident_id' => $incident->id,
                    'title' => $template['title'],
                    'message' => $message,
                    'type' => $template['type'],
                    'is_read' => rand(0, 1) === 1, // Randomly mark some as read
                    'data' => [
                        'incident_severity' => $incident->severity,
                        'incident_location' => $incident->location,
                    ],
                    'created_at' => now()->subHours(rand(1, 72)), // Random time in last 3 days
                ]);

                $createdCount++;
            }
        }

        // Create some recent unread notifications for demonstration
        $recentUsers = $users->take(5);
        foreach ($recentUsers as $user) {
            $recentIncident = $incidents->where('created_at', '>', now()->subHours(24))->first();
            
            if ($recentIncident && $recentIncident->reported_by !== $user->id) {
                Notification::create([
                    'user_id' => $user->id,
                    'incident_id' => $recentIncident->id,
                    'title' => 'Urgent: New Critical Incident',
                    'message' => "Critical incident reported: {$recentIncident->title}. Immediate attention required.",
                    'type' => Notification::TYPE_INCIDENT_UPDATED,
                    'is_read' => false,
                    'data' => [
                        'incident_severity' => $recentIncident->severity,
                        'incident_location' => $recentIncident->location,
                        'priority' => 'high',
                    ],
                    'created_at' => now()->subMinutes(rand(5, 60)),
                ]);

                $createdCount++;
            }
        }

        $this->command->info("Created {$createdCount} notifications");
    }
}