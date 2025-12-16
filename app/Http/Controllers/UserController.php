<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view users', only: ['index']),
            new Middleware('permission:create users', only: ['create']),
            new Middleware('permission:edit users', only: ['edit']),
            new Middleware('permission:delete users', only: ['destroy']),
        ];
    }
    public function index()
    {
        if (auth()->user()->hasRole('superadmin')) {
            $dataUsers = User::with('roles')
                ->orderBy('id', 'DESC')
                ->get();
        } else {
            $dataUsers = User::whereDoesntHave('roles', function ($q) {
                $q->where('name', 'superadmin');
            })->with('roles')
                ->orderBy('id', 'DESC')
                ->get();
        }
        return view('pengaturan.user.index', [
            'datausers'     => $dataUsers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->user()->hasRole('superadmin')) {
            $getRoles = Role::all();
        } else {
            $getRoles = Role::where('name', '!=', 'superadmin')
                ->orderBy('name', 'ASC')
                ->get();
        }
        return view('modal.user.create-user', [
            'role'      => $getRoles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'min:1'],
            'nik' => ['required', 'min:1', 'digits:16', 'numeric'],
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'min:1', 'digits_between:1,12', 'numeric'],
            'password' => ['required', 'min:5', 'same:password_confirmation'],
            'password_confirmation' => ['required'],
        ], [
            'username.required' => 'Bagian username wajib diisi !!!',
            'username.min' => 'username minimal 1 karakter !!!',
            'nik.required' => 'Bagian NIK wajib diisi !!!',
            'nik.min' => 'NIK minimal 1 karakter !!!',
            'nik.digits' => 'NIK maksimal 16 karakter !!!',
            'name.required' => 'Bagian nama wajib diisi !!!',
            'name.min' => 'Nama minimal 3 karakter.',
            'email.required' => 'Bagian email wajib diisi !!!',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Bagian email sudah ada sebelumnya.',
            'phone.required' => 'Bagian no telp wajib diisi !!!',
            'phone.min' => 'No telp minimal 1 karakter !!!',
            'phone.digits_between' => 'No telp maksimal 12 karakter !!!',
            'password.required' => 'Bagian password wajib diisi !!!',
            'password.min' => 'Password minimal 5 karakter.',
            'password.same' => 'Password dan konfirmasi password harus sama.',
            'password_confirmation.required' => 'Bagian konfirmasi password wajib diisi !!!',
        ]);

        $createUser = new User();
        $createUser->username   = $request->username;
        $createUser->nik   = $request->nik;
        $createUser->name   = $request->name;
        $createUser->email  = $request->email;
        $createUser->phone  = $request->phone;
        $createUser->password  = Hash::make($request->password);
        $createUser->code_user = Str::random(60);
        $createUser->syncRoles($request->role);
        $createUser->save();
        return response()->json([
            'message'           => 'User berhasil ditambahkan!',
            'csrf_token'        => csrf_token()
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $getUsers = User::where('code_user', $id)->firstOrFail();
        if (auth()->user()->hasRole('superadmin')) {
            $getRoles = Role::all();
        } else {
            $getRoles = Role::where('name', '!=', 'superadmin')
                ->orderBy('name', 'ASC')
                ->get();
        }
        $hasRoles = $getUsers->roles()->pluck('id');
        return view('modal.user.edit-user', [
            'userdata'          => $getUsers,
            'roles'             => $getRoles,
            'hasRoles'          => $hasRoles
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'min:3', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email,' . $id . ',code_user'],
            'username' => ['nullable', 'string', 'min:3', 'max:50', 'unique:users,username,' . $id . ',code_user'],
            'nik'      => ['nullable', 'string', 'digits:16', 'unique:users,nik,' . $id . ',code_user'],
            'phone'    => ['nullable', 'string', 'min:10', 'max:13', 'regex:/^[0-9]+$/'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required'     => 'Bagian nama wajib diisi !!!',
            'name.min'          => 'Nama minimal 3 karakter',
            'name.max'          => 'Nama maksimal 100 karakter',
            'email.required'    => 'Bagian email wajib diisi !!!',
            'email.email'       => 'Format email tidak valid',
            'email.unique'      => 'Email sudah digunakan',
            'username.min'      => 'Username minimal 3 karakter',
            'username.max'      => 'Username maksimal 50 karakter',
            'username.unique'   => 'Username sudah digunakan',
            'nik.digits'        => 'NIK harus 16 digit angka',
            'nik.unique'        => 'NIK sudah terdaftar',
            'phone.min'         => 'Nomor telepon minimal 10 digit',
            'phone.max'         => 'Nomor telepon maksimal 13 digit',
            'phone.regex'       => 'Nomor telepon hanya boleh berisi angka',
            'password.min'      => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        $user = User::where('code_user', $id)->firstOrFail();

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (isset($validated['username'])) {
            $user->username = $validated['username'];
        }
        if (isset($validated['nik'])) {
            $user->nik = $validated['nik'];
        }
        if (isset($validated['phone'])) {
            $user->phone = $validated['phone'];
        }
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        if ($request->has('role')) {
            $user->syncRoles($request->role);
        } else {
            $user->syncRoles([]);
        }

        return response()->json([
            'message'    => 'Data user berhasil diperbarui!',
            'csrf_token' => csrf_token()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dataUsers = User::where('code_user', $id)->firstOrFail();
        $dataUsers->delete();
        return response()->json([
            'message'           => 'User berhasil dihapus!',
            'csrf_token'        => csrf_token()
        ]);
    }
}
