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
                                    <h4 class="mt-3 mb-1 fw-semibold text-white font-18">Forgot Password?</h4>   
                                    <p class="text-muted mb-0">Enter your email to reset your password.</p>  
                                </div>
                            </div>
                            <div class="card-body pt-0">
                          

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

                                <div class="text-center mb-4">
                                    <div class="avatar-lg mx-auto">
                                        <div class="avatar-title rounded-circle bg-light">
                                            <i class="mdi mdi-lock-reset h1 mb-0 text-primary"></i>
                                        </div>
                                    </div>
                                    <p class="text-muted mt-3">
                                        Don't worry! It happens. Please enter the email address associated with your account.
                                    </p>
                                </div>

                                <form class="my-4" action="{{ route('password.email') }}" method="POST">
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

                                    <div class="form-group mb-0 row">
                                        <div class="col-12">
                                            <div class="d-grid mt-3">
                                                <button class="btn btn-primary btn-lg" type="submit" id="resetBtn">
                                                    <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                                                    <i class="mdi mdi-send me-1"></i> Send Reset Link
                                                </button>
                                            </div>
                                        </div><!--end col--> 
                                    </div> <!--end form-group-->                           
                                </form><!--end form-->

                                <div class="text-center">
                                    <p class="text-muted">
                                        Remember your password? 
                                        <a href="{{ route('login') }}" class="text-primary ms-1">
                                            <i class="mdi mdi-arrow-left"></i> Back to Login
                                        </a>
                                    </p>
                                </div>

                                <!-- Help Section -->
                                <div class="mt-4">
                                    <div class="card bg-light border-0">
                                        <div class="card-body p-3">
                                            <h6 class="card-title mb-2">
                                                <i class="mdi mdi-help-circle text-info"></i> Need Help?
                                            </h6>
                                            <small class="text-muted">
                                                If you don't receive the reset email within a few minutes, please check your spam folder or contact your system administrator.
                                            </small>
                                        </div>
                                    </div>
                                </div>
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
    // Form submission loading state
    const resetForm = document.querySelector('form');
    const resetBtn = document.getElementById('resetBtn');
    const spinner = resetBtn.querySelector('.spinner-border');

    resetForm.addEventListener('submit', function() {
        resetBtn.disabled = true;
        spinner.classList.remove('d-none');
        resetBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Sending...';
    });
});
</script>

@endsection