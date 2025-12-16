<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'login'     => ['required', 'string'],
            'password'  => ['required', 'string'],
        ]);

        $login = $request->input('login');
        $password = $request->input('password');

        $field = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        // Attempt login
        if (! Auth::attempt([
            $field => $login,
            'password' => $password,
        ], $request->boolean('remember'))) {

            throw ValidationException::withMessages([
                'login' => ['Email/Username atau password salah.'],
            ]);
        }

        $request->session()->regenerate();

        return response()->json([
            'status'     => 'success',
            'message'    => 'Selamat datang kembali!',
            'redirect'   => route('dashboard'),
            'csrf_token' => csrf_token(),
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'status'   => 'success',
            'message'  => 'Logout berhasil, silakan login kembali.',
            'redirect' => route('login'),
        ]);
    }
}
