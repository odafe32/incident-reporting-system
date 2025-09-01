<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resource extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'type',
        'status',
        'location',
        'description',
        'current_incident_id',
    ];

    // Type constants
    const TYPE_BED = 'bed';
    const TYPE_EQUIPMENT = 'equipment';
    const TYPE_STAFF = 'staff';

    // Status constants
    const STATUS_AVAILABLE = 'available';
    const STATUS_IN_USE = 'in_use';
    const STATUS_MAINTENANCE = 'maintenance';

    /**
     * Get the incident currently using this resource
     */
    public function currentIncident(): BelongsTo
    {
        return $this->belongsTo(Incident::class, 'current_incident_id');
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_AVAILABLE => 'badge-success',
            self::STATUS_IN_USE => 'badge-warning',
            self::STATUS_MAINTENANCE => 'badge-danger',
            default => 'badge-secondary'
        };
    }

    /**
     * Get type badge class
     */
    public function getTypeBadgeClass(): string
    {
        return match($this->type) {
            self::TYPE_BED => 'badge-primary',
            self::TYPE_EQUIPMENT => 'badge-info',
            self::TYPE_STAFF => 'badge-success',
            default => 'badge-secondary'
        };
    }

    /**
     * Get display type
     */
    public function getDisplayType(): string
    {
        return ucfirst($this->type);
    }

    /**
     * Get display status
     */
    public function getDisplayStatus(): string
    {
        return match($this->status) {
            self::STATUS_IN_USE => 'In Use',
            default => ucfirst($this->status)
        };
    }

    /**
     * Check if resource is available
     */
    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    /**
     * Mark resource as in use
     */
    public function markAsInUse($incidentId = null): void
    {
        $this->update([
            'status' => self::STATUS_IN_USE,
            'current_incident_id' => $incidentId,
        ]);
    }

    /**
     * Mark resource as available
     */
    public function markAsAvailable(): void
    {
        $this->update([
            'status' => self::STATUS_AVAILABLE,
            'current_incident_id' => null,
        ]);
    }

    /**
     * Scope for available resources
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * Scope for resources by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}