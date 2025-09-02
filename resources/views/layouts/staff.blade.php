<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />

  <!-- Title -->
  <title>{{ $meta_title ?? 'Metrica' }}</title>

  <!-- Primary Meta Tags -->
  <meta name="title" content="{{ $meta_title ?? 'Metrica - Hospital Incident Management & Resource Allocation System' }}" />
  <meta name="description" content="{{ $meta_desc ?? 'Metrica is a hospital incident management and resource allocation system that streamlines incident reporting, resource allocation, and real-time emergency response.' }}" />
  <meta name="keywords" content="Metrica, hospital management, incident reporting, resource allocation, healthcare dashboard, emergency response system" />
  <meta name="author" content="Odafe Godfrey" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="{{ url()->current() }}" />
  <meta property="og:title" content="{{ $meta_title ?? 'Metrica - Hospital Incident Management & Resource Allocation System' }}" />
  <meta property="og:description" content="{{ $meta_desc ?? 'Streamline hospital operations with real-time incident reporting, automated resource allocation, and actionable analytics.' }}" />
  <meta property="og:image" content="{{ $meta_image ?? url('assets/images/preview.png') }}" />

  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="{{ $meta_title ?? 'Metrica - Hospital Incident Management & Resource Allocation System' }}" />
  <meta name="twitter:description" content="{{ $meta_desc ?? 'Real-time hospital incident reporting and resource allocation platform.' }}" />
  <meta name="twitter:image" content="{{ $meta_image ?? url('assets/images/preview.png') }}" />

  <!-- Generator -->
  <meta name="generator" content="Metrica Dashboard" />

  <!-- App favicon -->
  <link rel="shortcut icon" href="{{ url('assets/images/favicon.ico') }}">

  <!-- App css -->
  <link href="{{ url('assets/css/bootstrap.min.css?v=' .env('CACHE_VERSION')) }}" rel="stylesheet" type="text/css" />
  <link href="{{ url('assets/css/icons.min.css?v=' .env('CACHE_VERSION')) }}" rel="stylesheet" type="text/css" />
  <link href="{{ url('assets/css/app.min.css?v=' .env('CACHE_VERSION')) }}" rel="stylesheet" type="text/css" />
 <style>
.nav-link.active {
    background-color: rgba(13, 110, 253, 0.1) !important;
    color: #0d6efd !important;
    font-weight: 600;
}

/* Fixed notification badge styling */
.notification-list {
    position: relative;
}

.alert-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    min-width: 18px;
    height: 18px;
    font-size: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    line-height: 1;
    padding: 0 4px;
    z-index: 10;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

/* Ensure the notification icon container has relative positioning */
.nav-icon {
    position: relative;
    display: inline-block;
}

/* Better notification dropdown styling */
.notification-menu {
    max-height: 300px;
    overflow-y: auto;
}

.notification-menu .dropdown-item {
    border-bottom: 1px solid #f8f9fa;
    transition: background-color 0.2s ease;
}

.notification-menu .dropdown-item:hover {
    background-color: #f8f9fa;
}

.notification-menu .dropdown-item.bg-light {
    background-color: #e3f2fd !important;
    border-left: 3px solid #2196f3;
}

.avatar-md {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-right: 12px;
}

.bg-soft-primary {
    background-color: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
}

.media {
    display: flex;
    align-items: flex-start;
}

.media-body {
    flex: 1;
}

.badge-sm {
    font-size: 0.65rem;
    padding: 0.2rem 0.4rem;
}
.notification-pulse {
    animation: notificationPulse 2s ease-in-out;
}

@keyframes notificationPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

/* Subtle update indicators */
.update-indicator {
    font-size: 12px;
    font-weight: 500;
}

/* Smooth transitions for incident updates */
.incident-item {
    transition: all 0.3s ease;
}

.incident-item.updated {
    background-color: rgba(0, 123, 255, 0.05);
    border-left: 3px solid #007bff;
}

/* Chat message updates */
.message-item.new-message {
    animation: slideInMessage 0.5s ease-out;
}

@keyframes slideInMessage {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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
    opacity: 0;
    transition: opacity 0.3s ease;
}

.loading-overlay.show {
    opacity: 1;
}
</style>
</head>

