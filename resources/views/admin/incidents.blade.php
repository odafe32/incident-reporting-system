@extends('layouts.admin')
@section('content')

<div class="container-fluid">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-end">
                    <div class="btn-group">
                        <button class="btn btn-primary btn-sm" onclick="refreshIncidents()">
                            <i class="mdi mdi-refresh me-1"></i> Refresh
                        </button>
                        <button class="btn btn-success btn-sm" onclick="showResourceModal()">
                            <i class="mdi mdi-hospital-box me-1"></i> Manage Resources
                        </button>
                    </div>
                </div>
                <h4 class="page-title">
                    <i class="mdi mdi-shield-account me-2"></i>Incident Management Center
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
                                <!-- Filter Tabs -->
                                <div class="incident-tabs">
                                    <ul class="nav nav-pills nav-justified" id="incidentTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="all-incidents-tab" data-bs-toggle="pill" 
                                                    data-bs-target="#all-incidents" type="button" role="tab">
                                                <div class="tab-content-wrapper">
                                                    <i class="mdi mdi-format-list-bulleted"></i>
                                                    <span class="tab-text">All Incidents</span>
                                                    <span class="badge bg-primary" id="allIncidentsCount">{{ $allIncidents->count() }}</span>
                                                </div>
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pending-incidents-tab" data-bs-toggle="pill" 
                                                    data-bs-target="#pending-incidents" type="button" role="tab">
                                                <div class="tab-content-wrapper">
                                                    <i class="mdi mdi-clock-alert"></i>
                                                    <span class="tab-text">Pending</span>
                                                    <span class="badge bg-warning" id="pendingIncidentsCount">{{ $pendingIncidents->count() }}</span>
                                                </div>
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="critical-incidents-tab" data-bs-toggle="pill" 
                                                    data-bs-target="#critical-incidents" type="button" role="tab">
                                                <div class="tab-content-wrapper">
                                                    <i class="mdi mdi-alert-octagon"></i>
                                                    <span class="tab-text">Critical</span>
                                                    <span class="badge bg-danger" id="criticalIncidentsCount">{{ $criticalIncidents->count() }}</span>
                                                </div>
                                            </button>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Search and Filters -->
                                <div class="incident-search">
                                    <div class="position-relative mb-3">
                                        <input type="text" id="incident-search" class="form-control" 
                                               placeholder="Search incidents...">
                                        <i class="mdi mdi-magnify search-icon"></i>
                                    </div>
                                    <div class="filter-controls">
                                        <select id="severityFilter" class="form-select form-select-sm mb-2">
                                            <option value="">All Severities</option>
                                            <option value="critical">Critical</option>
                                            <option value="high">High</option>
                                            <option value="medium">Medium</option>
                                            <option value="low">Low</option>
                                        </select>
                                        <select id="statusFilter" class="form-select form-select-sm">
                                            <option value="">All Statuses</option>
                                            <option value="pending">Pending</option>
                                            <option value="assigned">Assigned</option>
                                            <option value="in_progress">In Progress</option>
                                            <option value="resolved">Resolved</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Incident List -->
                                <div class="incident-list" data-simplebar style="max-height: calc(100vh - 280px);">
                                    <div class="tab-content" id="incidentTabContent">
                                        <!-- All Incidents Tab -->
                                        <div class="tab-pane fade show active" id="all-incidents">
                                            @forelse($allIncidents as $incident)
                                                @include('admin.partials.incident-item', ['incident' => $incident])
                                            @empty
                                                <div class="empty-state">
                                                    <i class="mdi mdi-clipboard-text-outline"></i>
                                                    <p>No incidents found</p>
                                                </div>
                                            @endforelse
                                        </div>

                                        <!-- Pending Incidents Tab -->
                                        <div class="tab-pane fade" id="pending-incidents">
                                            @forelse($pendingIncidents as $incident)
                                                @include('admin.partials.incident-item', ['incident' => $incident])
                                            @empty
                                                <div class="empty-state">
                                                    <i class="mdi mdi-clock-alert-outline"></i>
                                                    <p>No pending incidents</p>
                                                </div>
                                            @endforelse
                                        </div>

                                        <!-- Critical Incidents Tab -->
                                        <div class="tab-pane fade" id="critical-incidents">
                                            @forelse($criticalIncidents as $incident)
                                                @include('admin.partials.incident-item', ['incident' => $incident])
                                            @empty
                                                <div class="empty-state">
                                                    <i class="mdi mdi-shield-check-outline"></i>
                                                    <p>No critical incidents</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side - Chat Interface with Admin Controls -->
                        <div class="col-lg-8 col-xl-9">
                            <div class="chat-container" id="chatContainer">
                                <!-- Welcome State -->
                                <div class="chat-welcome" id="chatWelcome">
                                    <div class="welcome-content">
                                        <div class="welcome-icon">
                                            <i class="mdi mdi-shield-account" style="color: #007bff;"></i>
                                        </div>
                                        <h4 style="color: #2c3e50;">Admin Incident Management</h4>
                                        <p class="text-muted">Select an incident to view details, chat history, and manage assignments</p>
                                        <div class="welcome-stats">
                                            <div class="stat-item">
                                                <div class="stat-number text-primary">{{ $allIncidents->count() }}</div>
                                                <div class="stat-label">Total Incidents</div>
                                            </div>
                                            <div class="stat-item">
                                                <div class="stat-number text-warning">{{ $pendingIncidents->count() }}</div>
                                                <div class="stat-label">Pending Review</div>
                                            </div>
                                            <div class="stat-item">
                                                <div class="stat-number text-danger">{{ $criticalIncidents->count() }}</div>
                                                <div class="stat-label">Critical</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Chat Interface -->
                                <div class="chat-interface" id="chatInterface" style="display: none;">
                                    <!-- Chat Header with Admin Controls -->
                                    <div class="chat-header" id="chatHeader">
                                        <!-- Dynamic content -->
                                    </div>
                                    
                                    <!-- Admin Action Panel -->
                                    <div class="admin-action-panel" id="adminActionPanel" style="display: none;">
                                        <!-- Dynamic content -->
                                    </div>
                                    
                                    <!-- Chat Messages -->
                                    <div class="chat-messages" id="chatMessages" data-simplebar>
                                        <!-- Dynamic content -->
                                    </div>
                                    
                                    <!-- Chat Input -->
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

