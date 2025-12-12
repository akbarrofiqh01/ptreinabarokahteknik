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
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:5', 'same:confirm'],
            'confirm' => ['required'],
        ], [
            'name.required' => 'Bagian nama wajib diisi !!!',
            'name.min' => 'Nama minimal 3 karakter.',
            'email.required' => 'Bagian email wajib diisi !!!',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Bagian email sudah ada sebelumnya.',
            'password.required' => 'Bagian password wajib diisi !!!',
            'password.min' => 'Password minimal 5 karakter.',
            'password.same' => 'Password dan konfirmasi password harus sama.',
            'confirm.required' => 'Bagian konfirmasi password wajib diisi !!!',
        ]);

        $createUser = new User();
        $createUser->name   = $request->name;
        $createUser->email  = $request->email;
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
            'name'                 => ['required', 'min:3'],
            'email'                => ['required', 'email', 'unique:users,email,' . $id . ',code_user'],
        ], [
            'name.required'        => 'Bagian nama wajib diisi !!!',
            'email.required'        => 'Bagian email wajib diisi !!!',
            'email.unique'          => 'Bagian email sudah ada sebelumnya'
        ]);
        $users = User::where('code_user', $id)->firstOrFail();
        $users->syncRoles($request->role);
        $users->update($validated);
        return response()->json([
            'message'           => 'User berhasil diubah!',
            'csrf_token'        => csrf_token()
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