<body id="body">
         <!-- leftbar-tab-menu -->
        <div class="leftbar-tab-menu">
            <div class="main-icon-menu">
                <a href="{{ route('staff.dashboard') }}" class="logo logo-metrica d-block text-center">
                    <span>
                        <img src="{{ url('assets/images/logo-sm.png') }}" alt="logo-small" class="logo-sm">
                    </span>
                </a>
                <div class="main-icon-menu-body">
                    <div class="position-reletive h-100" data-simplebar style="overflow-x: hidden;">
                        <ul class="nav nav-tabs" role="tablist" id="tab-menu">
                            <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboard" data-bs-trigger="hover">
                                <a href="#MetricaDashboard" id="dashboard-tab" class="nav-link">
                                    <i class="ti ti-smart-home menu-icon"></i>
                                </a><!--end nav-link-->
                            </li><!--end nav-item-->
                 
                        </ul><!--end nav-->
                    </div><!--end /div-->
                </div><!--end main-icon-menu-body-->
                <div class="pro-metrica-end">
                    <a href="{{ route('staff.profile') }}" class="profile">
                        <img src="{{ url('assets/images/users/user-4.jpg') }}" alt="profile-user" class="rounded-circle thumb-sm">
                    </a>
                </div><!--end pro-metrica-end-->
            </div>
            <!--end main-icon-menu-->

            <div class="main-menu-inner">
                <!-- LOGO -->
                <div class="topbar-left">
                    <a href="{{ route('staff.dashboard') }}" class="logo">
                        <span>
                            <img src="{{ url('assets/images/logo-dark.png') }}" alt="logo-large" class="logo-lg logo-dark">
                            <img src="{{ url('assets/images/logo.png') }}" alt="logo-large" class="logo-lg logo-light">
                        </span>
                    </a><!--end logo-->
                </div><!--end topbar-left-->
                <!--end logo-->
                <div class="menu-body navbar-vertical tab-content" data-simplebar>
                    <div id="MetricaDashboard" class="main-icon-menu-pane tab-pane" role="tabpanel"
                        aria-labelledby="dasboard-tab">
                        <div class="title-box">
                            <h6 class="menu-title">Dashboard</h6>
                        </div>

                       <ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}" href="{{ route('staff.dashboard') }}">Dashboard</a>
    </li><!--end nav-item-->
    
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('staff.incidents*') ? 'active' : '' }}" href="#sidebarINCIDENT" data-bs-toggle="collapse" role="button"
            aria-expanded="{{ request()->routeIs('staff.incidents*') ? 'true' : 'false' }}" aria-controls="sidebarINCIDENT">
       Incidents <span class="menu-arrow"></span>
        </a>
        <div class="collapse {{ request()->routeIs('staff.incidents*') ? 'show' : '' }}" id="sidebarINCIDENT">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('staff.incidents') && !request()->has('tab') ? 'active' : '' }}" href="{{ route('staff.incidents') }}">All Incidents</a>
                </li><!--end nav-item-->
                <li class="nav-item">
                    <a class="nav-link {{ request()->get('tab') == 'report' ? 'active' : '' }}" href="{{ route('staff.incidents') }}?tab=report">Report Incidents</a>
                </li><!--end nav-item-->
                <li class="nav-item">
                    <a class="nav-link {{ request()->get('tab') == 'assigned' ? 'active' : '' }}" href="{{ route('staff.incidents') }}?tab=assigned">Assigned Incidents</a>
                </li><!--end nav-item-->
            </ul><!--end nav-->
        </div><!--end sidebarINCIDENT-->
    </li><!--end nav-item-->

    
</ul><!--end nav-->
                    </div><!-- end Dashboards -->

        
                </div>
                <!--end menu-body-->
            </div><!-- end main-menu-inner-->
        </div>
        <!-- end leftbar-tab-menu-->

        <!-- Top Bar Start -->
        <!-- Top Bar Start -->
        <div class="topbar">            
            <!-- Navbar -->
            <nav class="navbar-custom" id="navbar-custom">    
                <ul class="list-unstyled topbar-nav float-end mb-0">
             
               
      <li class="dropdown notification-list">
    <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#" role="button"
        aria-haspopup="false" aria-expanded="false" id="notificationDropdown">
        <i class="ti ti-bell" style="font-size: 18px;"></i>
        <span class="alert-badge" id="notificationBadge" style="display: none;">0</span>
    </a>
    <div class="dropdown-menu dropdown-menu-end dropdown-lg pt-0" style="width: 320px;">
        <h6 class="dropdown-item-text font-15 m-0 py-3 border-bottom d-flex justify-content-between align-items-center">
            Notifications 
            <span class="badge bg-primary badge-pill" id="notificationCount">0</span>
        </h6> 
        <div class="notification-menu" data-simplebar id="notificationList" style="max-height: 300px;">
            <div class="text-center py-4">
                <i class="mdi mdi-bell-off display-4 text-muted"></i>
                <p class="text-muted mt-2">Loading notifications...</p>
            </div>
        </div>
        <!-- All-->
        <a href="{{ route('staff.notifications') }}" class="dropdown-item text-center text-primary border-top">
            View all <i class="mdi mdi-arrow-right"></i>
        </a>
    </div>
