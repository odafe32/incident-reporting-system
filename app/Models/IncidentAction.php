<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncidentAction extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'incident_id',
        'user_id',
        'action_type',
        'message',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Action type constants
    const TYPE_MESSAGE = 'message';
    const TYPE_ASSIGNMENT = 'assignment';
    const TYPE_RESOURCE_ALLOCATION = 'resource_allocation';
    const TYPE_STATUS_CHANGE = 'status_change';
    const TYPE_RESOLUTION = 'resolution';

    /**
     * Get the incident this action belongs to
     */
    public function incident(): BelongsTo
    {
        return $this->belongsTo(Incident::class);
    }

    /**
     * Get the user who performed this action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get action type badge class
     */
    public function getActionTypeBadgeClass(): string
    {
        return match($this->action_type) {
            self::TYPE_MESSAGE => 'badge-secondary',
            self::TYPE_ASSIGNMENT => 'badge-info',
            self::TYPE_RESOURCE_ALLOCATION => 'badge-warning',
            self::TYPE_STATUS_CHANGE => 'badge-primary',
            self::TYPE_RESOLUTION => 'badge-success',
            default => 'badge-light'
        };
    }

    /**
     * Get display action type
     */
    public function getDisplayActionType(): string
    {
        return match($this->action_type) {
            self::TYPE_MESSAGE => 'Message',
            self::TYPE_ASSIGNMENT => 'Assignment',
            self::TYPE_RESOURCE_ALLOCATION => 'Resource Allocation',
            self::TYPE_STATUS_CHANGE => 'Status Change',
            self::TYPE_RESOLUTION => 'Resolution',
            default => 'Action'
        };
    }

    /**
     * Check if this is a system action (not a user message)
     */
    public function isSystemAction(): bool
    {
        return in_array($this->action_type, [
            self::TYPE_ASSIGNMENT,
            self::TYPE_RESOURCE_ALLOCATION,
            self::TYPE_STATUS_CHANGE,
            self::TYPE_RESOLUTION
        ]);
    }

    /**
     * Get formatted time
     */
    public function getFormattedTime(): string
    {
        return $this->created_at->format('g:i A');
    }

    /**
     * Get time elapsed
     */
    public function getTimeElapsed(): string
    {
        return $this->created_at->diffForHumans();
    }
}