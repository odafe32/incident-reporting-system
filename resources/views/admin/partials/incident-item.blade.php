<div class="incident-item" data-incident-id="{{ $incident->id }}" data-severity="{{ $incident->severity }}" data-status="{{ $incident->status }}">
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
                <span class="badge {{ $incident->getStatusBadgeClass() }}" style="color: #0b51b7;">
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
                    <span>{{ $incident->reporter->name }} ({{ $incident->reporter->getDisplayRole() }})</span>
                </div>
                @if($incident->assignedUser)
                    <div class="meta-item assigned">
                        <i class="mdi mdi-account-check text-success"></i>
                        <span>Assigned to {{ $incident->assignedUser->name }}</span>
                    </div>
                @else
                    <div class="meta-item unassigned">
                        <i class="mdi mdi-account-alert text-warning"></i>
                        <span>Unassigned</span>
                    </div>
                @endif
                @if($incident->actions()->where('action_type', 'message')->count() > 0)
                    <div class="meta-item">
                        <i class="mdi mdi-message text-info"></i>
                        <span>{{ $incident->actions()->where('action_type', 'message')->count() }} messages</span>
                    </div>
                @endif
            </div>
            <div class="incident-time">
                {{ $incident->getTimeElapsed() }}
                @if($incident->isCritical())
                    <span class="badge bg-danger badge-sm ms-2">URGENT</span>
                @endif
            </div>
        </div>
    </div>
</div>
