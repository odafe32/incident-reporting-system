<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

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

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
     * Get all incidents that have used this resource
     */
    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class, 'current_incident_id');
    }

    /**
     * Get status badge class (Bootstrap 5 compatible)
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_AVAILABLE => 'bg-success text-white',
            self::STATUS_IN_USE => 'bg-warning text-dark',
            self::STATUS_MAINTENANCE => 'bg-danger text-white',
            default => 'bg-secondary text-white'
        };
    }

    /**
     * Get type badge class (Bootstrap 5 compatible)
     */
    public function getTypeBadgeClass(): string
    {
        return match($this->type) {
            self::TYPE_BED => 'bg-primary text-white',
            self::TYPE_EQUIPMENT => 'bg-success text-white',
            self::TYPE_STAFF => 'bg-info text-white',
            default => 'bg-secondary text-white'
        };
    }

    /**
     * Get status icon class
     */
    public function getStatusIconClass(): string
    {
        return match($this->status) {
            self::STATUS_AVAILABLE => 'mdi-check-circle text-success',
            self::STATUS_IN_USE => 'mdi-clock text-warning',
            self::STATUS_MAINTENANCE => 'mdi-wrench text-danger',
            default => 'mdi-help-circle text-secondary'
        };
    }

    /**
     * Get type icon class
     */
    public function getTypeIconClass(): string
    {
        return match($this->type) {
            self::TYPE_BED => 'mdi-bed',
            self::TYPE_EQUIPMENT => 'mdi-medical-bag',
            self::TYPE_STAFF => 'mdi-account',
            default => 'mdi-help-circle'
        };
    }

    /**
     * Get display type
     */
    public function getDisplayType(): string
    {
        return match($this->type) {
            self::TYPE_BED => 'Bed',
            self::TYPE_EQUIPMENT => 'Equipment',
            self::TYPE_STAFF => 'Staff',
            default => ucfirst($this->type)
        };
    }

    /**
     * Get display status
     */
    public function getDisplayStatus(): string
    {
        return match($this->status) {
            self::STATUS_AVAILABLE => 'Available',
            self::STATUS_IN_USE => 'In Use',
            self::STATUS_MAINTENANCE => 'Maintenance',
            default => ucfirst($this->status)
        };
    }

    /**
     * Get status color for UI elements
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            self::STATUS_AVAILABLE => '#28a745',
            self::STATUS_IN_USE => '#ffc107',
            self::STATUS_MAINTENANCE => '#dc3545',
            default => '#6c757d'
        };
    }

    /**
     * Get type color for UI elements
     */
    public function getTypeColor(): string
    {
        return match($this->type) {
            self::TYPE_BED => '#007bff',
            self::TYPE_EQUIPMENT => '#28a745',
            self::TYPE_STAFF => '#17a2b8',
            default => '#6c757d'
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
     * Check if resource is in use
     */
    public function isInUse(): bool
    {
        return $this->status === self::STATUS_IN_USE;
    }

    /**
     * Check if resource is under maintenance
     */
    public function isUnderMaintenance(): bool
    {
        return $this->status === self::STATUS_MAINTENANCE;
    }

    /**
     * Check if resource can be assigned to an incident
     */
    public function canBeAssigned(): bool
    {
        return $this->isAvailable();
    }

    /**
     * Check if resource can be deleted
     */
    public function canBeDeleted(): bool
    {
        return !$this->isInUse();
    }

    /**
     * Check if resource can be edited
     */
    public function canBeEdited(): bool
    {
        return true; // Can always edit, but with restrictions for in-use resources
    }

    /**
     * Mark resource as in use
     */
    public function markAsInUse($incidentId = null): bool
    {
        if (!$this->isAvailable()) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_IN_USE,
            'current_incident_id' => $incidentId,
        ]);
    }

    /**
     * Mark resource as available
     */
    public function markAsAvailable(): bool
    {
        return $this->update([
            'status' => self::STATUS_AVAILABLE,
            'current_incident_id' => null,
        ]);
    }

    /**
     * Mark resource as under maintenance
     */
    public function markAsUnderMaintenance(): bool
    {
        if ($this->isInUse()) {
            return false; // Cannot mark in-use resource for maintenance
        }

        return $this->update([
            'status' => self::STATUS_MAINTENANCE,
            'current_incident_id' => null,
        ]);
    }

    /**
     * Get time since last status change
     */
    public function getTimeSinceLastUpdate(): string
    {
        return $this->updated_at->diffForHumans();
    }

    /**
     * Get formatted creation date
     */
    public function getFormattedCreatedDate(): string
    {
        return $this->created_at->format('M d, Y g:i A');
    }

    /**
     * Get usage duration if currently in use
     */
    public function getUsageDuration(): ?string
    {
        if (!$this->isInUse() || !$this->currentIncident) {
            return null;
        }

        return $this->currentIncident->created_at->diffForHumans(null, true);
    }

    /**
     * Scope for available resources
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * Scope for in-use resources
     */
    public function scopeInUse($query)
    {
        return $query->where('status', self::STATUS_IN_USE);
    }

    /**
     * Scope for maintenance resources
     */
    public function scopeUnderMaintenance($query)
    {
        return $query->where('status', self::STATUS_MAINTENANCE);
    }

    /**
     * Scope for resources by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for beds
     */
    public function scopeBeds($query)
    {
        return $query->where('type', self::TYPE_BED);
    }

    /**
     * Scope for equipment
     */
    public function scopeEquipment($query)
    {
        return $query->where('type', self::TYPE_EQUIPMENT);
    }

    /**
     * Scope for staff
     */
    public function scopeStaff($query)
    {
        return $query->where('type', self::TYPE_STAFF);
    }

    /**
     * Scope for resources by location
     */
    public function scopeAtLocation($query, $location)
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    /**
     * Scope for resources with incidents
     */
    public function scopeWithIncident($query)
    {
        return $query->whereNotNull('current_incident_id');
    }

    /**
     * Scope for resources without incidents
     */
    public function scopeWithoutIncident($query)
    {
        return $query->whereNull('current_incident_id');
    }

    /**
     * Scope for search functionality
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('location', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Get all available resource types
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_BED,
            self::TYPE_EQUIPMENT,
            self::TYPE_STAFF,
        ];
    }

    /**
     * Get all available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_AVAILABLE,
            self::STATUS_IN_USE,
            self::STATUS_MAINTENANCE,
        ];
    }

    /**
     * Get type options for forms
     */
    public static function getTypeOptions(): array
    {
        return [
            self::TYPE_BED => 'Bed',
            self::TYPE_EQUIPMENT => 'Equipment',
            self::TYPE_STAFF => 'Staff',
        ];
    }

    /**
     * Get status options for forms
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_AVAILABLE => 'Available',
            self::STATUS_IN_USE => 'In Use',
            self::STATUS_MAINTENANCE => 'Maintenance',
        ];
    }

    /**
     * Get validation rules for resource creation/update
     */
    public static function getValidationRules($isUpdate = false): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', self::getTypes()),
            'location' => 'required|string|max:255',
            'status' => 'required|in:' . implode(',', self::getStatuses()),
            'description' => 'nullable|string|max:1000',
        ];

        if (!$isUpdate) {
            $rules['name'] .= '|unique:resources,name';
        }

        return $rules;
    }

    /**
     * Get resource statistics
     */
    public static function getStatistics(): array
    {
        return [
            'total' => self::count(),
            'available' => self::available()->count(),
            'in_use' => self::inUse()->count(),
            'maintenance' => self::underMaintenance()->count(),
            'beds' => self::beds()->count(),
            'equipment' => self::equipment()->count(),
            'staff' => self::staff()->count(),
        ];
    }

    /**
     * Get resources grouped by type
     */
    public static function getGroupedByType(): array
    {
        return [
            'beds' => self::beds()->get(),
            'equipment' => self::equipment()->get(),
            'staff' => self::staff()->get(),
        ];
    }

    /**
     * Get resources grouped by status
     */
    public static function getGroupedByStatus(): array
    {
        return [
            'available' => self::available()->get(),
            'in_use' => self::inUse()->get(),
            'maintenance' => self::underMaintenance()->get(),
        ];
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically set status to available when creating new resource
        static::creating(function ($resource) {
            if (!$resource->status) {
                $resource->status = self::STATUS_AVAILABLE;
            }
        });

        // Clear incident association when marking as available
        static::updating(function ($resource) {
            if ($resource->status === self::STATUS_AVAILABLE && $resource->isDirty('status')) {
                $resource->current_incident_id = null;
            }
        });
    }
}