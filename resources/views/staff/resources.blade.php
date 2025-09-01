@extends('layouts.staff')
@section('content')

<div class="container-fluid">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-end">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="refreshResources">
                            <i class="mdi mdi-refresh me-1"></i> Refresh
                        </button>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="mdi mdi-filter me-1"></i> Filter
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" data-filter="all">All Resources</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="available">Available Only</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="in_use">In Use Only</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="maintenance">Maintenance</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <h4 class="page-title">
                    <i class="mdi mdi-hospital-box me-2"></i>Hospital Resources
                </h4>
            </div>
        </div>
    </div>

    <!-- Resource Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-soft-primary">
                                <span class="avatar-title bg-primary rounded-circle">
                                    <i class="mdi mdi-bed-empty font-16 text-white"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-13">Total Beds</h6>
                            <b class="font-18">{{ $resources->where('type', 'bed')->count() }}</b>
                            <small class="text-success">
                                {{ $resources->where('type', 'bed')->where('status', 'available')->count() }} Available
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-soft-info">
                                <span class="avatar-title bg-info rounded-circle">
                                    <i class="mdi mdi-medical-bag font-16 text-white"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-13">Equipment</h6>
                            <b class="font-18">{{ $resources->where('type', 'equipment')->count() }}</b>
                            <small class="text-success">
                                {{ $resources->where('type', 'equipment')->where('status', 'available')->count() }} Available
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-soft-success">
                                <span class="avatar-title bg-success rounded-circle">
                                    <i class="mdi mdi-account-group font-16 text-white"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-13">Staff Resources</h6>
                            <b class="font-18">{{ $resources->where('type', 'staff')->count() }}</b>
                            <small class="text-success">
                                {{ $resources->where('type', 'staff')->where('status', 'available')->count() }} Available
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-soft-warning">
                                <span class="avatar-title bg-warning rounded-circle">
                                    <i class="mdi mdi-tools font-16 text-white"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-13">In Maintenance</h6>
                            <b class="font-18">{{ $resources->where('status', 'maintenance')->count() }}</b>
                            <small class="text-danger">
                                Needs Attention
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Bar -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body py-2">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="position-relative">
                                <input type="text" id="resourceSearch" class="form-control" 
                                       placeholder="Search resources by name, type, or location...">
                                <i class="mdi mdi-magnify position-absolute" 
                                   style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2">
                                <select id="typeFilter" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="bed">Beds</option>
                                    <option value="equipment">Equipment</option>
                                    <option value="staff">Staff</option>
                                </select>
                                <select id="statusFilter" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="available">Available</option>
                                    <option value="in_use">In Use</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resources Tabs -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs nav-justified" id="resourceTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-resources-tab" data-bs-toggle="tab" 
                                    data-bs-target="#all-resources" type="button" role="tab">
                                <i class="mdi mdi-view-grid me-1"></i>
                                All Resources
                                <span class="badge bg-primary ms-1">{{ $resources->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="beds-tab" data-bs-toggle="tab" 
                                    data-bs-target="#beds" type="button" role="tab">
                                <i class="mdi mdi-bed-empty me-1"></i>
                                Beds
                                <span class="badge bg-info ms-1">{{ $resources->where('type', 'bed')->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="equipment-tab" data-bs-toggle="tab" 
                                    data-bs-target="#equipment" type="button" role="tab">
                                <i class="mdi mdi-medical-bag me-1"></i>
                                Equipment
                                <span class="badge bg-warning ms-1">{{ $resources->where('type', 'equipment')->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="staff-resources-tab" data-bs-toggle="tab" 
                                    data-bs-target="#staff-resources" type="button" role="tab">
                                <i class="mdi mdi-account-group me-1"></i>
                                Staff
                                <span class="badge bg-success ms-1">{{ $resources->where('type', 'staff')->count() }}</span>
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="resourceTabContent">
                        <!-- All Resources Tab -->
                        <div class="tab-pane fade show active" id="all-resources" role="tabpanel">
                            <div class="p-3">
                                <div class="row" id="allResourcesContainer">
                                    @forelse($resources as $resource)
                                        @include('staff.partials.resource-card', ['resource' => $resource])
                                    @empty
                                        <div class="col-12">
                                            <div class="text-center py-5">
                                                <i class="mdi mdi-hospital-box display-4 text-muted"></i>
                                                <h5 class="mt-3 text-muted">No Resources Found</h5>
                                                <p class="text-muted">No resources are currently available in the system.</p>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Beds Tab -->
                        <div class="tab-pane fade" id="beds" role="tabpanel">
                            <div class="p-3">
                                <div class="row">
                                    @forelse($resources->where('type', 'bed') as $resource)
                                        @include('staff.partials.resource-card', ['resource' => $resource])
                                    @empty
                                        <div class="col-12">
                                            <div class="text-center py-5">
                                                <i class="mdi mdi-bed-empty display-4 text-muted"></i>
                                                <h5 class="mt-3 text-muted">No Beds Available</h5>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Equipment Tab -->
                        <div class="tab-pane fade" id="equipment" role="tabpanel">
                            <div class="p-3">
                                <div class="row">
                                    @forelse($resources->where('type', 'equipment') as $resource)
                                        @include('staff.partials.resource-card', ['resource' => $resource])
                                    @empty
                                        <div class="col-12">
                                            <div class="text-center py-5">
                                                <i class="mdi mdi-medical-bag display-4 text-muted"></i>
                                                <h5 class="mt-3 text-muted">No Equipment Available</h5>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Staff Resources Tab -->
                        <div class="tab-pane fade" id="staff-resources" role="tabpanel">
                            <div class="p-3">
                                <div class="row">
                                    @forelse($resources->where('type', 'staff') as $resource)
                                        @include('staff.partials.resource-card', ['resource' => $resource])
                                    @empty
                                        <div class="col-12">
                                            <div class="text-center py-5">
                                                <i class="mdi mdi-account-group display-4 text-muted"></i>
                                                <h5 class="mt-3 text-muted">No Staff Resources Available</h5>
                                            </div>
                                        </div>
                                    @endforelse
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
.resource-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.resource-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.resource-status-indicator {
    width: 4px;
    height: 100%;
    position: absolute;
    left: 0;
    top: 0;
    border-radius: 8px 0 0 8px;
}

.status-available { background-color: #28a745; }
.status-in_use { background-color: #ffc107; }
.status-maintenance { background-color: #dc3545; }

.resource-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.icon-bed { background: linear-gradient(135deg, #007bff, #0056b3); }
.icon-equipment { background: linear-gradient(135deg, #17a2b8, #138496); }
.icon-staff { background: linear-gradient(135deg, #28a745, #1e7e34); }

.nav-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    color: #6c757d;
    font-weight: 500;
    padding: 1rem 1.5rem;
}

.nav-tabs .nav-link.active {
    background: none;
    border-bottom-color: #007bff;
    color: #007bff;
}

.nav-tabs .nav-link:hover {
    border-bottom-color: #dee2e6;
    background: #f8f9fa;
}

.search-highlight {
    background-color: #fff3cd;
    padding: 0.1rem 0.2rem;
    border-radius: 3px;
}

@media (max-width: 768px) {
    .resource-card {
        margin-bottom: 0.75rem;
    }
    
    .nav-tabs .nav-link {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize search and filter functionality
    initializeResourceFilters();
    
    // Refresh resources
    document.getElementById('refreshResources').addEventListener('click', function() {
        location.reload();
    });
});

function initializeResourceFilters() {
    const searchInput = document.getElementById('resourceSearch');
    const typeFilter = document.getElementById('typeFilter');
    const statusFilter = document.getElementById('statusFilter');
    
    // Search functionality
    searchInput.addEventListener('input', function() {
        filterResources();
    });
    
    // Filter functionality
    typeFilter.addEventListener('change', function() {
        filterResources();
    });
    
    statusFilter.addEventListener('change', function() {
        filterResources();
    });
    
    // Filter dropdown buttons
    document.querySelectorAll('[data-filter]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const filter = this.dataset.filter;
            statusFilter.value = filter === 'all' ? '' : filter;
            filterResources();
        });
    });
}

function filterResources() {
    const searchTerm = document.getElementById('resourceSearch').value.toLowerCase();
    const typeFilter = document.getElementById('typeFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    
    const resourceCards = document.querySelectorAll('.resource-card');
    let visibleCount = 0;
    
    resourceCards.forEach(card => {
        const name = card.dataset.name?.toLowerCase() || '';
        const type = card.dataset.type || '';
        const status = card.dataset.status || '';
        const location = card.dataset.location?.toLowerCase() || '';
        
        const matchesSearch = !searchTerm || 
            name.includes(searchTerm) || 
            type.includes(searchTerm) || 
            location.includes(searchTerm);
            
        const matchesType = !typeFilter || type === typeFilter;
        const matchesStatus = !statusFilter || status === statusFilter;
        
        if (matchesSearch && matchesType && matchesStatus) {
            card.style.display = 'block';
            visibleCount++;
            
            // Highlight search terms
            if (searchTerm) {
                highlightSearchTerm(card, searchTerm);
            } else {
                removeHighlight(card);
            }
        } else {
            card.style.display = 'none';
        }
    });
    
    // Show/hide empty state
    updateEmptyState(visibleCount);
}

function highlightSearchTerm(card, term) {
    const textElements = card.querySelectorAll('.resource-name, .resource-location');
    textElements.forEach(element => {
        const text = element.textContent;
        const regex = new RegExp(`(${term})`, 'gi');
        element.innerHTML = text.replace(regex, '<span class="search-highlight">$1</span>');
    });
}

function removeHighlight(card) {
    const highlightedElements = card.querySelectorAll('.search-highlight');
    highlightedElements.forEach(element => {
        element.outerHTML = element.textContent;
    });
}

function updateEmptyState(visibleCount) {
    const container = document.getElementById('allResourcesContainer');
    let emptyState = container.querySelector('.empty-state');
    
    if (visibleCount === 0) {
        if (!emptyState) {
            emptyState = document.createElement('div');
            emptyState.className = 'col-12 empty-state';
            emptyState.innerHTML = `
                <div class="text-center py-5">
                    <i class="mdi mdi-magnify display-4 text-muted"></i>
                    <h5 class="mt-3 text-muted">No Resources Found</h5>
                    <p class="text-muted">Try adjusting your search or filter criteria.</p>
                </div>
            `;
            container.appendChild(emptyState);
        }
    } else if (emptyState) {
        emptyState.remove();
    }
}
</script>

@endsection