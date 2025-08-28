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

<body id="body" class="auth-page" style="background-image: url('{{ url('assets/images/p-1.png') }}'); background-size: cover; background-position: center center;">


    @yield('content')
    
    <script src="{{ url('assets/libs/bootstrap/js/bootstrap.bundle.min.js?v=' .env('CACHE_VERSION')) }}"></script>
    <script src="{{ url('assets/libs/simplebar/simplebar.min.js?v=' .env('CACHE_VERSION')) }}"></script>
    <script src="{{ url('assets/libs/feather-icons/feather.min.js?v=' .env('CACHE_VERSION')) }}"></script>
    <!-- App js -->
    <script src="{{ url('assets/js/app.js?v=' .env('CACHE_VERSION')) }}"></script>
</body>
</html>