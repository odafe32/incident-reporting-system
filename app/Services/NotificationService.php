<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Incident;

class NotificationService
{
    /**
     * Create incident assigned notification
     */
    public static function createIncidentAssignedNotification(Incident $incident, User $assignedUser, User $assignedBy)
    {
        return Notification::create([
            'user_id' => $assignedUser->id,
            'incident_id' => $incident->id,
            'type' => Notification::TYPE_INCIDENT_ASSIGNED,
            'title' => 'New Incident Assigned',
            'message' => "You have been assigned to incident: {$incident->title} by {$assignedBy->name}",
            'data' => [
                'incident_id' => $incident->id,
                'assigned_by' => $assignedBy->name,
                'severity' => $incident->severity,
                'location' => $incident->location,
            ]
        ]);
    }

    /**
     * Create incident updated notification
     */
    public static function createIncidentUpdatedNotification(Incident $incident, User $user, string $updateType)
    {
        $message = match($updateType) {
            'status_change' => "Incident status changed to: {$incident->status}",
            'resource_allocated' => "Resources have been allocated to your incident",
            'message_added' => "New message added to your incident",
            default => "Your incident has been updated"
        };

        return Notification::create([
            'user_id' => $user->id,
            'incident_id' => $incident->id,
            'type' => Notification::TYPE_INCIDENT_UPDATED,
            'title' => 'Incident Updated',
            'message' => $message,
            'data' => [
                'incident_id' => $incident->id,
                'update_type' => $updateType,
                'status' => $incident->status,
            ]
        ]);
    }

    /**
     * Create incident resolved notification
     */
    public static function createIncidentResolvedNotification(Incident $incident, User $user)
    {
        return Notification::create([
            'user_id' => $user->id,
            'incident_id' => $incident->id,
            'type' => Notification::TYPE_INCIDENT_RESOLVED,
            'title' => 'Incident Resolved',
            'message' => "Your incident '{$incident->title}' has been resolved",
            'data' => [
                'incident_id' => $incident->id,
                'resolved_at' => now()->toISOString(),
            ]
        ]);
    }

    /**
     * Create resource allocated notification
     */
    public static function createResourceAllocatedNotification(Incident $incident, User $user, array $resources)
    {
        $resourceNames = collect($resources)->pluck('name')->join(', ');
        
        return Notification::create([
            'user_id' => $user->id,
            'incident_id' => $incident->id,
            'type' => Notification::TYPE_RESOURCE_ALLOCATED,
            'title' => 'Resources Allocated',
            'message' => "Resources allocated to your incident: {$resourceNames}",
            'data' => [
                'incident_id' => $incident->id,
                'resources' => $resources,
            ]
        ]);
    }

    /**
     * Notify multiple users
     */
    public static function notifyMultipleUsers(array $userIds, array $notificationData)
    {
        foreach ($userIds as $userId) {
            Notification::create(array_merge($notificationData, ['user_id' => $userId]));
        }
    }
}