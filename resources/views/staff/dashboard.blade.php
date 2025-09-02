@extends('layouts.staff')
@section('content')

<div class="container-fluid">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-end">
                    <a href="{{ route('staff.incidents') }}?tab=report" class="btn btn-primary btn-sm me-2">
                        <i class="mdi mdi-plus me-1"></i> Report Incident
                    </a>
                    <a href="{{ route('staff.incidents') }}" class="btn btn-outline-primary btn-sm">
                        <i class="mdi mdi-format-list-bulleted me-1"></i> View All
                    </a>
                </div>
                <h4 class="page-title">
                    <i class="mdi mdi-view-dashboard me-2"></i>Dashboard
                </h4>
                <p class="text-muted mb-0">Welcome back, {{ $user->name }}!</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary">
                            <i class="mdi mdi-clipboard-list"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="stats-number mb-1">{{ $stats['total_incidents'] }}</h4>
                            <p class="stats-label mb-0">Total Incidents</p>
                            <small class="text-muted">Accessible to you</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-success">
                            <i class="mdi mdi-account-check"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="stats-number mb-1">{{ $stats['assigned_incidents'] }}</h4>
                            <p class="stats-label mb-0">Assigned to Me</p>
                            <small class="text-muted">Active assignments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-warning">
                            <i class="mdi mdi-clock-outline"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="stats-number mb-1">{{ $stats['pending_incidents'] }}</h4>
                            <p class="stats-label mb-0">Pending</p>
                            <small class="text-muted">Awaiting action</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-danger">
                            <i class="mdi mdi-alert-octagon"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="stats-number mb-1">{{ $stats['critical_incidents'] }}</h4>
                            <p class="stats-label mb-0">Critical</p>
                            <small class="text-muted">High priority</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Incidents -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">
                            <i class="mdi mdi-history me-2"></i>Recent Incidents
                        </h5>
                        <a href="{{ route('staff.incidents') }}" class="btn  btn-outline-primary" style="width: 100px;">
                            View All <i class="mdi mdi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @forelse($recentIncidents as $incident)
                        <div class="incident-item-dashboard" data-incident-id="{{ $incident->id }}">
                            <div class="d-flex align-items-center p-3 border-bottom">
                                <div class="severity-badge severity-{{ $incident->severity }} me-3">
                                    @switch($incident->severity)
                                        @case('critical')
                                            <i class="mdi mdi-alert-octagon"></i>
                                            @break
                                        @case('high')
                                            <i class="mdi mdi-alert"></i>
                                            @break
                                        @case('medium')
                                            <i class="mdi mdi-alert-circle"></i>
                                            @break
                                        @default
                                            <i class="mdi mdi-information"></i>
                                    @endswitch
                                </div>
                                
                                <div class="flex-grow-1">
                                    <h6 class="incident-title mb-1">{{ $incident->title }}</h6>
                                    <div class="d-flex align-items-center gap-3 mb-1">
                                        <span class="badge {{ $incident->getStatusBadgeClass() }}">
                                            {{ $incident->getDisplayStatus() }}
                                        </span>
                                        <small class="text-muted">
                                            <i class="mdi mdi-map-marker me-1"></i>{{ $incident->location }}
                                        </small>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <small class="text-muted">
                                            <div class="d-inline-flex align-items-center">
                                                <div class="user-avatar-xs me-1">
                                                    @if($incident->reporter->profile_image)
                                                        <img src="{{ Storage::url($incident->reporter->profile_image) }}" 
                                                             alt="{{ $incident->reporter->name }}" class="avatar-img">
                                                    @else
                                                        <img src="{{ url('empty.svg') }}" 
                                                             alt="{{ $incident->reporter->name }}" class="avatar-img">
                                                    @endif
                                                </div>
                                                {{ $incident->reporter->name }}
                                            </div>
                                        </small>
                                        @if($incident->assignedUser)
                                            <small class="text-success">
                                                <i class="mdi mdi-account-check me-1"></i>{{ $incident->assignedUser->name }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="text-end">
                                    <small class="text-muted d-block">{{ $incident->getTimeElapsed() }}</small>
                                    <a href="{{ route('staff.incidents') }}#incident-{{ $incident->id }}" 
                                       class="btn btn-sm btn-outline-primary mt-1">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="mdi mdi-clipboard-text-outline display-4 text-muted"></i>
                            <p class="text-muted mt-2">No recent incidents found</p>
                            <a href="{{ route('staff.incidents') }}?tab=report" class="btn btn-primary">
                                <i class="mdi mdi-plus me-1"></i> Report First Incident
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions & Summary -->
        <div class="col-xl-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-lightning-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('staff.incidents') }}?tab=report" class="btn btn-primary">
                            <i class="mdi mdi-plus me-2"></i>Report New Incident
                        </a>
                        <a href="{{ route('staff.incidents') }}?tab=assigned" class="btn btn-outline-success">
                            <i class="mdi mdi-account-check me-2"></i>My Assignments
                        </a>
                        <a href="{{ route('staff.incidents') }}" class="btn btn-outline-info">
                            <i class="mdi mdi-format-list-bulleted me-2"></i>All Incidents
                        </a>
                        <a href="{{ route('staff.profile') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-account-cog me-2"></i>Profile Settings
                        </a>
                    </div>
                </div>
            </div>

            <!-- Status Distribution -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-chart-donut me-2"></i>Status Overview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="status-chart">
                        @php
                            $statusCounts = [
                                'pending' => $recentIncidents->where('status', 'pending')->count(),
                                'assigned' => $recentIncidents->where('status', 'assigned')->count(),
                                'in_progress' => $recentIncidents->where('status', 'in_progress')->count(),
                                'resolved' => $recentIncidents->where('status', 'resolved')->count(),
                            ];
                            $total = array_sum($statusCounts);
                        @endphp
                        
                        @if($total > 0)
                            <div class="status-item">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="status-label">
                                        <span class="status-dot bg-warning"></span>Pending
                                    </span>
                                    <span class="status-count">{{ $statusCounts['pending'] }}</span>
                                </div>
                                <div class="progress mb-3" style="height: 6px;">
                                    <div class="progress-bar bg-warning" style="width: {{ $total > 0 ? ($statusCounts['pending'] / $total) * 100 : 0 }}%"></div>
                                </div>
                            </div>

                            <div class="status-item">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="status-label">
                                        <span class="status-dot bg-info"></span>Assigned
                                    </span>
                                    <span class="status-count">{{ $statusCounts['assigned'] }}</span>
                                </div>
                                <div class="progress mb-3" style="height: 6px;">
                                    <div class="progress-bar bg-info" style="width: {{ $total > 0 ? ($statusCounts['assigned'] / $total) * 100 : 0 }}%"></div>
                                </div>
                            </div>

                            <div class="status-item">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="status-label">
                                        <span class="status-dot bg-primary"></span>In Progress
                                    </span>
                                    <span class="status-count">{{ $statusCounts['in_progress'] }}</span>
                                </div>
                                <div class="progress mb-3" style="height: 6px;">
                                    <div class="progress-bar bg-primary" style="width: {{ $total > 0 ? ($statusCounts['in_progress'] / $total) * 100 : 0 }}%"></div>
                                </div>
                            </div>

                            <div class="status-item">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="status-label">
                                        <span class="status-dot bg-success"></span>Resolved
                                    </span>
                                    <span class="status-count">{{ $statusCounts['resolved'] }}</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: {{ $total > 0 ? ($statusCounts['resolved'] / $total) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="mdi mdi-chart-donut text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2 mb-0">No data available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Timeline (if needed) -->
    @if($recentIncidents->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-timeline me-2"></i>Recent Activity
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($recentIncidents->take(5) as $incident)
                            <div class="timeline-item">
                                <div class="timeline-marker severity-{{ $incident->severity }}">
                                    @switch($incident->severity)
                                        @case('critical')
                                            <i class="mdi mdi-alert-octagon"></i>
                                            @break
                                        @case('high')
                                            <i class="mdi mdi-alert"></i>
                                            @break
                                        @case('medium')
                                            <i class="mdi mdi-alert-circle"></i>
                                            @break
                                        @default
                                            <i class="mdi mdi-information"></i>
                                    @endswitch
                                </div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">{{ $incident->title }}</h6>
                                    <p class="timeline-description text-muted mb-1">
                                        Reported by {{ $incident->reporter->name }} at {{ $incident->location }}
                                    </p>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge {{ $incident->getStatusBadgeClass() }}">
                                            {{ $incident->getDisplayStatus() }}
                                        </span>
                                        <small class="text-muted">{{ $incident->getTimeElapsed() }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
/* Stats Cards */
.stats-card {
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.stats-number {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
}

.stats-label {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
}

/* Dashboard Incident Items */
.incident-item-dashboard {
    cursor: pointer;
    transition: all 0.3s ease;
}

.incident-item-dashboard:hover {
    background-color: #f8f9fa;
}

.incident-item-dashboard:last-child .border-bottom {
    border-bottom: none !important;
}

.incident-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.25rem;
}

.user-avatar-xs {
    width: 16px;
    height: 16px;
}

.user-avatar-xs .avatar-img {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    object-fit: cover;
}

/* Severity Badges - Reuse from incidents page */
.severity-badge {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    flex-shrink: 0;
}

.severity-critical { background: linear-gradient(135deg, #d32f2f, #f44336); }
.severity-high { background: linear-gradient(135deg, #f57c00, #ff9800); }
.severity-medium { background: linear-gradient(135deg, #fbc02d, #ffeb3b); color: #333; }
.severity-low { background: linear-gradient(135deg, #388e3c, #4caf50); }

/* Status Chart */
.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 0.5rem;
}

.status-label {
    font-size: 0.85rem;
    font-weight: 500;
    color: #495057;
}

.status-count {
    font-weight: 600;
    color: #2c3e50;
}

/* Timeline */
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 1rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -2rem;
    top: 0;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.9rem;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.timeline-content {
    background: white;
    padding: 1rem;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.timeline-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.timeline-description {
    font-size: 0.85rem;
    line-height: 1.4;
}

/* Card Enhancements */
.card {
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 12px;
}

.card-header {
    background: white;
    border-bottom: 1px solid #f0f0f0;
    border-radius: 12px 12px 0 0 !important;
    padding: 1.25rem 1.5rem;
}

.card-title {
    font-weight: 600;
    color: #2c3e50;
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

.btn-primary:hover {
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
}

/* Badge Styles - Reuse from incidents */
.badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
    border-radius: 6px;
    font-weight: 500;
}

/* Progress bars */
.progress {
    border-radius: 10px;
    background-color: #f8f9fa;
}

.progress-bar {
    border-radius: 10px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .stats-number {
        font-size: 1.5rem;
    }
    
    .stats-icon {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }
    
    .timeline {
        padding-left: 1.5rem;
    }
    
    .timeline-marker {
        left: -1.5rem;
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }
}

/* Animation */
.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Loading states */
.loading-spinner {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add fade-in animation to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('fade-in');
        }, index * 100);
    });

    // Handle incident item clicks
    document.querySelectorAll('.incident-item-dashboard').forEach(item => {
        item.addEventListener('click', function(e) {
            // Don't trigger if clicking on a button or link
            if (e.target.closest('a, button')) return;
            
            const incidentId = this.dataset.incidentId;
            if (incidentId) {
                window.location.href = `{{ route('staff.incidents') }}#incident-${incidentId}`;
            }
        });
    });

    // Auto-refresh dashboard data every 60 seconds
    setInterval(function() {
        // Only refresh if user is not actively interacting
        const activeElement = document.activeElement;
        const isUserInteracting = activeElement && (
            activeElement.tagName === 'INPUT' || 
            activeElement.tagName === 'TEXTAREA' ||
            activeElement.tagName === 'BUTTON'
        );
        
        if (!isUserInteracting) {
            refreshDashboardStats();
        }
    }, 60000); // 60 seconds

    function refreshDashboardStats() {
        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateStatsCards(data.stats);
            }
        })
        .catch(error => {
            console.debug('Dashboard refresh failed:', error);
        });
    }

    function updateStatsCards(stats) {
        // Update stats numbers silently
        const statsElements = {
            'total_incidents': document.querySelector('.stats-number'),
            'assigned_incidents': document.querySelectorAll('.stats-number')[1],
            'pending_incidents': document.querySelectorAll('.stats-number')[2],
            'critical_incidents': document.querySelectorAll('.stats-number')[3]
        };

        Object.keys(statsElements).forEach(key => {
            if (statsElements[key] && stats[key] !== undefined) {
                statsElements[key].textContent = stats[key];
            }
        });
    }
});
</script>

@endsection