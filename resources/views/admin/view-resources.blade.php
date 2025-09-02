@extends('layouts.admin')
@section('content')

<div class="container-fluid">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-end">
                    <div class="btn-group">
                        <a href="{{ route('admin.create-resources') }}" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-plus me-1"></i> Add New Resource
                        </a>
                        <button class="btn btn-success btn-sm" onclick="refreshResources()">
                            <i class="mdi mdi-refresh me-1"></i> Refresh
                        </button>
                        <button class="btn btn-info btn-sm" onclick="exportResources()">
                            <i class="mdi mdi-download me-1"></i> Export
                        </button>
                    </div>
                </div>
                <h4 class="page-title">
                    <i class="mdi mdi-hospital-box me-2"></i>Resource Management
                </h4>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary">
                                <span class="avatar-title">
                                    <i class="mdi mdi-hospital-box font-20 text-white"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-15">Total Resources</h6>
                            <h4 class="my-1 text-primary">{{ $stats['total'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success">
                                <span class="avatar-title">
                                    <i class="mdi mdi-check-circle font-20 text-white"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-15">Available</h6>
                            <h4 class="my-1 text-success">{{ $stats['available'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning">
                                <span class="avatar-title">
                                    <i class="mdi mdi-clock font-20 text-white"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-15">In Use</h6>
                            <h4 class="my-1 text-warning">{{ $stats['in_use'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-danger">
                                <span class="avatar-title">
                                    <i class="mdi mdi-wrench font-20 text-white"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-15">Maintenance</h6>
                            <h4 class="my-1 text-danger">{{ $stats['maintenance'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resources Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">All Resources</h4>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex gap-2">
                                <!-- Search -->
                                <div class="position-relative">
                                    <input type="text" id="searchResources" class="form-control form-control-sm" 
                                           placeholder="Search resources..." style="width: 200px;">
                                    <i class="mdi mdi-magnify position-absolute" 
                                       style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                </div>
                                
                                <!-- Type Filter -->
                                <select id="typeFilter" class="form-select form-select-sm" style="width: 150px;">
                                    <option value="">All Types</option>
                                    <option value="bed">Beds</option>
                                    <option value="equipment">Equipment</option>
                                    <option value="staff">Staff</option>
                                </select>
                                
                                <!-- Status Filter -->
                                <select id="statusFilter" class="form-select form-select-sm" style="width: 150px;">
                                    <option value="">All Status</option>
                                    <option value="available">Available</option>
                                    <option value="in_use">In Use</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="resourcesTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Current Incident</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($resources ?? [] as $resource)
                                    <tr data-resource-id="{{ $resource->id }}" 
                                        data-type="{{ $resource->type }}" 
                                        data-status="{{ $resource->status }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm rounded-circle resource-type-{{ $resource->type }} me-2">
                                                    <span class="avatar-title">
                                                        @switch($resource->type)
                                                            @case('bed')
                                                                <i class="mdi mdi-bed font-16 text-white"></i>
                                                                @break
                                                            @case('equipment')
                                                                <i class="mdi mdi-medical-bag font-16 text-white"></i>
                                                                @break
                                                            @case('staff')
                                                                <i class="mdi mdi-account font-16 text-white"></i>
                                                                @break
                                                        @endswitch
                                                    </span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 font-14">{{ $resource->name }}</h6>
                                                    <small class="text-muted">ID: {{ $resource->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge resource-type-badge-{{ $resource->type }}">
                                                {{ ucfirst($resource->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <i class="mdi mdi-map-marker text-muted me-1"></i>
                                            {{ $resource->location }}
                                        </td>
                                        <td>
                                            <span class="badge resource-status-{{ $resource->status }}">
                                                @switch($resource->status)
                                                    @case('available')
                                                        Available
                                                        @break
                                                    @case('in_use')
                                                        In Use
                                                        @break
                                                    @case('maintenance')
                                                        Maintenance
                                                        @break
                                                    @default
                                                        {{ ucfirst($resource->status) }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>
                                            @if($resource->currentIncident ?? false)
                                                <a href="{{ route('admin.incidents') }}?incident={{ $resource->currentIncident->id }}" 
                                                   class="text-decoration-none">
                                                    <small class="text-primary">
                                                        <i class="mdi mdi-alert-circle me-1"></i>
                                                        {{ Str::limit($resource->currentIncident->title, 30) }}
                                                    </small>
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted">
                                                {{ $resource->description ? Str::limit($resource->description, 50) : '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary btn-action" 
                                                        onclick="editResource('{{ $resource->id }}')"
                                                        data-bs-toggle="tooltip" title="Edit Resource">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                                @if($resource->status !== 'in_use')
                                                    <button class="btn btn-outline-danger btn-action" 
                                                            onclick="deleteResource('{{ $resource->id }}')"
                                                            data-bs-toggle="tooltip" title="Delete Resource">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                @endif
                                                <div class="btn-group">
                                                    <button class="btn btn-outline-secondary btn-action dropdown-toggle" 
                                                            data-bs-toggle="dropdown">
                                                        <i class="mdi mdi-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @if($resource->status === 'available')
                                                            <li><a class="dropdown-item" href="#" 
                                                                   onclick="changeResourceStatus('{{ $resource->id }}', 'maintenance')">
                                                                <i class="mdi mdi-wrench me-2 text-warning"></i>Mark for Maintenance
                                                            </a></li>
                                                        @elseif($resource->status === 'maintenance')
                                                            <li><a class="dropdown-item" href="#" 
                                                                   onclick="changeResourceStatus('{{ $resource->id }}', 'available')">
                                                                <i class="mdi mdi-check me-2 text-success"></i>Mark as Available
                                                            </a></li>
                                                        @endif
                                                        @if($resource->status === 'in_use')
                                                            <li><a class="dropdown-item" href="#" 
                                                                   onclick="changeResourceStatus('{{ $resource->id }}', 'available')">
                                                                <i class="mdi mdi-check me-2 text-success"></i>Mark as Available
                                                            </a></li>
                                                        @endif
                                                      
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="mdi mdi-hospital-box display-4 text-muted mb-2"></i>
                                                <h6 class="text-muted">No resources found</h6>
                                                <p class="text-muted mb-3">Start by adding your first resource</p>
                                                <a href="{{ route('admin.create-resources') }}" class="btn btn-primary btn-sm">
                                                    <i class="mdi mdi-plus me-1"></i>Add Resource
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Resource Modal -->
<div class="modal fade" id="editResourceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="mdi mdi-pencil me-2"></i>Edit Resource
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editResourceForm">
                    <input type="hidden" id="editResourceId">
                    
                    <div class="mb-3">
                        <label class="form-label">Resource Name</label>
                        <input type="text" id="editResourceName" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select id="editResourceType" class="form-select" required>
                            <option value="">Select type...</option>
                            <option value="bed">Bed</option>
                            <option value="equipment">Equipment</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" id="editResourceLocation" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select id="editResourceStatus" class="form-select" required>
                            <option value="available">Available</option>
                            <option value="in_use">In Use</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description (Optional)</label>
                        <textarea id="editResourceDescription" class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateResource()">
                    <i class="mdi mdi-check me-1"></i>Update Resource
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Table Styles */
.table th {
    font-weight: 600;
    font-size: 0.875rem;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    vertical-align: middle;
    font-size: 0.875rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

/* Resource Type Badges */
.resource-type-badge-bed {
    background-color: #007bff !important;
    color: white !important;
    font-weight: 500;
}

.resource-type-badge-equipment {
    background-color: #28a745 !important;
    color: white !important;
    font-weight: 500;
}

.resource-type-badge-staff {
    background-color: #17a2b8 !important;
    color: white !important;
    font-weight: 500;
}

/* Resource Status Badges */
.resource-status-available {
    background-color: #28a745 !important;
    color: white !important;
    font-weight: 500;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
}

.resource-status-in_use {
    background-color: #ffc107 !important;
    color: #212529 !important;
    font-weight: 500;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
}

.resource-status-maintenance {
    background-color: #dc3545 !important;
    color: white !important;
    font-weight: 500;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
}

/* Resource Type Avatar Backgrounds */
.resource-type-bed {
    background-color: #007bff !important;
}

.resource-type-equipment {
    background-color: #28a745 !important;
}

.resource-type-staff {
    background-color: #17a2b8 !important;
}

/* Action Buttons */
.btn-action {
    border-width: 1px;
    font-weight: 500;
    transition: all 0.2s ease-in-out;
}

.btn-outline-primary.btn-action {
    color: #007bff !important;
    border-color: #007bff;
}

.btn-outline-primary.btn-action:hover {
    background-color: #007bff !important;
    color: white !important;
}

.btn-outline-danger.btn-action {
    color: #dc3545 !important;
    border-color: #dc3545;
}

.btn-outline-danger.btn-action:hover {
    background-color: #dc3545 !important;
    color: white !important;
}

.btn-outline-secondary.btn-action {
    color: #6c757d !important;
    border-color: #6c757d;
}

.btn-outline-secondary.btn-action:hover {
    background-color: #6c757d !important;
    color: white !important;
}

/* Button Group Styles */
.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

/* Avatar Styles */
.avatar-sm {
    width: 32px;
    height: 32px;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

/* Badge General Styles */
.badge {
    font-size: 0.75rem;
    font-weight: 500;
    display: inline-block;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
}

/* Filter animations */
.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr.filtered-out {
    opacity: 0.3;
    transform: scale(0.98);
}

/* Loading states */
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

/* Dropdown Menu Icons */
.dropdown-item i {
    width: 16px;
    text-align: center;
}

/* Responsive Design */
@media (max-width: 768px) {
    .btn-group-sm .btn {
        padding: 0.2rem 0.4rem;
        font-size: 0.7rem;
    }
    
    .avatar-sm {
        width: 28px;
        height: 28px;
    }
    
    .table td {
        font-size: 0.8rem;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
}

/* Ensure proper contrast */
.text-white {
    color: #ffffff !important;
}

.text-primary {
    color: #007bff !important;
}

.text-success {
    color: #28a745 !important;
}

.text-warning {
    color: #ffc107 !important;
}

.text-danger {
    color: #dc3545 !important;
}

.text-info {
    color: #17a2b8 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize filters
    initializeFilters();
});

function initializeFilters() {
    const searchInput = document.getElementById('searchResources');
    const typeFilter = document.getElementById('typeFilter');
    const statusFilter = document.getElementById('statusFilter');
    
    // Add event listeners
    searchInput.addEventListener('input', filterResources);
    typeFilter.addEventListener('change', filterResources);
    statusFilter.addEventListener('change', filterResources);
}

function filterResources() {
    const searchTerm = document.getElementById('searchResources').value.toLowerCase();
    const typeFilter = document.getElementById('typeFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    
    const rows = document.querySelectorAll('#resourcesTable tbody tr[data-resource-id]');
    
    rows.forEach(row => {
        const name = row.querySelector('td:first-child').textContent.toLowerCase();
        const type = row.dataset.type;
        const status = row.dataset.status;
        
        const matchesSearch = name.includes(searchTerm);
        const matchesType = !typeFilter || type === typeFilter;
        const matchesStatus = !statusFilter || status === statusFilter;
        
        if (matchesSearch && matchesType && matchesStatus) {
            row.style.display = '';
            row.classList.remove('filtered-out');
        } else {
            row.style.display = 'none';
            row.classList.add('filtered-out');
        }
    });
}

function editResource(resourceId) {
    // Find the resource row
    const row = document.querySelector(`tr[data-resource-id="${resourceId}"]`);
    if (!row) return;
    
    // Extract current values
    const name = row.querySelector('td:first-child h6').textContent;
    const type = row.dataset.type;
    const status = row.dataset.status;
    const location = row.querySelector('td:nth-child(3)').textContent.trim().replace('📍', '').trim();
    
    // Populate modal
    document.getElementById('editResourceId').value = resourceId;
    document.getElementById('editResourceName').value = name;
    document.getElementById('editResourceType').value = type;
    document.getElementById('editResourceStatus').value = status;
    document.getElementById('editResourceLocation').value = location;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('editResourceModal'));
    modal.show();
}

function updateResource() {
    const resourceId = document.getElementById('editResourceId').value;
    const formData = new FormData();
    
    formData.append('name', document.getElementById('editResourceName').value);
    formData.append('type', document.getElementById('editResourceType').value);
    formData.append('location', document.getElementById('editResourceLocation').value);
    formData.append('status', document.getElementById('editResourceStatus').value);
    formData.append('description', document.getElementById('editResourceDescription').value);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('_method', 'PUT');
    
    fetch(`/admin/resources/${resourceId}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            bootstrap.Modal.getInstance(document.getElementById('editResourceModal')).hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error updating resource:', error);
        showAlert('error', 'Failed to update resource');
    });
}

function deleteResource(resourceId) {
    if (!confirm('Are you sure you want to delete this resource? This action cannot be undone.')) {
        return;
    }
    
    fetch(`/admin/resources/${resourceId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            // Remove the row from table
            const row = document.querySelector(`tr[data-resource-id="${resourceId}"]`);
            if (row) {
                row.remove();
            }
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error deleting resource:', error);
        showAlert('error', 'Failed to delete resource');
    });
}

function changeResourceStatus(resourceId, newStatus) {
    const formData = new FormData();
    formData.append('status', newStatus);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('_method', 'PUT');
    
    fetch(`/admin/resources/${resourceId}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', `Resource status updated to ${newStatus.replace('_', ' ')}`);
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error updating resource status:', error);
        showAlert('error', 'Failed to update resource status');
    });
}

function viewResourceHistory(resourceId) {
    // This would open a modal showing resource usage history
    showAlert('info', 'Resource history feature coming soon!');
}

function refreshResources() {
    location.reload();
}

function exportResources() {
    // This would export resources to CSV/Excel
    showAlert('info', 'Export feature coming soon!');
}

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