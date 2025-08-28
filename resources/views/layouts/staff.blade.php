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
                                <a class="nav-link" href="{{ route('staff.dashboard') }}">Dashboard</a>
                            </li><!--end nav-item-->
                           <li class="nav-item">
                                <a class="nav-link" href="#sidebarINCIDENT" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarINCIDENT">
                               Incidents <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse " id="sidebarINCIDENT">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('staff.incidents') }}">Incidents</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('staff.report-incidents') }}">Report Incidents</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('staff.assigned-incidents') }}">Assigned Incidents</a>
                                        </li><!--end nav-item-->
                                        
                                    </ul><!--end nav-->
                                </div><!--end sidebarINCIDENT-->
                            </li><!--end nav-item-->

                             <li class="nav-item">
                                <a class="nav-link" href="{{ route('staff.resources') }}">Resources</a>
                            </li><!--end nav-item-->
                             <li class="nav-item">
                                <a class="nav-link" href="{{ route('staff.notifications') }}">Notifications</a>
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
                            aria-haspopup="false" aria-expanded="false">
                            <i class="ti ti-bell"></i>
                            <span class="alert-badge"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-lg pt-0">
                
                            <h6 class="dropdown-item-text font-15 m-0 py-3 border-bottom d-flex justify-content-between align-items-center">
                                Notifications <span class="badge bg-soft-primary badge-pill">2</span>
                            </h6> 
                            <div class="notification-menu" data-simplebar>
                                <!-- item-->
                                <a href="#" class="dropdown-item py-3">
                                    <small class="float-end text-muted ps-2">2 min ago</small>
                                    <div class="media">
                                        <div class="avatar-md bg-soft-primary">
                                            <i class="ti ti-chart-arcs"></i>
                                        </div>
                                        <div class="media-body align-self-center ms-2 text-truncate">
                                            <h6 class="my-0 fw-normal text-dark">Your order is placed</h6>
                                            <small class="text-muted mb-0">Dummy text of the printing and industry.</small>
                                        </div><!--end media-body-->
                                    </div><!--end media-->
                                </a><!--end-item-->
                                <!-- item-->
                                <a href="#" class="dropdown-item py-3">
                                    <small class="float-end text-muted ps-2">10 min ago</small>
                                    <div class="media">
                                        <div class="avatar-md bg-soft-primary">
                                            <i class="ti ti-device-computer-camera"></i>
                                        </div>
                                        <div class="media-body align-self-center ms-2 text-truncate">
                                            <h6 class="my-0 fw-normal text-dark">Meeting with designers</h6>
                                            <small class="text-muted mb-0">It is a long established fact that a reader.</small>
                                        </div><!--end media-body-->
                                    </div><!--end media-->
                                </a><!--end-item-->
                     
                            </div>
                            <!-- All-->
                            <a href="javascript:void(0);" class="dropdown-item text-center text-primary">
                                View all <i class="fi-arrow-right"></i>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide success alerts after 5 seconds
            const successAlerts = document.querySelectorAll('.alert-success');
            successAlerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });

            // Auto-hide info alerts after 7 seconds
            const infoAlerts = document.querySelectorAll('.alert-info');
            infoAlerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 7000);
            });
        });
    </script>
</body>
</html>
