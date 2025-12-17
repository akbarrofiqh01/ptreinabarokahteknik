<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Company;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

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
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(
            [

                'name'      => ['required', 'string', 'max:255'],
                'username'  => ['required', 'string', 'max:50', 'unique:users,username'],
                'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'password'  => ['required', 'confirmed', Rules\Password::defaults()],
                'nik'       => ['nullable', 'string', 'max:20'],
                'phone'     => ['nullable', 'string', 'max:20'],

                'company_name'    => ['required', 'string', 'max:255'],
                'npwp'            => ['nullable', 'string', 'max:25'],
                'company_phone'   => ['nullable', 'string', 'max:20'],
                'company_address' => ['nullable', 'string'],
            ],
            [
                'name.required'      => 'Nama lengkap wajib diisi.',
                'username.required'  => 'Username wajib diisi.',
                'username.unique'    => 'Username sudah digunakan.',
                'email.required'     => 'Email wajib diisi.',
                'email.email'        => 'Format email tidak valid.',
                'email.unique'       => 'Email sudah terdaftar.',
                'password.required'  => 'Password wajib diisi.',
                'password.confirmed' => 'Konfirmasi password tidak sesuai.',

                'company_name.required' => 'Nama perusahaan wajib diisi.',
            ]
        );

        DB::beginTransaction();

        try {


            $company = Company::create([
                'name'    => $validated['company_name'],
                'npwp'    => $validated['npwp'] ?? null,
                'phone'   => $validated['company_phone'] ?? null,
                'address' => $validated['company_address'] ?? null
            ]);


            $user = User::create([
                'name'       => $validated['name'],
                'username'   => $validated['username'],
                'email'      => $validated['email'],
                'nik'        => $validated['nik'] ?? null,
                'phone'      => $validated['phone'] ?? null,
                'password'   => Hash::make($validated['password']),
                'company_id' => $company->id,
                'code_user'  => Str::random(60),
            ]);


            $userRole = Role::where('name', 'user')->first();

            if ($userRole) {
                $user->assignRole($userRole);
            }

            event(new Registered($user));

            DB::commit();

            return response()->json([
                'message'  => 'Registrasi berhasil',
                'redirect' => route('login'),
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Registrasi gagal',
                'error'   => app()->environment('local') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
