<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'incident_id',
        'title',
        'message',
        'type',
        'is_read',
        'data',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Type constants
    const TYPE_INCIDENT_ASSIGNED = 'incident_assigned';
    const TYPE_INCIDENT_UPDATED = 'incident_updated';
    const TYPE_RESOURCE_ALLOCATED = 'resource_allocated';
    const TYPE_INCIDENT_RESOLVED = 'incident_resolved';

    /**
     * Get the user this notification belongs to
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the incident this notification is about
     */
    public function incident(): BelongsTo
    {
        return $this->belongsTo(Incident::class);
    }

    /**
     * Get type badge class
     */
    public function getTypeBadgeClass(): string
    {
        return match($this->type) {
            self::TYPE_INCIDENT_ASSIGNED => 'badge-info',
            self::TYPE_INCIDENT_UPDATED => 'badge-warning',
            self::TYPE_RESOURCE_ALLOCATED => 'badge-primary',
            self::TYPE_INCIDENT_RESOLVED => 'badge-success',
            default => 'badge-secondary'
        };
    }

    /**
     * Get type icon
     */
    public function getTypeIcon(): string
    {
        return match($this->type) {
            self::TYPE_INCIDENT_ASSIGNED => 'mdi-account-plus',
            self::TYPE_INCIDENT_UPDATED => 'mdi-update',
            self::TYPE_RESOURCE_ALLOCATED => 'mdi-hospital-box',
            self::TYPE_INCIDENT_RESOLVED => 'mdi-check-circle',
            default => 'mdi-bell'
        };
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Get time elapsed
     */
    public function getTimeElapsed(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for recent notifications
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}