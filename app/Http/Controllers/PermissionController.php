<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:permissions.view', only: ['index']),
            new Middleware('permission:permissions.create', only: ['store']),
            new Middleware('permission:permissions.edit', only: ['edit']),
            new Middleware('permission:permissions.delete', only: ['destroy']),
        ];
    }
    public function index()
    {
        $dataPermissions = Permission::all()->sortByDesc('id');
        return view('pengaturan.permissions.index', [
            'permissions'       => $dataPermissions
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                 => ['required', 'unique:permissions', 'min:3'],
        ], [
            'name.required'        => 'Bagian nama permissions wajib diisi !!!',
            'name.unique'        => 'Bagian nama permissions harus unique !!!',
        ]);

        $createPermissions = new Permission();
        $createPermissions->name = $request->name;
        $createPermissions->code_permissions = Str::random(60);
        $createPermissions->saveOrFail();
        return response()->json([
            'message'           => 'Permissions berhasil ditambahkan!',
            'csrf_token'        => csrf_token()
        ]);
    }

    public function edit($permissionsCode)
    {
        $permissions = Permission::where('code_permissions', $permissionsCode)
            ->orderBy('id', 'DESC')
            ->firstOrFail();
        return view('modal.permissions.update', [
            'rowPermissions'        => $permissions
        ]);
    }

    public function update(Request $request, $permissionsCode)
    {
        $validated = $request->validate([
            'name'                 => ['required', 'unique:permissions', 'min:3'],
        ], [
            'name.required'        => 'Bagian nama permissions wajib diisi !!!',
            'name.unique'          => 'Bagian nama sudah ada sebelumnya'
        ]);

        $getPermissions = Permission::where('code_permissions', $permissionsCode)->firstOrFail();
        $getPermissions->update($validated);
        return response()->json([
            'message'           => 'Permissions berhasil diubah!',
            'csrf_token'        => csrf_token()
        ]);
    }


    public function destroy($permissionsCode)
    {
        $permissions = Permission::where('code_permissions', $permissionsCode)->firstOrFail();
        $permissions->delete();
        return response()->json([
            'message'           => 'Permissions berhasil dihapus!',
            'csrf_token'        => csrf_token(),
        ]);
    }
}
