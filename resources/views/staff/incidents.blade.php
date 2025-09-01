@extends('layouts.staff')
@section('content')

<div class="container-fluid">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-end">
                    <button class="btn btn-primary btn-sm" onclick="switchToReportTab()">
                        <i class="mdi mdi-plus me-1"></i> Quick Report
                    </button>
                </div>
                <h4 class="page-title">
                    <i class="mdi mdi-hospital-building me-2"></i>Incident Management
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Left Sidebar - Incident List -->
                        <div class="col-lg-4 col-xl-3">
                            <div class="incident-sidebar">
                                <!-- Tabs Navigation -->
                                <div class="incident-tabs">
                                    <ul class="nav nav-pills nav-justified" id="incidentTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="all-incidents-tab" data-bs-toggle="pill" 
                                                    data-bs-target="#all-incidents" type="button" role="tab">
                                                <div class="tab-content-wrapper">
                                                    <i class="mdi mdi-format-list-bulleted"></i>
                                                    <span class="tab-text">All Incidents</span>
                                                    <span class="badge bg-primary">{{ $allIncidents->count() }}</span>
                                                </div>
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="report-incident-tab" data-bs-toggle="pill" 
                                                    data-bs-target="#report-incident" type="button" role="tab">
                                                <div class="tab-content-wrapper">
                                                    <i class="mdi mdi-plus-circle"></i>
                                                    <span class="tab-text">Report New</span>
                                                </div>
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="assigned-incidents-tab" data-bs-toggle="pill" 
                                                    data-bs-target="#assigned-incidents" type="button" role="tab">
                                                <div class="tab-content-wrapper">
                                                    <i class="mdi mdi-account-check"></i>
                                                    <span class="tab-text">Assigned to Me</span>
                                                    <span class="badge bg-success">{{ $assignedIncidents->count() }}</span>
                                                </div>
                                            </button>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Search Bar -->
                                <div class="incident-search">
                                    <div class="position-relative">
                                        <input type="text" id="incident-search" class="form-control" 
                                               placeholder="Search incidents...">
                                        <i class="mdi mdi-magnify search-icon"></i>
                                    </div>
                                </div>

                                <!-- Incident List -->
                                <div class="incident-list" data-simplebar style="max-height: calc(100vh - 200px);">
                                    <div class="tab-content" id="incidentTabContent">
                                        <!-- All Incidents Tab -->
                                        <div class="tab-pane fade show active" id="all-incidents">
                                            @forelse($allIncidents as $incident)
                                                <div class="incident-item" data-incident-id="{{ $incident->id }}">
                                                    <div class="incident-card">
                                                        <div class="incident-header">
                                                            <div class="severity-badge severity-{{ $incident->severity }}">
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
                                                            <div class="incident-status">
                                                                <span class="badge {{ $incident->getStatusBadgeClass() }}" style="color:#0b51b7;">
                                                                    {{ $incident->getDisplayStatus() }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="incident-content">
                                                            <h6 class="incident-title">{{ $incident->title }}</h6>
                                                            <div class="incident-meta">
                                                                <div class="meta-item">
                                                                    <i class="mdi mdi-map-marker text-muted"></i>
                                                                    <span>{{ $incident->location }}</span>
                                                                </div>
                                                                <div class="meta-item">
                                                                    <div class="user-avatar-sm">
                                                                        @if($incident->reporter->profile_image)
                                                                            <img src="{{ Storage::url($incident->reporter->profile_image) }}" 
                                                                                 alt="{{ $incident->reporter->name }}" class="avatar-img">
                                                                        @else
                                                                            <img src="{{ url('empty.svg') }}" 
                                                                                 alt="{{ $incident->reporter->name }}" class="avatar-img">
                                                                        @endif
                                                                    </div>
                                                                    <span>{{ $incident->reporter->name }}</span>
                                                                </div>
                                                                @if($incident->assignedUser)
                                                                    <div class="meta-item assigned">
                                                                        <i class="mdi mdi-account-check text-success"></i>
                                                                        <span>{{ $incident->assignedUser->name }}</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="incident-time">
                                                                {{ $incident->getTimeElapsed() }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="empty-state">
                                                    <i class="mdi mdi-clipboard-text-outline"></i>
                                                    <p>No incidents found</p>
                                                </div>
                                            @endforelse
                                        </div>

                                        <!-- Report Incident Tab -->
                                        <div class="tab-pane fade" id="report-incident">
                                            <div class="report-form-container">
                                                <div class="form-header">
                                                    <h5><i class="mdi mdi-plus-circle me-2"></i>Report New Incident</h5>
                                                    <p class="text-muted">Describe the incident in detail</p>
                                                </div>
                                                
                                                <form id="reportIncidentForm" class="report-form">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label class="form-label">
                                                            <i class="mdi mdi-alert-circle me-1"></i>Severity Level
                                                        </label>
                                                        <select name="severity" class="form-select" required>
                                                            <option value="">Select severity</option>
                                                            <option value="low">🟢 Low - Minor issue</option>
                                                            <option value="medium">🟡 Medium - Moderate concern</option>
                                                            <option value="high">🟠 High - Urgent attention</option>
                                                            <option value="critical">🔴 Critical - Emergency</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="form-label">
                                                            <i class="mdi mdi-map-marker me-1"></i>Location
                                                        </label>
                                                        <input type="text" name="location" class="form-control" 
                                                               placeholder="e.g., ICU Ward A, Room 205" required>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="form-label">
                                                            <i class="mdi mdi-text me-1"></i>Incident Description
                                                        </label>
                                                        <textarea name="message" class="form-control" rows="4" 
                                                                  placeholder="Describe what happened in detail..." required></textarea>
                                                    </div>
                                                    
                                                    <button type="submit" class="btn btn-primary w-100 btn-report">
                                                        <i class="mdi mdi-send me-1"></i>Report Incident
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Assigned Incidents Tab -->
                                        <div class="tab-pane fade" id="assigned-incidents">
                                            @forelse($assignedIncidents as $incident)
                                                <div class="incident-item" data-incident-id="{{ $incident->id }}">
                                                    <div class="incident-card assigned-card">
                                                        <div class="incident-header">
                                                            <div class="severity-badge severity-{{ $incident->severity }}">
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
                                                            <div class="incident-status">
                                                                <span class="badge {{ $incident->getStatusBadgeClass() }}" style="color: #0b51b7">
                                                                    {{ $incident->getDisplayStatus() }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="incident-content">
                                                            <h6 class="incident-title">{{ $incident->title }}</h6>
                                                            <div class="incident-meta">
                                                                <div class="meta-item">
                                                                    <i class="mdi mdi-map-marker text-muted"></i>
                                                                    <span>{{ $incident->location }}</span>
                                                                </div>
                                                                <div class="meta-item">
                                                                    <div class="user-avatar-sm">
                                                                        @if($incident->reporter->profile_image)
                                                                            <img src="{{ Storage::url($incident->reporter->profile_image) }}" 
                                                                                 alt="{{ $incident->reporter->name }}" class="avatar-img">
                                                                        @else
                                                                            <img src="{{ url('empty.svg') }}" 
                                                                                 alt="{{ $incident->reporter->name }}" class="avatar-img">
                                                                        @endif
                                                                    </div>
                                                                    <span>Reported by {{ $incident->reporter->name }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="incident-time">
                                                                {{ $incident->getTimeElapsed() }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="empty-state">
                                                    <i class="mdi mdi-account-check-outline"></i>
                                                    <p>No incidents assigned to you</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side - Chat Interface -->
                        <div class="col-lg-8 col-xl-9">
                            <div class="chat-container" id="chatContainer">
                                <!-- Welcome State -->
                                <div class="chat-welcome" id="chatWelcome">
                                    <div class="welcome-content">
                                        <div class="welcome-icon">
                                            <i class="mdi mdi-hospital-building" style="color: black;"></i>
                                        </div>
                                        <h4 style="color: black;">Incident Management System</h4>
                                        <p class="text-muted">Select an incident from the left to view details and chat history</p>
                                        <div class="welcome-stats">
                                            <div class="stat-item">
                                                <div class="stat-number" style="color: #007bff;">{{ $allIncidents->count() }}</div>
                                                <div class="stat-label" style="color: #6c757d;">Total Incidents</div>
                                            </div>
                                            <div class="stat-item">
                                                <div class="stat-number" style="color: #007bff;">{{ $assignedIncidents->count() }}</div>
                                                <div class="stat-label" style="color: #6c757d;">Assigned to You</div>
                                            </div>
                                            <div class="stat-item">
                                                <div class="stat-number" style="color: #007bff;">{{ $allIncidents->where('status', 'pending')->count() }}</div>
                                                <div class="stat-label" style="color: #6c757d;">Pending</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Chat Interface -->
                                <div class="chat-interface" id="chatInterface" style="display: none;">
                                    <!-- Chat Header -->
                                    <div class="chat-header" id="chatHeader">
                                        <!-- Dynamic content -->
                                    </div>
                                    
                                    <!-- Chat Messages -->
                                    <div class="chat-messages" id="chatMessages" data-simplebar>
                                        <!-- Dynamic content -->
                                    </div>
                                    
                                    <!-- Chat Input - Fixed at bottom -->
                                    <div class="chat-input-container" id="chatInputContainer">
                                        <div class="chat-input" id="chatInput">
                                            <!-- Dynamic content -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
/* Main Container Styles */
.incident-sidebar {
    background: #f8f9fa;
    border-right: 1px solid #e9ecef;
    height: calc(100vh - 120px);
    display: flex;
    flex-direction: column;
}

/* Improved Tabs Styling */
.incident-tabs {
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.incident-tabs .nav-pills {
    background: #e9ecef;
    border-radius: 10px;
    padding: 0.25rem;
}

.incident-tabs .nav-pills .nav-link {
    border-radius: 8px;
    padding: 0.75rem 0.5rem;
    font-size: 0.8rem;
    font-weight: 500;
    color: #6c757d;
    border: none;
    background: transparent;
    transition: all 0.3s ease;
    min-height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.tab-content-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
    text-align: center;
}

.tab-content-wrapper i {
    font-size: 1.2rem;
}

.tab-text {
    font-size: 0.75rem;
    line-height: 1.2;
    white-space: nowrap;
}

.incident-tabs .nav-pills .nav-link:hover {
    background-color: rgba(255,255,255,0.7);
    color: #495057;
}

.incident-tabs .nav-pills .nav-link.active {
    background-color: #007bff;
    color: white;
    box-shadow: 0 2px 8px rgba(0,123,255,0.3);
}

.incident-tabs .badge {
    font-size: 0.65rem;
    padding: 0.2rem 0.4rem;
    border-radius: 10px;
}

/* Search Bar */
.incident-search {
    padding: 0 1rem 1rem;
    border-bottom: 1px solid #e9ecef;
}

.incident-search .position-relative input {
    padding-left: 2.5rem;
    border-radius: 20px;
    border: 1px solid #dee2e6;
    font-size: 0.875rem;
}

.incident-search .search-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    font-size: 1rem;
}

/* Incident List */
.incident-list {
    flex: 1;
    overflow-y: auto;
}

.incident-item {
    cursor: pointer;
    transition: all 0.3s ease;
}

.incident-item:hover .incident-card {
    background-color: #ffffff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.incident-item.active .incident-card {
    background-color: #e3f2fd;
    border-left: 4px solid #2196f3;
    box-shadow: 0 2px 12px rgba(33,150,243,0.2);
}

.incident-card {
    margin: 0.5rem 1rem;
    padding: 1rem;
    border-radius: 12px;
    background: white;
    border: 1px solid #f0f0f0;
    transition: all 0.3s ease;
}

.assigned-card {
    border-left: 3px solid #28a745;
}

.incident-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.severity-badge {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.severity-critical { background: linear-gradient(135deg, #d32f2f, #f44336); }
.severity-high { background: linear-gradient(135deg, #f57c00, #ff9800); }
.severity-medium { background: linear-gradient(135deg, #fbc02d, #ffeb3b); color: #333; }
.severity-low { background: linear-gradient(135deg, #388e3c, #4caf50); }

.incident-content h6 {
    font-size: 0.95rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    line-height: 1.3;
    color: #2c3e50;
}

.incident-meta {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.meta-item {
    display: flex;
    align-items: center;
    font-size: 0.8rem;
    color: #6c757d;
    gap: 0.5rem;
}

.meta-item.assigned {
    color: #28a745;
    font-weight: 500;
}

.user-avatar-sm {
    width: 20px;
    height: 20px;
}

.user-avatar-sm .avatar-img {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    object-fit: cover;
}

.incident-time {
    font-size: 0.75rem;
    color: #adb5bd;
    margin-top: 0.5rem;
    font-weight: 500;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Report Form */
.report-form-container {
    padding: 1.5rem;
}

.form-header {
    margin-bottom: 1.5rem;
    text-align: center;
}

.form-header h5 {
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.report-form .form-group {
    margin-bottom: 1.25rem;
}

.report-form .form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.report-form .form-control,
.report-form .form-select {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    padding: 0.75rem;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.report-form .form-control:focus,
.report-form .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

.btn-report {
    padding: 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.btn-report:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
}

/* Chat Container */
.chat-container {
    height: calc(100vh - 120px);
    display: flex;
    flex-direction: column;
}

/* Welcome State */
.chat-welcome {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    background: white;
    color: white;
    text-align: center;
}

.welcome-content {
    max-width: 400px;
    padding: 2rem;
}

.welcome-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.9;
}

.welcome-content h4 {
    margin-bottom: 1rem;
    font-weight: 600;
}

.welcome-stats {
    display: flex;
    justify-content: space-around;
    margin-top: 2rem;
    gap: 1rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.8rem;
    opacity: 0.8;
}

/* Improved Chat Interface */
.chat-interface {
    height: 100%;
    display: flex;
    flex-direction: column;
    background: #f8f9fa;
}

.chat-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e9ecef;
    background: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    z-index: 10;
}

.chat-close-btn {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: none;
    background: #f8f9fa;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    z-index: 20;
}

.chat-close-btn:hover {
    background: #e9ecef;
    color: #495057;
    transform: scale(1.1);
}

.chat-messages {
    flex: 1;
    padding: 1rem;
    overflow-y: auto;
    max-height: calc(100vh - 280px);
}

/* Fixed Chat Input Container */
.chat-input-container {
    position: sticky;
    bottom: 0;
    background: white;
    border-top: 1px solid #e9ecef;
    z-index: 10;
}

.chat-input {
    padding: 1rem 1.5rem;
}

/* Improved Message Styles */
.message-item {
    margin-bottom: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.message-item.own {
    flex-direction: row-reverse;
}

.message-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    overflow: hidden;
    border: 2px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.message-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.message-content {
    max-width: 70%;
    display: flex;
    flex-direction: column;
}

.message-item.own .message-content {
    align-items: flex-end;
}

.message-sender {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
    font-weight: 500;
}

.message-bubble {
    background: white;
    padding: 0.75rem 1rem;
    border-radius: 18px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    position: relative;
    word-wrap: break-word;
    border: 1px solid #f0f0f0;
}

.message-item.own .message-bubble {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    border: none;
}

.message-time {
    font-size: 0.7rem;
    color: #adb5bd;
    margin-top: 0.25rem;
}

.system-message {
    text-align: center;
    margin: 1.5rem 0;
}

.system-message .message-bubble {
    background: #e9ecef;
    color: #495057;
    font-style: italic;
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    border: 1px solid #dee2e6;
}

/* Improved Input Form */
.message-form {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: #f8f9fa;
    padding: 0.75rem;
    border-radius: 25px;
    border: 1px solid #e9ecef;
}

.message-form input {
    flex: 1;
    border: none;
    background: transparent;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    outline: none;
}

.message-form input::placeholder {
    color: #adb5bd;
}

.message-form button {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.message-form button:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
}

/* Responsive Design */
@media (max-width: 991.98px) {
    .incident-sidebar {
        height: auto;
        max-height: 400px;
    }
    
    .chat-container {
        height: 500px;
        margin-top: 1rem;
    }
    
    .welcome-stats {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .message-content {
        max-width: 85%;
    }
    
    .tab-text {
        display: none;
    }
}

@media (max-width: 575.98px) {
    .incident-tabs .nav-pills .nav-link {
        padding: 0.5rem 0.25rem;
        min-height: 50px;
    }
    
    .incident-card {
        margin: 0.25rem 0.5rem;
        padding: 0.75rem;
    }
    
    .report-form-container {
        padding: 1rem;
    }
    
    .chat-header,
    .chat-input {
        padding: 0.75rem 1rem;
    }
    
    .message-avatar {
        width: 35px;
        height: 35px;
    }
}

/* Animation Classes */
.fade-in {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.slide-up {
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* Loading States */
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

/* Alert Styles */
.alert {
    border-radius: 8px;
    border: none;
    padding: 1rem 1.25rem;
    margin-bottom: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.alert-success {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    color: #155724;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da, #f5c6cb);
    color: #721c24;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentIncidentId = null;
    let incidentRefreshInterval;
    let lastIncidentUpdate = null;
    
    // Initialize the interface
    initializeInterface();
    
    // Start auto-refresh for incidents
    startIncidentAutoRefresh();
    
    // Handle URL hash on page load
    handleUrlHash();
    
    function initializeInterface() {
        // Handle incident item clicks
        document.querySelectorAll('.incident-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const incidentId = this.dataset.incidentId;
                selectIncident(this, incidentId);
            });
        });
        
        // Handle report incident form
        const reportForm = document.getElementById('reportIncidentForm');
        if (reportForm) {
            reportForm.addEventListener('submit', handleReportSubmit);
        }
        
        // Handle search
        const searchInput = document.getElementById('incident-search');
        if (searchInput) {
            searchInput.addEventListener('input', handleSearch);
        }
        
        // Handle tab changes - pause refresh when user is actively using the interface
        document.querySelectorAll('[data-bs-toggle="pill"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function() {
                // Brief pause when switching tabs
                pauseIncidentAutoRefresh();
                setTimeout(() => startIncidentAutoRefresh(), 2000);
            });
        });
    }
    
    function handleUrlHash() {
        const hash = window.location.hash;
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        
        if (hash === '#report-incident' || tab === 'report') {
            switchToReportTab();
        } else if (hash === '#assigned-incidents' || tab === 'assigned') {
            switchToAssignedTab();
        }
    }
    
    // Auto-refresh functionality
    function startIncidentAutoRefresh() {
        // Clear existing interval
        if (incidentRefreshInterval) {
            clearInterval(incidentRefreshInterval);
        }
        
        // Start new interval
        incidentRefreshInterval = setInterval(function() {
            refreshIncidentList();
            
            // If a chat is open, refresh it too
            if (currentIncidentId) {
                refreshCurrentIncidentChat();
            }
        }, 30000); // 30 seconds
    }
    
    function pauseIncidentAutoRefresh() {
        if (incidentRefreshInterval) {
            clearInterval(incidentRefreshInterval);
            incidentRefreshInterval = null;
        }
    }
    
    function refreshIncidentList() {
        // Only refresh if user is not actively typing or interacting
        const activeElement = document.activeElement;
        const isUserTyping = activeElement && (
            activeElement.tagName === 'INPUT' || 
            activeElement.tagName === 'TEXTAREA'
        );
        
        if (isUserTyping) {
            return; // Skip refresh if user is typing
        }
        
        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parse the response and update incident counts silently
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            updateIncidentCountsSilently(doc);
        })
        .catch(error => {
            // Silent fail - don't show errors for background refresh
            console.debug('Background incident refresh failed:', error);
        });
    }
    
    function updateIncidentCountsSilently(doc) {
        // Update badge counts from the new document
        const newAllBadge = doc.querySelector('#all-incidents-tab .badge');
        const newAssignedBadge = doc.querySelector('#assigned-incidents-tab .badge');
        
        const currentAllBadge = document.querySelector('#all-incidents-tab .badge');
        const currentAssignedBadge = document.querySelector('#assigned-incidents-tab .badge');
        
        if (newAllBadge && currentAllBadge) {
            currentAllBadge.textContent = newAllBadge.textContent;
        }
        if (newAssignedBadge && currentAssignedBadge) {
            currentAssignedBadge.textContent = newAssignedBadge.textContent;
        }
    }
    
    function refreshCurrentIncidentChat() {
        if (!currentIncidentId) return;
        
        fetch(`/staff/incidents/${currentIncidentId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateChatMessagesSilently(data.incident);
                }
            })
            .catch(error => {
                console.debug('Background chat refresh failed:', error);
            });
    }
    
    function updateChatMessagesSilently(incident) {
        const chatMessages = document.getElementById('chatMessages');
        if (!chatMessages) return;
        
        // Store scroll position
        const isScrolledToBottom = chatMessages.scrollTop + chatMessages.clientHeight >= chatMessages.scrollHeight - 10;
        
        // Update messages
        updateChatMessages(incident);
        
        // Maintain scroll position (only auto-scroll if user was at bottom)
        if (isScrolledToBottom) {
            setTimeout(() => {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }, 100);
        }
    }
    
    // Incident selection and chat functionality
    function selectIncident(element, incidentId) {
        // Pause auto-refresh when user selects an incident
        pauseIncidentAutoRefresh();
        
        // Update active state
        document.querySelectorAll('.incident-item').forEach(i => i.classList.remove('active'));
        element.classList.add('active');
        
        // Load incident chat
        loadIncidentChat(incidentId);
        
        // Resume auto-refresh after 5 seconds
        setTimeout(() => startIncidentAutoRefresh(), 5000);
    }
    
    function loadIncidentChat(incidentId) {
        currentIncidentId = incidentId;
        
        // Show loading state
        showChatLoading();
        
        fetch(`/staff/incidents/${incidentId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayIncidentChat(data.incident);
                } else {
                    showError('Failed to load incident details');
                }
            })
            .catch(error => {
                console.error('Error loading incident:', error);
                showError('Failed to load incident details');
            });
    }
    
    function showChatLoading() {
        document.getElementById('chatWelcome').style.display = 'none';
        document.getElementById('chatInterface').style.display = 'flex';
        
        const chatMessages = document.getElementById('chatMessages');
        chatMessages.innerHTML = `
            <div class="text-center py-4">
                <div class="loading-spinner"></div>
                <p class="text-muted mt-2">Loading incident details...</p>
            </div>
        `;
    }
    
    function displayIncidentChat(incident) {
        // Hide welcome, show chat
        document.getElementById('chatWelcome').style.display = 'none';
        document.getElementById('chatInterface').style.display = 'flex';
        
        // Update chat header
        updateChatHeader(incident);
        
        // Update chat messages
        updateChatMessages(incident);
        
        // Update chat input
        updateChatInput();
        
        // Add fade-in animation
        document.getElementById('chatInterface').classList.add('fade-in');
    }
    
    function updateChatHeader(incident) {
        const chatHeader = document.getElementById('chatHeader');
        chatHeader.innerHTML = `
            <div class="d-flex align-items-center flex-grow-1">
                <div class="severity-badge severity-${incident.severity} me-3">
                    ${getSeverityIcon(incident.severity)}
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1">${incident.title}</h6>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge ${getStatusBadgeClass(incident.status)}">${incident.status}</span>
                        <small class="text-muted">
                            <i class="mdi mdi-map-marker me-1"></i>${incident.location}
                        </small>
                        <small class="text-muted">
                            <i class="mdi mdi-clock me-1"></i>${incident.created_at}
                        </small>
                    </div>
                </div>
            </div>
            <button class="chat-close-btn" onclick="closeChatInterface()">
                <i class="mdi mdi-close"></i>
            </button>
        `;
    }
    
    function updateChatMessages(incident) {
        const chatMessages = document.getElementById('chatMessages');
        let messagesHtml = '';
        
        if (incident.actions && incident.actions.length > 0) {
            incident.actions.forEach(action => {
                const isOwnMessage = action.user_id === '{{ auth()->id() }}';
                const isSystemMessage = action.action_type !== 'message';
                
                if (isSystemMessage) {
                    messagesHtml += createSystemMessage(action);
                } else {
                    messagesHtml += createUserMessage(action, isOwnMessage);
                }
            });
        } else {
            messagesHtml = `
                <div class="text-center py-4">
                    <i class="mdi mdi-message-outline display-4 text-muted"></i>
                    <p class="text-muted mt-2">No messages yet. Start the conversation!</p>
                </div>
            `;
        }
        
        chatMessages.innerHTML = messagesHtml;
        
        // Scroll to bottom
        setTimeout(() => {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }, 100);
    }
    
    function createSystemMessage(action) {
        return `
            <div class="system-message slide-up">
                <div class="message-bubble">
                    <i class="mdi ${getActionTypeIcon(action.action_type)} me-1"></i>
                    ${action.message}
                </div>
                <div class="message-time">${formatTime(action.created_at)}</div>
            </div>
        `;
    }
    
    function createUserMessage(action, isOwnMessage) {
        // Create avatar HTML based on profile image availability
        let avatarHtml = '';
        if (action.user.profile_image) {
            avatarHtml = `<img src="/storage/${action.user.profile_image}" alt="${action.user.name}">`;
        } else {
            avatarHtml = `<img src="/empty.svg" alt="${action.user.name}">`;
        }
        
        return `
            <div class="message-item ${isOwnMessage ? 'own' : ''} slide-up">
                <div class="message-avatar">
                    ${avatarHtml}
                </div>
                <div class="message-content">
                    ${!isOwnMessage ? `<div class="message-sender">${action.user.name}</div>` : ''}
                    <div class="message-bubble">
                        ${action.message}
                    </div>
                    <div class="message-time">${formatTime(action.created_at)}</div>
                </div>
            </div>
        `;
    }
    
    function updateChatInput() {
        const chatInput = document.getElementById('chatInput');
        chatInput.innerHTML = `
            <form class="message-form" id="messageForm">
                <input type="text" id="messageInput" placeholder="Type your message..." required>
                <button type="submit">
                    <i class="mdi mdi-send"></i>
                </button>
            </form>
        `;
        
        // Handle message form submission
        document.getElementById('messageForm').addEventListener('submit', handleMessageSubmit);
    }
    
    function handleMessageSubmit(e) {
        e.preventDefault();
        
        const messageInput = document.getElementById('messageInput');
        const message = messageInput.value.trim();
        
        if (!message || !currentIncidentId) return;
        
        // Show sending state
        const submitBtn = e.target.querySelector('button');
        const originalContent = submitBtn.innerHTML;
        submitBtn.innerHTML = '<div class="loading-spinner"></div>';
        submitBtn.disabled = true;
        
        const formData = new FormData();
        formData.append('message', message);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        
        fetch(`/staff/incidents/${currentIncidentId}/messages`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Clear input
                messageInput.value = '';
                
                // Add message to chat
                addMessageToChat(data.action);
            } else {
                showError(data.message || 'Failed to send message');
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
            showError('Failed to send message');
        })
        .finally(() => {
            submitBtn.innerHTML = originalContent;
            submitBtn.disabled = false;
        });
    }
    
    function addMessageToChat(action) {
        const chatMessages = document.getElementById('chatMessages');
        const isOwnMessage = action.user_id === '{{ auth()->id() }}';
        
        const messageHtml = createUserMessage(action, isOwnMessage);
        chatMessages.insertAdjacentHTML('beforeend', messageHtml);
        
        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    function handleReportSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<div class="loading-spinner me-2"></div>Reporting...';
        
        fetch('{{ route("staff.incidents.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('Incident reported successfully!');
                e.target.reset();
                
                // Switch to all incidents tab and refresh
                document.getElementById('all-incidents-tab').click();
                setTimeout(() => location.reload(), 1500);
            } else {
                showError(data.message || 'Failed to report incident');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred while reporting the incident');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    }
    
    function handleSearch() {
        const searchTerm = this.value.toLowerCase();
        const incidents = document.querySelectorAll('.incident-item');
        
        incidents.forEach(incident => {
            const title = incident.querySelector('.incident-title')?.textContent.toLowerCase() || '';
            const location = incident.querySelector('.meta-item span')?.textContent.toLowerCase() || '';
            
            if (title.includes(searchTerm) || location.includes(searchTerm)) {
                incident.style.display = 'block';
            } else {
                incident.style.display = 'none';
            }
        });
    }
    
    // Global functions
    window.closeChatInterface = function() {
        document.getElementById('chatWelcome').style.display = 'flex';
        document.getElementById('chatInterface').style.display = 'none';
        document.querySelectorAll('.incident-item').forEach(i => i.classList.remove('active'));
        currentIncidentId = null;
    };
    
    window.switchToReportTab = function() {
        document.getElementById('report-incident-tab').click();
    };
    
    window.switchToAssignedTab = function() {
        document.getElementById('assigned-incidents-tab').click();
    };
    
    function switchToReportTab() {
        document.getElementById('report-incident-tab').click();
    }
    
    function switchToAssignedTab() {
        document.getElementById('assigned-incidents-tab').click();
    }
    
    // Helper functions
    function getSeverityIcon(severity) {
        const icons = {
            'critical': '<i class="mdi mdi-alert-octagon"></i>',
            'high': '<i class="mdi mdi-alert"></i>',
            'medium': '<i class="mdi mdi-alert-circle"></i>',
            'low': '<i class="mdi mdi-information"></i>'
        };
        return icons[severity] || icons['low'];
    }
    
    function getStatusBadgeClass(status) {
        const classes = {
            'pending': 'badge-warning',
            'assigned': 'badge-info',
            'in_progress': 'badge-primary',
            'resolved': 'badge-success'
        };
        return classes[status] || 'badge-secondary';
    }
    
    function getActionTypeIcon(actionType) {
        const icons = {
            'assignment': 'mdi-account-plus',
            'resource_allocation': 'mdi-hospital-box',
            'status_change': 'mdi-update',
            'resolution': 'mdi-check-circle'
        };
        return icons[actionType] || 'mdi-information';
    }
    
    function formatTime(timestamp) {
        const date = new Date(timestamp);
        return date.toLocaleTimeString('en-US', { 
            hour: 'numeric', 
            minute: '2-digit',
            hour12: true 
        });
    }
    
    function showSuccess(message) {
        showAlert('success', message);
    }
    
    function showError(message) {
        showAlert('error', message);
    }
    
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'mdi-check-circle' : 'mdi-alert-circle';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="mdi ${iconClass} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Insert alert at the top of the container
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
});
</script>
@endsection