<!-- Assignment Modal -->
<div class="modal fade" id="assignmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="mdi mdi-account-plus me-2"></i>Assign Incident
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="assignmentForm">
                    <input type="hidden" id="assignIncidentId">
                    <div class="mb-3">
                        <label class="form-label">Select Staff Member</label>
                        <select id="assignStaffSelect" class="form-select" required>
                            <option value="">Choose staff member...</option>
                            @foreach($staffMembers as $staff)
                                <option value="{{ $staff->id }}" data-role="{{ $staff->role }}" data-department="{{ $staff->department }}">
                                    {{ $staff->name }} - {{ $staff->getDisplayRole() }} ({{ $staff->department }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Assignment Notes (Optional)</label>
                        <textarea id="assignmentNotes" class="form-control" rows="3" 
                                  placeholder="Add any specific instructions or notes..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitAssignment()">
                    <i class="mdi mdi-check me-1"></i>Assign Incident
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Resource Allocation Modal -->
<div class="modal fade" id="resourceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="mdi mdi-hospital-box me-2"></i>Allocate Resources
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="resourceForm">
                    <input type="hidden" id="resourceIncidentId">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Available Resources</h6>
                            <div id="availableResources">
                                <!-- Dynamic content -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Selected Resources</h6>
                            <div id="selectedResources" class="selected-resources-list">
                                <p class="text-muted">No resources selected</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Resource Notes</label>
                        <textarea id="resourceNotes" class="form-control" rows="3" 
                                  placeholder="Add notes about resource allocation..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="submitResourceAllocation()">
                    <i class="mdi mdi-check me-1"></i>Allocate Resources
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Admin Styles */
.incident-sidebar {
    background: #f8f9fa;
    border-right: 1px solid #e9ecef;
    height: calc(100vh - 120px);
    display: flex;
    flex-direction: column;
}

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

.search-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    font-size: 1rem;
}

.filter-controls .form-select {
    font-size: 0.8rem;
}

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

/* Chat Interface */
.chat-container {
    height: calc(100vh - 120px);
    display: flex;
    flex-direction: column;
}

.chat-welcome {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    background: white;
    text-align: center;
}

.welcome-content {
    max-width: 500px;
    padding: 2rem;
}

.welcome-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
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
    color: #6c757d;
}

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

/* Admin Action Panel */
.admin-action-panel {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-bottom: 1px solid #dee2e6;
    padding: 1rem 1.5rem;
}

