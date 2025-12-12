<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ],
            [
                'name.required'     => 'Bagian nama wajib diisi !!!',
                'email.required'     => 'Bagian email wajib diisi !!!',
                'email.unique'     => 'Email tidak boleh sama !!!',
                'password.required'     => 'Bagian password wajib diisi !!!',
            ]
        );

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'code_user'   => Str::random(60),
        ]);

        event(new Registered($user));
        return response()->json([
            'message'           => 'Berhasil membuat akun!',
            'csrf_token'        => csrf_token(),
            'redirect'          => route('login'),
        ]);
    }
}