</li>

                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle nav-user" data-bs-toggle="dropdown" href="#" role="button"
                            aria-haspopup="false" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                  @if($user->profile_image)
                                    <img src="{{ Storage::url($user->profile_image) }}" alt="Profile Image" class="rounded-circle me-2 thumb-sm" id="profilePreview">
                                @else
                                    <img src="{{ url('empty.svg') }}" alt="profile-user" class="rounded-circle me-2 thumb-sm" />
                                @endif
                              
                                <div>
                                    <small class="d-none d-md-block font-11">{{ auth()->user()->getDisplayRole() }}</small>
                                    <span class="d-none d-md-block fw-semibold font-12">{{ auth()->user()->name }} <i
                                            class="mdi mdi-chevron-down"></i></span>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="{{ route('staff.profile') }}"><i class="ti ti-user font-16 me-1 align-text-bottom"></i> Profile</a>
                            

                            <div class="dropdown-divider mb-0"></div>
                        
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item" style="border: none; ">
                                  <i class="ti ti-power font-16 me-1 align-text-bottom"></i>  Logout
                                </button>
                            </form>

                        </div>
                    </li><!--end topbar-profile-->
               
                </ul><!--end topbar-nav-->

                <ul class="list-unstyled topbar-nav mb-0">                        
                    <li>
                        <button class="nav-link button-menu-mobile nav-icon" id="togglemenu">
                            <i class="ti ti-menu-2"></i>
                        </button>
                    </li> 
                                 
                </ul>
            </nav>
            <!-- end navbar-->
        </div>
        <!-- Top Bar End -->
        <!-- Top Bar End -->

        <!-- Main Content Area -->
        <div class="page-wrapper">
            <div class="page-content-tab">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show m-3" role="alert" style="position: relative; z-index: 1050;">
                        <i class="ti ti-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert" style="position: relative; z-index: 1050;">
                        <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show m-3" role="alert" style="position: relative; z-index: 1050;">
                        <i class="ti ti-alert-triangle me-2"></i>{{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info alert-dismissible fade show m-3" role="alert" style="position: relative; z-index: 1050;">
                        <i class="ti ti-info-circle me-2"></i>{{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
                   
                <!--Start Footer-->
                <!-- Footer Start -->
                <footer class="footer text-center text-sm-start">
                    &copy; <script>
                        document.write(new Date().getFullYear())
                    </script> Metrica <span class="text-muted d-none d-sm-inline-block float-end">Crafted with <i
                            class="mdi mdi-heart text-danger"></i> by Odafe Godfrey </span>
                </footer>
                <!-- end Footer -->                
                <!--end footer-->
            </div>
        </div>
    
    <script src="{{ url('assets/libs/bootstrap/js/bootstrap.bundle.min.js?v=' .env('CACHE_VERSION')) }}"></script>
    <script src="{{ url('assets/libs/simplebar/simplebar.min.js?v=' .env('CACHE_VERSION')) }}"></script>
    <script src="{{ url('assets/libs/feather-icons/feather.min.js?v=' .env('CACHE_VERSION')) }}"></script>
    <!-- App js -->
    <script src="{{ url('assets/js/app.js?v=' .env('CACHE_VERSION')) }}"></script>

    <!-- Auto-hide success alerts -->
<script>
let notificationRefreshInterval;
let lastNotificationCount = 0;

document.addEventListener('DOMContentLoaded', function() {
    // Load notifications on page load
    loadNotifications();
    
    // Start auto-refresh every 30 seconds
    startNotificationAutoRefresh();
    
    // Load notifications when dropdown is clicked
    document.getElementById('notificationDropdown').addEventListener('click', function() {
        loadNotifications();
    });
    
    // Pause auto-refresh when user is interacting with notifications
    const notificationDropdown = document.querySelector('.notification-list .dropdown-menu');
    if (notificationDropdown) {
        notificationDropdown.addEventListener('show.bs.dropdown', function() {
            pauseNotificationAutoRefresh();
        });
        
        notificationDropdown.addEventListener('hide.bs.dropdown', function() {
            startNotificationAutoRefresh();
        });
    }
});

function startNotificationAutoRefresh() {
    // Clear existing interval
    if (notificationRefreshInterval) {
        clearInterval(notificationRefreshInterval);
    }
    
    // Start new interval
    notificationRefreshInterval = setInterval(function() {
        loadNotifications(true); // true = silent refresh
    }, 30000); // 30 seconds
}

function pauseNotificationAutoRefresh() {
    if (notificationRefreshInterval) {
        clearInterval(notificationRefreshInterval);
        notificationRefreshInterval = null;
    }
}

function loadNotifications(silent = false) {
    fetch('{{ route("staff.notifications") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateNotificationUI(data.notifications, data.unread_count, silent);
            }
        })
        .catch(error => {
            if (!silent) {
                console.error('Error loading notifications:', error);
            }
        });
}

function updateNotificationUI(notifications, unreadCount, silent = false) {
    // Update badge
    const badge = document.getElementById('notificationBadge');
    const countSpan = document.getElementById('notificationCount');
    
    // Show subtle animation for new notifications (only if not silent)
    if (!silent && unreadCount > lastNotificationCount && lastNotificationCount > 0) {
        showNewNotificationIndicator();
    }
    
    lastNotificationCount = unreadCount;
    
    if (unreadCount > 0) {
        badge.style.display = 'flex';
        badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
        countSpan.textContent = unreadCount;
        countSpan.className = 'badge bg-danger badge-pill';
        
        // Subtle pulse animation for new notifications
        if (!silent && unreadCount > 0) {
            badge.classList.add('notification-pulse');
            setTimeout(() => badge.classList.remove('notification-pulse'), 2000);
        }
    } else {
        badge.style.display = 'none';
        countSpan.textContent = '0';
        countSpan.className = 'badge bg-secondary badge-pill';
    }
    
    // Update notification list (only if dropdown is not open)
    const dropdown = document.querySelector('.notification-list .dropdown-menu');
    const isDropdownOpen = dropdown && dropdown.classList.contains('show');
    
    if (!isDropdownOpen) {
        updateNotificationList(notifications);
    }
}

function updateNotificationList(notifications) {
    const notificationList = document.getElementById('notificationList');
    let notificationsHtml = '';
    
    if (notifications.length > 0) {
        notifications.forEach(notification => {
            const isUnread = !notification.is_read;
            
            notificationsHtml += `
                <a href="#" class="dropdown-item py-3 ${isUnread ? 'bg-light' : ''}" 
                   onclick="markAsRead('${notification.id}', '${notification.incident?.id || ''}')"
                   style="text-decoration: none;">
                    <div class="d-flex">
                        <div class="avatar-md bg-soft-primary flex-shrink-0">
                            <i class="mdi ${notification.icon}"></i>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <h6 class="my-0 fw-normal text-dark font-13">${notification.title}</h6>
                                <small class="text-muted">${notification.time_elapsed}</small>
                            </div>
                            <p class="text-muted mb-0 font-12 text-truncate" style="max-width: 200px;">
                                ${notification.message}
                            </p>
                            ${isUnread ? '<span class="badge bg-primary badge-sm mt-1">New</span>' : ''}
                        </div>
                    </div>
                </a>
            `;
        });
    } else {
        notificationsHtml = `
            <div class="text-center py-4">
                <i class="mdi mdi-bell-off display-4 text-muted"></i>
                <p class="text-muted mt-2 mb-0">No notifications yet</p>
            </div>
        `;
    }
    
    notificationList.innerHTML = notificationsHtml;
}

function showNewNotificationIndicator() {
    // Create a subtle toast notification
    const toast = document.createElement('div');
    toast.className = 'toast align-items-center text-white bg-primary border-0 position-fixed';
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="mdi mdi-bell me-2"></i>New notification received
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
    bsToast.show();
    
    // Remove toast after it's hidden
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

// Rest of your existing notification functions...
function markAsRead(notificationId, incidentId = '') {
    fetch(`/staff/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadNotifications(); // Refresh notifications
            
            // If there's an incident ID, redirect to it
            if (incidentId) {
                window.location.href = `{{ route('staff.incidents') }}#incident-${incidentId}`;
            }
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
    });
}
</script>
</body>
</html>