.action-buttons {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.action-btn {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    border: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.status-controls {
    margin-top: 0.75rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.status-select {
    min-width: 150px;
    font-size: 0.85rem;
}

.chat-messages {
    flex: 1;
    padding: 1rem;
    overflow-y: auto;
    max-height: calc(100vh - 400px);
}

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

/* Message Styles */
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

/* Input Form */
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
}

.message-form button:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
}

/* Resource Selection */
.resource-item {
    padding: 0.75rem;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.resource-item:hover {
    background-color: #f8f9fa;
    border-color: #007bff;
}

.resource-item.selected {
    background-color: #e3f2fd;
    border-color: #2196f3;
}

.selected-resources-list {
    min-height: 200px;
    border: 1px dashed #dee2e6;
    border-radius: 8px;
    padding: 1rem;
}

.selected-resource {
    background: #e3f2fd;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    margin-bottom: 0.5rem;
    display: flex;
    justify-content: between;
    align-items: center;
}

.remove-resource {
    background: none;
    border: none;
    color: #dc3545;
    font-size: 1.2rem;
    cursor: pointer;
}

/* Animations */
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
    
    .action-buttons {
        justify-content: center;
    }
    
    .status-controls {
        flex-direction: column;
        align-items: stretch;
        gap: 0.5rem;
    }
}

@media (max-width: 575.98px) {
    .tab-text {
        display: none;
    }
    
    .action-btn {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
    }
    
    .message-content {
        max-width: 85%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentIncidentId = null;
    let incidentRefreshInterval;
    let selectedResources = [];
    
    // Initialize the interface
    initializeInterface();
    
    // Start auto-refresh
    startIncidentAutoRefresh();
    
    function initializeInterface() {
        // Handle incident item clicks
        document.querySelectorAll('.incident-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const incidentId = this.dataset.incidentId;
                selectIncident(this, incidentId);
            });
        });
        
        // Handle search and filters
        document.getElementById('incident-search').addEventListener('input', handleSearch);
        document.getElementById('severityFilter').addEventListener('change', handleFilter);
        document.getElementById('statusFilter').addEventListener('change', handleFilter);
        
        // Handle tab changes
        document.querySelectorAll('[data-bs-toggle="pill"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function() {
                pauseIncidentAutoRefresh();
                setTimeout(() => startIncidentAutoRefresh(), 2000);
            });
        });
    }
    
    function startIncidentAutoRefresh() {
        if (incidentRefreshInterval) {
            clearInterval(incidentRefreshInterval);
        }
        
        incidentRefreshInterval = setInterval(function() {
            refreshIncidentList();
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
        const activeElement = document.activeElement;
        const isUserTyping = activeElement && (
            activeElement.tagName === 'INPUT' || 
            activeElement.tagName === 'TEXTAREA'
        );
        
        if (isUserTyping) return;
        
        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateIncidentCounts(data.counts);
            }
        })
        .catch(error => {
            console.debug('Background refresh failed:', error);
        });
    }
    
    function updateIncidentCounts(counts) {
        document.getElementById('allIncidentsCount').textContent = counts.all || 0;
        document.getElementById('pendingIncidentsCount').textContent = counts.pending || 0;
        document.getElementById('criticalIncidentsCount').textContent = counts.critical || 0;
    }
    
    function selectIncident(element, incidentId) {
        pauseIncidentAutoRefresh();
        
        // Update active state
        document.querySelectorAll('.incident-item').forEach(i => i.classList.remove('active'));
        element.classList.add('active');
        
        // Load incident chat
        loadIncidentChat(incidentId);
        
        setTimeout(() => startIncidentAutoRefresh(), 5000);
    }
    
    function loadIncidentChat(incidentId) {
        currentIncidentId = incidentId;
        showChatLoading();
        
        fetch(`/admin/incidents/${incidentId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayIncidentChat(data.incident, data.staffMembers, data.resources);
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
    
    function displayIncidentChat(incident, staffMembers, resources) {
        document.getElementById('chatWelcome').style.display = 'none';
        document.getElementById('chatInterface').style.display = 'flex';
        
        updateChatHeader(incident);
        updateAdminActionPanel(incident, staffMembers, resources);
        updateChatMessages(incident);
        updateChatInput();
        
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
                        <span class="badge ${getStatusBadgeClass(incident.status)}">${incident.display_status}</span>
                        <small class="text-muted">
                            <i class="mdi mdi-map-marker me-1"></i>${incident.location}
                        </small>
                        <small class="text-muted">
                            <i class="mdi mdi-account me-1"></i>Reported by ${incident.reporter.name}
                        </small>
                        <small class="text-muted">
                            <i class="mdi mdi-clock me-1"></i>${incident.time_elapsed}
                        </small>
                    </div>
                </div>
            </div>
            <button class="btn btn-sm btn-outline-secondary" onclick="closeChatInterface()">
                <i class="mdi mdi-close"></i>
            </button>
        `;
    }
    
    function updateAdminActionPanel(incident, staffMembers, resources) {
        const adminActionPanel = document.getElementById('adminActionPanel');
        adminActionPanel.style.display = 'block';
        
        const assignedText = incident.assigned_user ? 
            `Assigned to: <strong>${incident.assigned_user.name}</strong>` : 
            'Not assigned';
            
        adminActionPanel.innerHTML = `
            <div class="d-flex justify-content-between align-items-start">
                <div class="action-buttons">
                    <button class="btn btn-primary action-btn" onclick="showAssignmentModal('${incident.id}')">
                        <i class="mdi mdi-account-plus"></i>
                        ${incident.assigned_user ? 'Reassign' : 'Assign Staff'}
                    </button>
                    <button class="btn btn-success action-btn" onclick="showResourceModal('${incident.id}')">
                        <i class="mdi mdi-hospital-box"></i>
                        Allocate Resources
                    </button>
                    <button class="btn btn-info action-btn" onclick="updateIncidentStatus('${incident.id}', 'in_progress')">
                        <i class="mdi mdi-play"></i>
                        Mark In Progress
                    </button>
                    <button class="btn btn-warning action-btn" onclick="updateIncidentStatus('${incident.id}', 'resolved')">
                        <i class="mdi mdi-check-circle"></i>
                        Mark Resolved
                    </button>
                </div>
                <div class="incident-assignment-info">
                    <small class="text-muted">${assignedText}</small>
                </div>
            </div>
            <div class="status-controls">
                <label class="form-label mb-0">Quick Status Change:</label>
                <select class="form-select status-select" onchange="updateIncidentStatus('${incident.id}', this.value)">
                    <option value="">Select new status...</option>
                    <option value="pending" ${incident.status === 'pending' ? 'selected' : ''}>Pending</option>
                    <option value="assigned" ${incident.status === 'assigned' ? 'selected' : ''}>Assigned</option>
                    <option value="in_progress" ${incident.status === 'in_progress' ? 'selected' : ''}>In Progress</option>
                    <option value="resolved" ${incident.status === 'resolved' ? 'selected' : ''}>Resolved</option>
                </select>
            </div>
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
                    ${!isOwnMessage ? `<div class="message-sender">${action.user.name} - ${action.user.role}</div>` : ''}
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
        
        document.getElementById('messageForm').addEventListener('submit', handleMessageSubmit);
    }
    
    function handleMessageSubmit(e) {
        e.preventDefault();
        
        const messageInput = document.getElementById('messageInput');
        const message = messageInput.value.trim();
        
        if (!message || !currentIncidentId) return;
        
        const submitBtn = e.target.querySelector('button');
        const originalContent = submitBtn.innerHTML;
        submitBtn.innerHTML = '<div class="loading-spinner"></div>';
        submitBtn.disabled = true;
        
        const formData = new FormData();
        formData.append('message', message);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        
        fetch(`/admin/incidents/${currentIncidentId}/messages`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageInput.value = '';
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
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Assignment Modal Functions
    window.showAssignmentModal = function(incidentId) {
        document.getElementById('assignIncidentId').value = incidentId;
        const modal = new bootstrap.Modal(document.getElementById('assignmentModal'));
        modal.show();
    };
    
    window.submitAssignment = function() {
        const incidentId = document.getElementById('assignIncidentId').value;
        const staffId = document.getElementById('assignStaffSelect').value;
        const notes = document.getElementById('assignmentNotes').value;
        
        if (!staffId) {
            showError('Please select a staff member');
            return;
        }
        
        const formData = new FormData();
        formData.append('assigned_to', staffId);
        formData.append('notes', notes);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        
        fetch(`/admin/incidents/${incidentId}/assign`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('Incident assigned successfully');
                bootstrap.Modal.getInstance(document.getElementById('assignmentModal')).hide();
                loadIncidentChat(incidentId); // Refresh chat
            } else {
                showError(data.message || 'Failed to assign incident');
            }
        })
        .catch(error => {
            console.error('Error assigning incident:', error);
            showError('Failed to assign incident');
        });
    };
    
    // Resource Modal Functions
    window.showResourceModal = function(incidentId) {
        document.getElementById('resourceIncidentId').value = incidentId || '';
        loadAvailableResources();
        const modal = new bootstrap.Modal(document.getElementById('resourceModal'));
        modal.show();
    };
    
    function loadAvailableResources() {
        fetch('/admin/resources/available')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayAvailableResources(data.resources);
                }
            })
            .catch(error => {
                console.error('Error loading resources:', error);
            });
    }
    
    function displayAvailableResources(resources) {
        const container = document.getElementById('availableResources');
        let html = '';
        
        resources.forEach(resource => {
            html += `
                <div class="resource-item" onclick="selectResource('${resource.id}', '${resource.name}', '${resource.type}')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${resource.name}</strong>
                            <br>
                            <small class="text-muted">${resource.type} - ${resource.location}</small>
                        </div>
                        <span class="badge ${getResourceTypeBadgeClass(resource.type)}">${resource.type}</span>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html || '<p class="text-muted">No resources available</p>';
    }
    
    window.selectResource = function(id, name, type) {
        if (selectedResources.find(r => r.id === id)) return;
        
        selectedResources.push({ id, name, type });
        updateSelectedResourcesDisplay();
    };
    
    function updateSelectedResourcesDisplay() {
        const container = document.getElementById('selectedResources');
        
        if (selectedResources.length === 0) {
            container.innerHTML = '<p class="text-muted">No resources selected</p>';
            return;
        }
        
        let html = '';
        selectedResources.forEach((resource, index) => {
            html += `
                <div class="selected-resource">
                    <span>${resource.name} (${resource.type})</span>
                    <button type="button" class="remove-resource" onclick="removeResource(${index})">
                        <i class="mdi mdi-close"></i>
                    </button>
                </div>
            `;
        });
        
        container.innerHTML = html;
    }
    
    window.removeResource = function(index) {
        selectedResources.splice(index, 1);
        updateSelectedResourcesDisplay();
    };
    
    window.submitResourceAllocation = function() {
        const incidentId = document.getElementById('resourceIncidentId').value;
        const notes = document.getElementById('resourceNotes').value;
        
        if (selectedResources.length === 0) {
            showError('Please select at least one resource');
            return;
        }
        
        const formData = new FormData();
        formData.append('resources', JSON.stringify(selectedResources.map(r => r.id)));
        formData.append('notes', notes);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        
        fetch(`/admin/incidents/${incidentId}/allocate-resources`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('Resources allocated successfully');
                bootstrap.Modal.getInstance(document.getElementById('resourceModal')).hide();
                selectedResources = [];
                if (incidentId) {
                    loadIncidentChat(incidentId);
                }
            } else {
                showError(data.message || 'Failed to allocate resources');
            }
        })
        .catch(error => {
            console.error('Error allocating resources:', error);
            showError('Failed to allocate resources');
        });
    };
    
    // Status Update Function
    window.updateIncidentStatus = function(incidentId, status) {
        if (!status) return;
        
        const formData = new FormData();
        formData.append('status', status);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        
        fetch(`/admin/incidents/${incidentId}/status`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('Incident status updated successfully');
                loadIncidentChat(incidentId); // Refresh chat
            } else {
                showError(data.message || 'Failed to update status');
            }
        })
        .catch(error => {
            console.error('Error updating status:', error);
            showError('Failed to update status');
        });
    };
    
    // Search and Filter Functions
    function handleSearch() {
        const searchTerm = this.value.toLowerCase();
        filterIncidents();
    }
    
    function handleFilter() {
        filterIncidents();
    }
    
    function filterIncidents() {
        const searchTerm = document.getElementById('incident-search').value.toLowerCase();
        const severityFilter = document.getElementById('severityFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        
        document.querySelectorAll('.incident-item').forEach(incident => {
            const title = incident.querySelector('.incident-title')?.textContent.toLowerCase() || '';
            const location = incident.querySelector('.meta-item span')?.textContent.toLowerCase() || '';
            const severity = incident.dataset.severity || '';
            const status = incident.dataset.status || '';
            
            const matchesSearch = title.includes(searchTerm) || location.includes(searchTerm);
            const matchesSeverity = !severityFilter || severity === severityFilter;
            const matchesStatus = !statusFilter || status === statusFilter;
            
            if (matchesSearch && matchesSeverity && matchesStatus) {
                incident.style.display = 'block';
            } else {
                incident.style.display = 'none';
            }
        });
    }
    
    // Global Functions
    window.closeChatInterface = function() {
        document.getElementById('chatWelcome').style.display = 'flex';
        document.getElementById('chatInterface').style.display = 'none';
        document.getElementById('adminActionPanel').style.display = 'none';
        document.querySelectorAll('.incident-item').forEach(i => i.classList.remove('active'));
        currentIncidentId = null;
    };
    
    window.refreshIncidents = function() {
        location.reload();
    };
    
    // Helper Functions
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
    
    function getResourceTypeBadgeClass(type) {
        const classes = {
            'bed': 'bg-primary',
            'equipment': 'bg-info',
            'staff': 'bg-success'
        };
        return classes[type] || 'bg-secondary';
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
        
        const container = document.querySelector('.container-fluid');
        container.insertAdjacentHTML('afterbegin', alertHtml);
        
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