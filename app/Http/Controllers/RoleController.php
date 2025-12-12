<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view roles', only: ['index']),
            new Middleware('permission:create roles', only: ['store']),
            new Middleware('permission:edit roles', only: ['edit']),
            new Middleware('permission:delete roles', only: ['destroy']),
        ];
    }
    public function index()
    {
        $getPermissions = Permission::orderBy('name', 'ASC')->get();
        $getData        = Role::all()->sortByDesc('id');
        return view('pengaturan.role.index', [
            'dataPermission'        => $getPermissions,
            'dataRoles'             => $getData
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name'                 => ['required', 'unique:roles', 'min:3'],
        ], [
            'name.required'        => 'Bagian nama roles wajib diisi !!!',
            'name.unique'        => 'Bagian nama roles harus unique !!!',
        ]);

        $createRoles = new Role();
        $createRoles->name = strtolower($request->name);
        $createRoles->code_role = Str::random(60);
        if (!empty($request->selectedPermissions)) {
            foreach ($request->selectedPermissions as $nameValue) {
                $createRoles->givePermissionTo($nameValue);
            }
        }
        $createRoles->saveOrFail();
        return response()->json([
            'message'           => 'Roles berhasil ditambahkan!',
            'csrf_token'        => csrf_token()
        ]);
    }
    public function edit($roleCode)
    {
        $roles = Role::where('code_role', $roleCode)->firstOrFail();
        $Haspermissions = $roles->permissions->pluck('name');
        $getPermissions = Permission::orderBy('name', 'ASC')->get();

        return view('modal.role.update', [
            'dataRole'              => $roles,
            'dataPermissions'       => $getPermissions,
            'hasPermissions'        => $Haspermissions
        ]);
    }
    public function update(Request $request, $roleCode)
    {
        $request->validate([
            'name'                 => ['required', 'unique:roles,name,' . $roleCode . ',code_role', 'min:3'],
        ], [
            'name.required'        => 'Bagian nama roles wajib diisi !!!',
            'name.unique'        => 'Bagian nama roles harus unique !!!',
        ]);
        $getRoles = Role::where('code_role', $roleCode)->firstOrFail();
        $isUsed  = User::role($getRoles->name)->exists();
        if ($isUsed) {
            return response()->json([
                'message' => 'Role ini sudah digunakan oleh user dan tidak bisa diubah.',
                'csrf_token'        => csrf_token()
            ], 422);
        }
        $getRoles->name = strtolower($request->name);
        if (!empty($request->selectedPermissions)) {
            $getRoles->syncPermissions($request->selectedPermissions);
        } else {
            $getRoles->syncPermissions([]);
        }
        $getRoles->update();
        return response()->json([
            'message'           => 'Roles berhasil diubah!',
            'csrf_token'        => csrf_token()
        ]);
    }
    public function destroy($roleCode)
    {
        $getRoles = Role::where('code_role', $roleCode)->firstOrFail();
        $isUsed  = User::role($getRoles->name)->exists();
        if ($isUsed) {
            return response()->json([
                'message' => 'Role ini sudah digunakan oleh user dan tidak bisa dihapus.',
                'csrf_token'        => csrf_token()
            ], 422);
        }
        $getRoles->delete();
        return response()->json([
            'message'           => 'Roles berhasil dihapus!',
            'csrf_token'        => csrf_token(),
        ]);
    }
}
