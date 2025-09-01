<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Role constants
     */
    const ROLE_ADMIN = 'admin';
    const ROLE_DOCTOR = 'doctor';
    const ROLE_NURSE = 'nurse';
    const ROLE_STAFF = 'staff';

    /**
     * Department constants
     */
    const DEPT_ICU = 'ICU';
    const DEPT_SURGERY = 'Surgery';
    const DEPT_EMERGENCY = 'Emergency';
    const DEPT_CARDIOLOGY = 'Cardiology';
    const DEPT_PEDIATRICS = 'Pediatrics';
    const DEPT_ONCOLOGY = 'Oncology';

    /**
     * Get incidents reported by this user
     */
    public function reportedIncidents(): HasMany
    {
        return $this->hasMany(Incident::class, 'reported_by');
    }

    /**
     * Get incidents assigned to this user
     */
    public function assignedIncidents(): HasMany
    {
        return $this->hasMany(Incident::class, 'assigned_to');
    }

    /**
     * Get incident actions by this user
     */
    public function incidentActions(): HasMany
    {
        return $this->hasMany(IncidentAction::class);
    }

    /**
     * Get notifications for this user
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadNotificationsCount(): int
    {
        return $this->notifications()->unread()->count();
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is doctor
     */
    public function isDoctor(): bool
    {
        return $this->role === self::ROLE_DOCTOR;
    }

    /**
     * Check if user is nurse
     */
    public function isNurse(): bool
    {
        return $this->role === self::ROLE_NURSE;
    }

    /**
     * Check if user is staff
     */
    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    /**
     * Check if user is staff member (doctor, nurse, or staff)
     * This is used for routing purposes
     */
    public function isStaffMember(): bool
    {
        return in_array($this->role, [self::ROLE_DOCTOR, self::ROLE_NURSE, self::ROLE_STAFF]);
    }

    /**
     * Get user's display role (for UI purposes)
     */
    public function getDisplayRole(): string
    {
        return match($this->role) {
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_DOCTOR => 'Doctor',
            self::ROLE_NURSE => 'Nurse',
            self::ROLE_STAFF => 'Staff Member',
            default => 'Unknown'
        };
    }

    /**
     * Get role badge class for UI
     */
    public function getRoleBadgeClass(): string
    {
        return match($this->role) {
            self::ROLE_ADMIN => 'badge-danger',
            self::ROLE_DOCTOR => 'badge-primary',
            self::ROLE_NURSE => 'badge-success',
            self::ROLE_STAFF => 'badge-info',
            default => 'badge-secondary'
        };
    }

    /**
     * Get all available roles
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_DOCTOR,
            self::ROLE_NURSE,
            self::ROLE_STAFF,
        ];
    }

    /**
     * Get staff roles (non-admin roles)
     */
    public static function getStaffRoles(): array
    {
        return [
            self::ROLE_DOCTOR,
            self::ROLE_NURSE,
            self::ROLE_STAFF,
        ];
    }

    /**
     * Get all available departments
     */
    public static function getDepartments(): array
    {
        return [
            self::DEPT_ICU,
            self::DEPT_SURGERY,
            self::DEPT_EMERGENCY,
            self::DEPT_CARDIOLOGY,
            self::DEPT_PEDIATRICS,
            self::DEPT_ONCOLOGY,
        ];
    }

    /**
     * Get departments as key-value pairs for select options
     */
    public static function getDepartmentOptions(): array
    {
        return [
            self::DEPT_ICU => 'Intensive Care Unit (ICU)',
            self::DEPT_SURGERY => 'Surgery Department',
            self::DEPT_EMERGENCY => 'Emergency Department',
            self::DEPT_CARDIOLOGY => 'Cardiology Department',
            self::DEPT_PEDIATRICS => 'Pediatrics Department',
            self::DEPT_ONCOLOGY => 'Oncology Department',
        ];
    }

    /**
     * Get role options for forms
     */
    public static function getRoleOptions(): array
    {
        return [
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_DOCTOR => 'Doctor',
            self::ROLE_NURSE => 'Nurse',
            self::ROLE_STAFF => 'Staff Member',
        ];
    }



public function unreadNotifications()
{
    return $this->hasMany(Notification::class)->unread();
}


public function getRecentNotifications($limit = 10)
{
    return $this->notifications()->with('incident')->take($limit)->get();
}
}

