<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Incident extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'title',
        'description',
        'reported_by',
        'assigned_to',
        'patient_id',
        'severity',
        'status',
        'location',
        'resources_allocated',
    ];

    protected $casts = [
        'resources_allocated' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Severity constants
    const SEVERITY_LOW = 'low';
    const SEVERITY_MEDIUM = 'medium';
    const SEVERITY_HIGH = 'high';
    const SEVERITY_CRITICAL = 'critical';

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_ASSIGNED = 'assigned';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_RESOLVED = 'resolved';

    /**
     * Get the user who reported this incident
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Get the user assigned to this incident
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get all actions for this incident
     */
    public function actions(): HasMany
    {
        return $this->hasMany(IncidentAction::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get notifications for this incident
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get severity badge class
     */
    public function getSeverityBadgeClass(): string
    {
        return match($this->severity) {
            self::SEVERITY_LOW => 'badge-success',
            self::SEVERITY_MEDIUM => 'badge-warning',
            self::SEVERITY_HIGH => 'badge-danger',
            self::SEVERITY_CRITICAL => 'badge-dark',
            default => 'badge-secondary'
        };
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'badge-warning',
            self::STATUS_ASSIGNED => 'badge-info',
            self::STATUS_IN_PROGRESS => 'badge-primary',
            self::STATUS_RESOLVED => 'badge-success',
            default => 'badge-secondary'
        };
    }

    /**
     * Get display severity
     */
    public function getDisplaySeverity(): string
    {
        return ucfirst($this->severity);
    }

    /**
     * Get display status
     */
    public function getDisplayStatus(): string
    {
        return match($this->status) {
            self::STATUS_IN_PROGRESS => 'In Progress',
            default => ucfirst($this->status)
        };
    }

    /**
     * Check if incident is resolved
     */
    public function isResolved(): bool
    {
        return $this->status === self::STATUS_RESOLVED;
    }

    /**
     * Check if incident is critical
     */
    public function isCritical(): bool
    {
        return $this->severity === self::SEVERITY_CRITICAL;
    }

    /**
     * Get time elapsed since creation
     */
    public function getTimeElapsed(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Scope for pending incidents
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for assigned incidents
     */
    public function scopeAssigned($query)
    {
        return $query->where('status', self::STATUS_ASSIGNED);
    }

    /**
     * Scope for critical incidents
     */
    public function scopeCritical($query)
    {
        return $query->where('severity', self::SEVERITY_CRITICAL);
    }

    /**
     * Scope for incidents assigned to a user
     */
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }
}