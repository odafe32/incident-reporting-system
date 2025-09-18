@extends('layouts.admin')
@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Profile</h4>
                    
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4">
                <!-- Profile Card -->
                <div class="card profile-card">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="profile-avatar-container mb-3">
                                @if($user->profile_image)
                                    <img src="{{ Storage::url($user->profile_image) }}" alt="Profile Image" class="profile-avatar" id="profilePreview">
                                @else
                                  <img src="{{ url('empty.svg') }}" alt="profile-user" class="rounded-circle me-2 thumb-sm" />
                                @endif
                                <div class="profile-avatar-overlay">
                                    <i class="mdi mdi-camera"></i>
                                </div>
                            </div>
                            <h5 class="font-size-16 mb-1 text-dark text-white" style="color: white;" >{{ $user->name }}</h5>
                            <p class="text-muted mb-2">{{ $user->getDisplayRole() }}</p>
                            @if($user->department)
                                <div class="department-badge">
                                    <i class="mdi mdi-hospital-building me-1"></i>{{ $user->department }} Department
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Account Info -->
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="mdi mdi-account-circle me-2 text-primary"></i>Account Information
                        </h5>
                        <div class="info-list">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="mdi mdi-email"></i>
                                    Email
                                </div>
                                <div class="info-value">{{ $user->email }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="mdi mdi-account-badge"></i>
                                    Role
                                </div>
                                <div class="info-value">
                                    <span class="role-badge role-{{ $user->role }}">
                                        {{ $user->getDisplayRole() }}
                                    </span>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="mdi mdi-hospital-building"></i>
                                    Department
                                </div>
                                <div class="info-value">{{ $user->department ?? 'Not assigned' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="mdi mdi-calendar"></i>
                                    Joined
                                </div>
                                <div class="info-value">{{ $user->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <!-- Edit Profile Form -->
                <div class="card form-card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="mdi mdi-account-edit me-2 text-primary"></i>Edit Profile
                        </h5>
                        
                        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                            @csrf
                            @method('PUT')
                            
                            <!-- Profile Image Upload -->
                            <div class="mb-4">
                                <label class="form-label">Profile Image</label>
                                <div class="profile-upload-container">
                                    <input type="file" id="profileImageInput" name="profile_image" accept="image/*" class="d-none">
                                    <div class="upload-area" onclick="document.getElementById('profileImageInput').click()">
                                        <i class="mdi mdi-cloud-upload"></i>
                                        <p class="mb-0">Click to upload profile image</p>
                                        <small class="text-muted">JPG, PNG or GIF (Max 2MB)</small>
                                    </div>
                                </div>
                                @error('profile_image')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">
                                            <i class="mdi mdi-account me-1"></i>Full Name
                                        </label>
                                        <input type="text" class="form-control modern-input @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label">
                                            <i class="mdi mdi-email me-1"></i>Email Address
                                        </label>
                                        <input type="email" class="form-control modern-input @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="role" class="form-label">
                                            <i class="mdi mdi-account-badge me-1"></i>Role
                                        </label>
                                        <input type="text" class="form-control modern-input readonly-input" value="{{ $user->getDisplayRole() }}" readonly>
                                        <small class="text-muted">Role cannot be changed</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="department" class="form-label">
                                            <i class="mdi mdi-hospital-building me-1"></i>Department
                                        </label>
                                        <input type="text" class="form-control modern-input readonly-input" value="{{ $user->department ?? 'Not assigned' }}" readonly>
                                        <small class="text-muted">Department is assigned by admin</small>
                                    </div>
                                </div>
                            </div>

                            <div class="password-section">
                                <div class="section-header">
                                    <h6 class="mb-2">
                                        <i class="mdi mdi-lock me-2"></i>Change Password
                                    </h6>
                                    <p class="text-muted mb-3">Leave blank if you don't want to change your password</p>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="current_password" class="form-label">Current Password</label>
                                            <div class="password-input-container">
                                                <input type="password" class="form-control modern-input @error('current_password') is-invalid @enderror" 
                                                       id="current_password" name="current_password">
                                                <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
                                                    <i class="mdi mdi-eye"></i>
                                                </button>
                                            </div>
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="password" class="form-label">New Password</label>
                                            <div class="password-input-container">
                                                <input type="password" class="form-control modern-input @error('password') is-invalid @enderror" 
                                                       id="password" name="password" minlength="8">
                                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                                    <i class="mdi mdi-eye"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                            <div class="password-input-container">
                                                <input type="password" class="form-control modern-input" 
                                                       id="password_confirmation" name="password_confirmation" minlength="8">
                                                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                                    <i class="mdi mdi-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-modern">
                                    <i class="mdi mdi-content-save me-2"></i>Update Profile
                                </button>
                                <button type="button" class="btn btn-secondary btn-modern ms-2" onclick="resetForm()">
                                    <i class="mdi mdi-refresh me-2"></i>Reset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Profile Card Styles */
.profile-card {
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border-radius: 15px;
    overflow: hidden;
    background: #0b51b7;
    color: white;
}

.profile-card .card-body {
    padding: 2rem;
}

.profile-avatar-container {
    position: relative;
    display: inline-block;
    cursor: pointer;
}

.profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid rgba(255,255,255,0.3);
    transition: all 0.3s ease;
}

.profile-avatar-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    font-weight: bold;
    color: white;
    border: 4px solid rgba(255,255,255,0.3);
    transition: all 0.3s ease;
}

.profile-avatar-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.profile-avatar-container:hover .profile-avatar-overlay {
    opacity: 1;
}

.profile-avatar-overlay i {
    font-size: 2rem;
    color: white;
}

.department-badge {
    background: rgba(255,255,255,0.2);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    display: inline-block;
    font-size: 0.9rem;
}

/* Info Card Styles */
.info-card {
    border: none;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    border-radius: 15px;
}

.info-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    display: flex;
    align-items: center;
    font-weight: 500;
    color: #6c757d;
    gap: 0.5rem;
}

.info-value {
    font-weight: 600;
    color: #495057;
}

.role-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.role-admin { background: #dc3545; color: white; }
.role-doctor { background: #007bff; color: white; }
.role-nurse { background: #28a745; color: white; }
.role-staff { background: #17a2b8; color: white; }

/* Form Card Styles */
.form-card {
    border: none;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    border-radius: 15px;
}

.form-card .card-body {
    padding: 2rem;
}

/* Profile Upload Styles */
.profile-upload-container {
    margin-bottom: 1rem;
}

.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.upload-area:hover {
    border-color: #667eea;
    background: #f0f2ff;
}

.upload-area i {
    font-size: 3rem;
    color: #6c757d;
    margin-bottom: 1rem;
}

/* Modern Input Styles */
.modern-input {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #fff;
}

.modern-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    background: #fff;
}

.readonly-input {
    background: #f8f9fa !important;
    color: #6c757d;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.form-label i {
    color: #667eea;
}

/* Password Section */
.password-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    margin: 2rem 0;
}

.section-header {
    margin-bottom: 1rem;
}

.password-input-container {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
    padding: 0.25rem;
}

.password-toggle:hover {
    color: #667eea;
}

/* Button Styles */
.btn-modern {
    border-radius: 10px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary.btn-modern {
    background: #0b51b7
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-primary.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.btn-secondary.btn-modern {
    background: #6c757d;
    color: white;
}

.btn-secondary.btn-modern:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

.form-actions {
    text-align: right;
    padding-top: 1rem;
    border-top: 1px solid #dee2e6;
    margin-top: 2rem;
}

/* Responsive */
@media (max-width: 768px) {
    .profile-card .card-body,
    .form-card .card-body {
        padding: 1.5rem;
    }
    
    .profile-avatar,
    .profile-avatar-placeholder {
        width: 100px;
        height: 100px;
    }
    
    .form-actions {
        text-align: center;
    }
    
    .btn-modern {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style>

<script>
// Image preview functionality
document.getElementById('profileImageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('profilePreview');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                // Replace placeholder with image
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Profile Image';
                img.className = 'profile-avatar';
                img.id = 'profilePreview';
                preview.parentNode.replaceChild(img, preview);
            }
        };
        reader.readAsDataURL(file);
    }
});

// Password toggle functionality
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const toggle = field.nextElementSibling.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        toggle.className = 'mdi mdi-eye-off';
    } else {
        field.type = 'password';
        toggle.className = 'mdi mdi-eye';
    }
}

// Reset form functionality
function resetForm() {
    if (confirm('Are you sure you want to reset all changes?')) {
        document.getElementById('profileForm').reset();
        // Reset image preview
        location.reload();
    }
}

// Form validation
document.getElementById('profileForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;
    
    if (password && password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match!');
        return false;
    }
});
</script>
@endsection