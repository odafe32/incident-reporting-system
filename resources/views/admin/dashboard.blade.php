@extends('layouts.admin')
@section('content')

<div class="container-fluid">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-end">
                    <div class="btn-group">
                        <button class="btn btn-primary btn-sm" onclick="refreshDashboard()">
                            <i class="mdi mdi-refresh me-1"></i> Refresh
                        </button>
                        <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="mdi mdi-plus me-1"></i> Quick Add
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.create-resources') }}">
                                    <i class="mdi mdi-hospital-box me-2"></i>Add Resource
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.users.create') }}">
                                    <i class="mdi mdi-account-plus me-2"></i>Add User
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.incidents') }}">
                                    <i class="mdi mdi-alert-circle me-2"></i>View Incidents
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <h4 class="page-title">
                    <i class="mdi mdi-view-dashboard me-2"></i>Dashboard
                </h4>
                <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}! Here's what's happening today.</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-primary-subtle">
                                <span class="avatar-title bg-primary rounded-circle">
                                    <i class="mdi mdi-alert-circle font-24 text-white"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-15 text-muted">Total Incidents</h6>
                            <h3 class="my-1 text-primary" id="totalIncidents">{{ $stats['total_incidents'] ?? 0 }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="badge bg-success-subtle text-success me-1">
                                    <i class="mdi mdi-arrow-up"></i> {{ $stats['incidents_change'] ?? '+0' }}%
                                </span>
                                <span class="font-12">vs last month</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-warning-subtle">
                                <span class="avatar-title bg-warning rounded-circle">
                                    <i class="mdi mdi-clock-alert font-24 text-white"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-15 text-muted">Pending Incidents</h6>
                            <h3 class="my-1 text-warning" id="pendingIncidents">{{ $stats['pending_incidents'] ?? 0 }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="badge bg-danger-subtle text-danger me-1">
                                    <i class="mdi mdi-alert"></i> Needs attention
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-success-subtle">
                                <span class="avatar-title bg-success rounded-circle">
                                    <i class="mdi mdi-hospital-box font-24 text-white"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-15 text-muted">Available Resources</h6>
                            <h3 class="my-1 text-success" id="availableResources">{{ $stats['available_resources'] ?? 0 }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="font-12">Out of {{ $stats['total_resources'] ?? 0 }} total</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-lg rounded-circle bg-info-subtle">
                                <span class="avatar-title bg-info rounded-circle">
                                    <i class="mdi mdi-account-group font-24 text-white"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-15 text-muted">Active Staff</h6>
                            <h3 class="my-1 text-info" id="activeStaff">{{ $stats['active_staff'] ?? 0 }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="font-12">{{ $stats['online_staff'] ?? 0 }} online now</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Critical Alerts -->
    @if(isset($criticalIncidents) && $criticalIncidents->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="mdi mdi-alert-octagon me-2"></i>
                <strong>Critical Alert!</strong> You have {{ $criticalIncidents->count() }} critical incident(s) requiring immediate attention.
                <a href="{{ route('admin.incidents') }}?filter=critical" class="alert-link">View Critical Incidents</a>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Dashboard Content -->
    <div class="row">
        <!-- Recent Incidents -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-alert-circle me-2"></i>Recent Incidents
                    </h5>
                    <a href="{{ route('admin.incidents') }}" class="btn btn-sm btn-outline-primary">
                        View All <i class="mdi mdi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($recentIncidents) && $recentIncidents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Incident</th>
                                        <th>Severity</th>
                                        <th>Status</th>
                                        <th>Reporter</th>
                                        <th>Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentIncidents as $incident)
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-1 font-14">{{ Str::limit($incident->title, 40) }}</h6>
                                                <small class="text-muted">
                                                    <i class="mdi mdi-map-marker me-1"></i>{{ $incident->location }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge severity-{{ $incident->severity }}">
                                                {{ ucfirst($incident->severity) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $incident->getStatusBadgeClass() }}">
                                                {{ $incident->getDisplayStatus() }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    @if($incident->reporter->profile_image)
                                                        <img src="{{ Storage::url($incident->reporter->profile_image) }}" 
                                                             alt="{{ $incident->reporter->name }}" 
                                                             class="avatar-img rounded-circle">
                                                    @else
                                                        <span class="avatar-title bg-primary rounded-circle font-12">
                                                            {{ substr($incident->reporter->name, 0, 2) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 font-13">{{ $incident->reporter->name }}</h6>
                                                    <small class="text-muted">{{ $incident->reporter->getDisplayRole() }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $incident->getTimeElapsed() }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.incidents') }}?incident={{ $incident->id }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="mdi mdi-clipboard-check-outline display-4 text-muted"></i>
                            <h6 class="mt-2 text-muted">No recent incidents</h6>
                            <p class="text-muted">All incidents are up to date!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions & Resource Status -->
        <div class="col-xl-4">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-lightning-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.incidents') }}" class="btn btn-outline-primary">
                            <i class="mdi mdi-alert-circle me-2"></i>Manage Incidents
                        </a>
                        <a href="{{ route('admin.create-resources') }}" class="btn btn-outline-success">
                            <i class="mdi mdi-plus-circle me-2"></i>Add Resources
                        </a>
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-info">
                            <i class="mdi mdi-account-group me-2"></i>Manage Users
                        </a>
                        <a href="{{ route('admin.resources') }}" class="btn btn-outline-warning">
                            <i class="mdi mdi-hospital-box me-2"></i>View Resources
                        </a>
                    </div>
                </div>
            </div>

            <!-- Resource Status Overview -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-hospital-box me-2"></i>Resource Status
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($resourceStats))
                        <div class="resource-stats">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Beds</span>
                                <div>
                                    <span class="badge bg-success me-1">{{ $resourceStats['beds']['available'] ?? 0 }}</span>
                                    <span class="text-muted">/ {{ $resourceStats['beds']['total'] ?? 0 }}</span>
                                </div>
                            </div>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar bg-success" 
                                     style="width: {{ $resourceStats['beds']['total'] > 0 ? ($resourceStats['beds']['available'] / $resourceStats['beds']['total']) * 100 : 0 }}%"></div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Equipment</span>
                                <div>
                                    <span class="badge bg-info me-1">{{ $resourceStats['equipment']['available'] ?? 0 }}</span>
                                    <span class="text-muted">/ {{ $resourceStats['equipment']['total'] ?? 0 }}</span>
                                </div>
                            </div>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar bg-info" 
                                     style="width: {{ $resourceStats['equipment']['total'] > 0 ? ($resourceStats['equipment']['available'] / $resourceStats['equipment']['total']) * 100 : 0 }}%"></div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Staff</span>
                                <div>
                                    <span class="badge bg-primary me-1">{{ $resourceStats['staff']['available'] ?? 0 }}</span>
                                    <span class="text-muted">/ {{ $resourceStats['staff']['total'] ?? 0 }}</span>
                                </div>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-primary" 
                                     style="width: {{ $resourceStats['staff']['total'] > 0 ? ($resourceStats['staff']['available'] / $resourceStats['staff']['total']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="mdi mdi-hospital-box-outline display-4 text-muted"></i>
                            <p class="text-muted mt-2">No resource data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Incident Trends Chart -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-chart-line me-2"></i>Incident Trends (Last 7 Days)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="incidentTrendsChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Severity Distribution -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-chart-donut me-2"></i>Severity Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="severityChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-history me-2"></i>Recent Activities
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($recentActivities) && $recentActivities->count() > 0)
                        <div class="activity-timeline">
                            @foreach($recentActivities as $activity)
                            <div class="activity-item">
                                <div class="activity-icon bg-{{ $activity['type'] ?? 'primary' }}">
                                    <i class="mdi {{ $activity['icon'] ?? 'mdi-information' }}"></i>
                                </div>
                                <div class="activity-content">
                                    <h6 class="mb-1">{{ $activity['title'] ?? 'Activity' }}</h6>
                                    <p class="text-muted mb-1">{{ $activity['description'] ?? 'No description' }}</p>
                                    <small class="text-muted">{{ $activity['time'] ?? 'Unknown time' }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="mdi mdi-history display-4 text-muted"></i>
                            <h6 class="mt-2 text-muted">No recent activities</h6>
                            <p class="text-muted">Activities will appear here as they happen</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Stats Cards */
.stats-card {
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

.avatar-lg {
    width: 64px;
    height: 64px;
}

.avatar-sm {
    width: 32px;
    height: 32px;
}

.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    font-weight: 600;
}

/* Severity Badges */
.severity-critical {
    background-color: #dc3545 !important;
    color: white !important;
}

.severity-high {
    background-color: #fd7e14 !important;
    color: white !important;
}

.severity-medium {
    background-color: #ffc107 !important;
    color: #212529 !important;
}

.severity-low {
    background-color: #28a745 !important;
    color: white !important;
}

/* Resource Stats */
.resource-stats .progress {
    border-radius: 10px;
}

.resource-stats .progress-bar {
    border-radius: 10px;
}

/* Activity Timeline */
.activity-timeline {
    position: relative;
    padding-left: 30px;
}

.activity-timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.activity-item {
    position: relative;
    margin-bottom: 20px;
    display: flex;
    align-items: flex-start;
}

.activity-icon {
    position: absolute;
    left: -22px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    z-index: 1;
}

.activity-content {
    flex: 1;
    margin-left: 20px;
}

/* Table Enhancements */
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

/* Card Enhancements */
.card {
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border-radius: 12px;
}

.card-header {
    background: transparent;
    border-bottom: 1px solid #f0f0f0;
    padding: 1.25rem 1.5rem;
}

.card-body {
    padding: 1.5rem;
}

/* Button Enhancements */
.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

/* Badge Enhancements */
.badge {
    font-weight: 500;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .activity-timeline {
        padding-left: 20px;
    }
    
    .activity-icon {
        left: -16px;
        width: 24px;
        height: 24px;
        font-size: 12px;
    }
    
    .activity-content {
        margin-left: 15px;
    }
}

/* Loading States */
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
}

.loading-spinner {
    width: 2rem;
    height: 2rem;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    initializeCharts();
    
    // Auto-refresh dashboard every 5 minutes
    setInterval(refreshDashboardData, 300000);
});

function initializeCharts() {
    // Incident Trends Chart
    const trendsCtx = document.getElementById('incidentTrendsChart');
    if (trendsCtx) {
        new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['trends']['labels'] ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']) !!},
                datasets: [{
                    label: 'Incidents',
                    data: {!! json_encode($chartData['trends']['data'] ?? [12, 19, 3, 5, 2, 3, 9]) !!},
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f0f0f0'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Severity Distribution Chart
    const severityCtx = document.getElementById('severityChart');
    if (severityCtx) {
        new Chart(severityCtx, {
            type: 'doughnut',
            data: {
                labels: ['Critical', 'High', 'Medium', 'Low'],
                datasets: [{
                    data: {!! json_encode($chartData['severity'] ?? [5, 15, 25, 35]) !!},
                    backgroundColor: ['#dc3545', '#fd7e14', '#ffc107', '#28a745'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }
}

function refreshDashboard() {
    // Add loading overlay
    const container = document.querySelector('.container-fluid');
    const loadingOverlay = document.createElement('div');
    loadingOverlay.className = 'loading-overlay';
    loadingOverlay.innerHTML = '<div class="loading-spinner"></div>';
    container.appendChild(loadingOverlay);
    
    // Refresh page after a short delay
    setTimeout(() => {
        location.reload();
    }, 1000);
}

function refreshDashboardData() {
    // Fetch updated dashboard data via AJAX
    fetch('/admin/dashboard/data', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateDashboardStats(data.stats);
        }
    })
    .catch(error => {
        console.debug('Dashboard refresh failed:', error);
    });
}

function updateDashboardStats(stats) {
    // Update stat counters
    if (stats.total_incidents !== undefined) {
        document.getElementById('totalIncidents').textContent = stats.total_incidents;
    }
    if (stats.pending_incidents !== undefined) {
        document.getElementById('pendingIncidents').textContent = stats.pending_incidents;
    }
    if (stats.available_resources !== undefined) {
        document.getElementById('availableResources').textContent = stats.available_resources;
    }
    if (stats.active_staff !== undefined) {
        document.getElementById('activeStaff').textContent = stats.active_staff;
    }
}

// Utility function to show alerts
function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 
                      type === 'error' ? 'alert-danger' : 
                      type === 'warning' ? 'alert-warning' : 'alert-info';
    
    const iconClass = type === 'success' ? 'mdi-check-circle' : 
                     type === 'error' ? 'mdi-alert-circle' : 
                     type === 'warning' ? 'mdi-alert-triangle' : 'mdi-information';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="mdi ${iconClass} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 5000);
}
</script>
@endsection