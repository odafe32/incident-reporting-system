<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;


Route::get('/', function () {
    return redirect()->route('login');
});

// Guest Routes Group (only accessible when NOT logged in)
Route::middleware('guest')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        // Login Routes
        Route::get('/login', 'showLogin')->name('login');
        Route::post('/login', 'login');

        // Password Reset Routes
        Route::get('/forgot-password', 'showForgotPassword')->name('password.request');
        Route::post('/forgot-password', 'forgotPassword')->name('password.email');
        Route::get('/reset-password/{token}', 'showResetPassword')->name('password.reset');
        Route::post('/reset-password', 'resetPassword')->name('password.update');
    });
});

// Authenticated Routes (requires login)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Common authenticated routes
    Route::controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout')->name('logout');
      
    });


    // Admin Routes - Requires admin role only
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::controller(AdminController::class)->group(function () {
            // Dashboard
            Route::get('/dashboard', 'showDashboard')->name('dashboard');
            
            // User Management
            Route::get('/users', 'manageUsers')->name('users');
            Route::get('/users/create', 'createUser')->name('users.create');
            Route::post('/users', 'storeUser')->name('users.store');
            Route::get('/users/{user}/edit', 'editUser')->name('users.edit');
            Route::put('/users/{user}', 'updateUser')->name('users.update');
            Route::delete('/users/{user}', 'deleteUser')->name('users.delete');
            
         
        });
    });

    // Staff Routes - For doctors, nurses, and staff members
    Route::middleware(['role:doctor,nurse,staff'])->prefix('staff')->name('staff.')->group(function () {
        Route::controller(StaffController::class)->group(function () {
            // Dashboard
            Route::get('/dashboard', 'showDashboard')->name('dashboard');
            Route::get('/incidents', 'showIncidents')->name('incidents');
            Route::get('/create-incidents', 'showCreateIncidents')->name('create-incidents');
            Route::get('/report-incidents', 'showReportIncidents')->name('report-incidents');
            Route::get('/assigned-incidents', 'showAssignedIncidents')->name('assigned-incidents');
            Route::get('/resources', 'showResources')->name('resources');
            Route::get('/notifications', 'showNotifications')->name('notifications');
            Route::get('/profile', 'showProfile')->name('profile');
            Route::put('/profile', 'updateProfile')->name('profile.update'); 

        });
    });

    // API Routes for AJAX calls
    Route::prefix('api')->name('api.')->group(function () {
        // Common API routes for all authenticated users
        Route::get('/user', function () {
            return auth()->user();
        })->name('user');
        
        // Admin only API routes
        Route::middleware(['role:admin'])->group(function () {
            Route::get('/users', function () {
                return \App\Models\User::with('department')->get();
            })->name('users');
            
            Route::get('/stats', [AdminController::class, 'getStats'])->name('stats');
        });
        
        
    });
});
