<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
    <div class="card resource-card position-relative" 
         data-name="{{ $resource->name }}" 
         data-type="{{ $resource->type }}" 
         data-status="{{ $resource->status }}"
         data-location="{{ $resource->location }}">
        
        <!-- Status Indicator -->
        <div class="resource-status-indicator status-{{ $resource->status }}"></div>
        
        <div class="card-body">
            <!-- Resource Header -->
            <div class="d-flex align-items-center mb-3">
                <div class="resource-icon icon-{{ $resource->type }} flex-shrink-0">
                    @switch($resource->type)
                        @case('bed')
                            <i class="mdi mdi-bed-empty"></i>
                            @break
                        @case('equipment')
                            <i class="mdi mdi-medical-bag"></i>
                            @break
                        @case('staff')
                            <i class="mdi mdi-account-group"></i>
                            @break
                        @default
                            <i class="mdi mdi-hospital-box"></i>
                    @endswitch
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-1 resource-name">{{ $resource->name }}</h6>
                    <span class="badge {{ $resource->getTypeBadgeClass() }} badge-sm">
                        {{ $resource->getDisplayType() }}
                    </span>
                </div>
            </div>

            <!-- Resource Details -->
            <div class="resource-details">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">Status:</small>
                    <span class="badge {{ $resource->getStatusBadgeClass() }}">
                        {{ $resource->getDisplayStatus() }}
                    </span>
                </div>

                @if($resource->location)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">Location:</small>
                        <small class="text-dark resource-location">{{ $resource->location }}</small>
                    </div>
                @endif

                @if($resource->description)
                    <div class="mb-2">
                        <small class="text-muted d-block">Description:</small>
                        <small class="text-dark">{{ Str::limit($resource->description, 60) }}</small>
                    </div>
                @endif

                <!-- Current Usage -->
                @if($resource->status === 'in_use' && $resource->currentIncident)
                    <div class="mt-3 p-2 bg-light rounded">
                        <small class="text-muted d-block mb-1">Currently Used By:</small>
                        <div class="d-flex align-items-center">
                            <div class="severity-badge severity-{{ $resource->currentIncident->severity }} me-2" 
                                 style="width: 20px; height: 20px; font-size: 0.7rem;">
                                @switch($resource->currentIncident->severity)
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
                                <small class="text-dark fw-medium d-block">
                                    {{ Str::limit($resource->currentIncident->title, 30) }}
                                </small>
                                <small class="text-muted">
                                    {{ $resource->currentIncident->location }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endif

                @if($resource->status === 'maintenance')
                    <div class="mt-3 p-2 bg-danger bg-opacity-10 rounded">
                        <small class="text-danger">
                            <i class="mdi mdi-tools me-1"></i>
                            Under Maintenance
                        </small>
                    </div>
                @endif
            </div>

            <!-- Resource Actions -->
           
        </div>

        <!-- Availability Indicator -->
        <div class="position-absolute top-0 end-0 m-2">
            @if($resource->isAvailable())
                <span class="badge bg-success rounded-pill">
                    <i class="mdi mdi-check-circle me-1"></i>Available
                </span>
            @elseif($resource->status === 'in_use')
                <span class="badge bg-warning rounded-pill">
                    <i class="mdi mdi-clock me-1"></i>In Use
                </span>
            @else
                <span class="badge bg-danger rounded-pill">
                    <i class="mdi mdi-tools me-1"></i>Maintenance
                </span>
            @endif
        </div>
    </div>
</div>

<style>
.severity-badge {
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.8rem;
}

.severity-critical { background: linear-gradient(135deg, #d32f2f, #f44336); }
.severity-high { background: linear-gradient(135deg, #f57c00, #ff9800); }
.severity-medium { background: linear-gradient(135deg, #fbc02d, #ffeb3b); color: #333; }
.severity-low { background: linear-gradient(135deg, #388e3c, #4caf50); }
</style>

<script>
function viewResourceDetails(resourceId) {
    // You can implement a modal or redirect to detailed view
    console.log('View details for resource:', resourceId);
    // Example: window.location.href = `/staff/resources/${resourceId}`;
}

function requestResource(resourceId) {
    // You can implement resource request functionality
    console.log('Request resource:', resourceId);
    // Example: Show modal to request resource for an incident
}
</script>