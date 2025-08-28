@extends('layouts.auth')
@section('content')
<div class="container-md">
    <div class="row vh-100 d-flex justify-content-center">
        <div class="col-12 align-self-center">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 mx-auto">
                        <div class="card">
                            <div class="card-body p-0 auth-header-box">
                                <div class="text-center p-3">
                                    <a href="{{ route('login') }}" class="logo logo-admin">
                                        <img src="{{ url('assets/images/logo-sm.png') }}" height="50" alt="logo" class="auth-logo">
                                    </a>
                                    <h4 class="mt-3 mb-1 fw-semibold text-white font-18">Welcome to Metrica</h4>   
                                    <p class="text-muted mb-0">Sign in to continue to your dashboard.</p>  
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                
                                
    <!-- Display Success Messages -->
                                 @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <!-- Display Status Messages -->
                                @if (session('status'))
                                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                                        <i class="mdi mdi-information me-2"></i>{{ session('status') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <!-- Display Validation Errors -->
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="mdi mdi-alert-circle me-2"></i>
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <form class="my-4" action="{{ route('login') }}" method="POST">
                                    @csrf
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="email">Email Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="mdi mdi-email"></i></span>
                                            <input type="email" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" 
                                                   name="email" 
                                                   value="{{ old('email') }}"
                                                   placeholder="Enter your email address"
                                                   required
                                                   autocomplete="email"
                                                   autofocus>
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div><!--end form-group--> 

                                    <div class="form-group mb-3">
                                        <label class="form-label" for="password">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="mdi mdi-lock"></i></span>
                                            <input type="password" 
                                                   class="form-control @error('password') is-invalid @enderror" 
                                                   name="password" 
                                                   id="password" 
                                                   placeholder="Enter your password"
                                                   required
                                                   autocomplete="current-password">
                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                <i class="mdi mdi-eye" id="toggleIcon"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div><!--end form-group--> 

                                    <div class="form-group row mt-3">
                                        <div class="col-sm-6">
                                            <div class="form-check form-switch form-switch-success">
                                                <input class="form-check-input" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="remember">Remember Me</label>
                                            </div>
                                        </div><!--end col--> 
                                        <div class="col-sm-6 text-end">
                                            <a href="{{ route('password.request') }}" class="text-muted font-13">
                                                <i class="mdi mdi-lock-reset"></i> Forgot password?
                                            </a>                                    
                                        </div><!--end col--> 
                                    </div><!--end form-group--> 

                                    <div class="form-group mb-0 row">
                                        <div class="col-12">
                                            <div class="d-grid mt-3">
                                                <button class="btn btn-primary btn-lg" type="submit" id="loginBtn">
                                                    <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                                                    <i class="mdi mdi-login me-1"></i> Sign In
                                                </button>
                                            </div>
                                        </div><!--end col--> 
                                    </div> <!--end form-group-->                           
                                </form><!--end form-->

                              
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end card-body-->
        </div><!--end col-->
    </div><!--end row-->
</div><!--end container-->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        if (type === 'text') {
            toggleIcon.className = 'mdi mdi-eye-off';
        } else {
            toggleIcon.className = 'mdi mdi-eye';
        }
    });


    // Form submission loading state
    const loginForm = document.querySelector('form');
    const loginBtn = document.getElementById('loginBtn');
    const spinner = loginBtn.querySelector('.spinner-border');

    loginForm.addEventListener('submit', function() {
        loginBtn.disabled = true;
        spinner.classList.remove('d-none');
        loginBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Signing In...';
    });
});
</script>

<style>


.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {

    
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-1px);
}
</style>
@endsection