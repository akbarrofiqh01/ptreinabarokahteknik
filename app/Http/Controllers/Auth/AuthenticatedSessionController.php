<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse|JsonResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        return response()->json([
            'status'            => 'success',
            'message'           => 'Selamat Datang kembali!',
            'redirect'          => route('dashboard'),
            'csrf_token'        => csrf_token()
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse|JsonResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json([
            'status'            => 'success',
            'message'           => 'Logout berhasil, Silahkan login kembali!',
            'redirect'          => route('login'),
        ]);
    }
}
