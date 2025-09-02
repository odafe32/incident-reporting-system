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
            Route::get('/incidents', 'showIncidents')->name('incidents');
            Route::get('/create-resources', 'showReources')->name('create-resources');
            Route::get('/resources', 'showviewResources')->name('resources');
            Route::get('/profile', 'showProfile')->name('profile');
            
            // Incident Management
            Route::get('/incidents/{id}', 'getIncident')->name('incidents.show');
            Route::post('/incidents/{id}/messages', 'addIncidentMessage')->name('incidents.messages.store');
            Route::post('/incidents/{id}/assign', 'assignIncident')->name('incidents.assign');
            Route::post('/incidents/{id}/status', 'updateIncidentStatus')->name('incidents.status');
            Route::post('/incidents/{id}/allocate-resources', 'allocateResources')->name('incidents.allocate-resources');
            
            // Resource Management
            Route::get('/resources/available', 'getAvailableResources')->name('resources.available');
            Route::post('/resources', 'storeResource')->name('resources.store');
            Route::put('/resources/{id}', 'updateResource')->name('resources.update');
            Route::delete('/resources/{id}', 'deleteResource')->name('resources.delete');
            Route::post('/resources/multiple', 'storeMultipleResources')->name('resources.store-multiple');
            Route::post('/resources/bulk-import', 'bulkImportResources')->name('resources.bulk-import');
            
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
            Route::post('/incidents', 'storeIncident')->name('incidents.store');
            Route::get('/incidents/{id}', 'getIncident')->name('incidents.show');
            Route::post('/incidents/{id}/messages', 'addIncidentMessage')->name('incidents.messages.store');
            Route::get('/resources', 'showResources')->name('resources');
            Route::get('/profile', 'showProfile')->name('profile');
            Route::put('/profile', 'updateProfile')->name('profile.update');
            
            // Notifications
            Route::get('/notifications', 'getNotifications')->name('notifications');
            Route::post('/notifications/{id}/read', 'markNotificationAsRead')->name('notifications.read');
            Route::post('/notifications/read-all', 'markAllNotificationsAsRead')->name('notifications.read-all');
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
                return \App\Models\User::all();
            })->name('users');
            
            Route::get('/stats', [AdminController::class, 'getStats'])->name('stats');
        });
    });
});