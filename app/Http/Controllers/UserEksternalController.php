<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class UserEksternalController extends Controller implements HasMiddleware
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
        $dataUsers = User::with(['roles', 'company'])
            ->where('source', 'register')
            ->orderBy('id', 'DESC')
            ->get();

        return view('pengaturan.user.list-eksternal', [
            'dataUsers'     => $dataUsers
        ]);
    }

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
        return view('modal.user.edit-user-eksternal', [
            'userdata'          => $getUsers,
            'roles'             => $getRoles,
            'hasRoles'          => $hasRoles
        ]);
    }

    public function approve(string $id)
    {
        $user = User::where('code_user', $id)
            ->where('source', 'register')
            ->where('status', 'pending')
            ->firstOrFail();

        if (!auth()->user()->can('edit users')) {
            abort(403);
        }

        $user->status = 'active';
        $user->email_verified_at = now();
        $user->save();

        return response()->json([
            'message' => 'User berhasil di-approve dan dapat login.'
        ]);
    }

    public function permitInternalForm(string $usercode)
    {
        if (!auth()->user()->hasRole('superadmin')) {
            abort(403);
        }

        $user = User::where('code_user', $usercode)
            ->where('source', 'register')
            ->where('status', 'active')
            ->firstOrFail();

        $roles = Role::whereNotIn('name', ['user', 'superadmin'])
            ->orderBy('name')
            ->get();

        return view('modal.user.promote-user-eksternal', [
            'user'  => $user,
            'roles' => $roles,
        ]);
    }

    public function permitInternalStore(Request $request, string $usercode)
    {
        if (!auth()->user()->hasRole('superadmin')) {
            abort(403);
        }

        $request->validate([
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user = User::where('code_user', $usercode)
            ->where('source', 'register')
            ->where('status', 'active')
            ->firstOrFail();

        $user->syncRoles([$request->role]);
        $user->source = 'admin';
        $user->save();

        return response()->json([
            'message' => 'User berhasil dipromosikan menjadi internal.'
        ]);
    }

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

        return response()->json([
            'message'    => 'Data user eksternal berhasil diperbarui!',
            'csrf_token' => csrf_token()
        ]);
    }
}
