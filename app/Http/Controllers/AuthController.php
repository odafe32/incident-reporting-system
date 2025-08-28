<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        $viewData = [
           'meta_title'=> 'Login | Metrica, incident reporting system',
           'meta_desc'=> 'Metrica, hospital management, incident reporting, resource allocation, healthcare dashboard, emergency response system',
           'meta_image'=> url('assets/images/favicon.ico'),
        ];

        return view('auth.login')->with($viewData);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Log successful login
            Log::info('User logged in successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'ip' => $request->ip(),
            ]);

            // Role-based redirect - simplified
            return $this->redirectBasedOnRole($user);
        }

        // Log failed login attempt
        Log::warning('Failed login attempt', [
            'email' => $request->email,
            'ip' => $request->ip(),
        ]);

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        
        // Log logout
        if ($user) {
            Log::info('User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    public function showForgotPassword()
    {
        $viewData = [
           'meta_title'=> 'Forgot Password | Metrica, incident reporting system',
           'meta_desc'=> 'Metrica, hospital management, incident reporting, resource allocation, healthcare dashboard, emergency response system',
           'meta_image'=> url('assets/images/favicon.ico'),
        ];

        return view('auth.forgot-password')->with($viewData);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPassword(Request $request, $token = null)
    {
        $viewData = [
           'meta_title'=> 'Reset Password | Metrica, incident reporting system',
           'meta_desc'=> 'Metrica, hospital management, incident reporting, resource allocation, healthcare dashboard, emergency response system',
           'meta_image'=> url('assets/images/favicon.ico'),
           'token' => $token,
           'email' => $request->email,
        ];

        return view('auth.reset-password')->with($viewData);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }

    /**
     * Redirect user based on their role - simplified version
     */
    private function redirectBasedOnRole(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        } else {
            // All non-admin users go to staff dashboard
            return redirect()->intended(route('staff.dashboard'));
        }
    }



}