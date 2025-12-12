<?php

use App\Http\Controllers\FacultiesController;
use App\Http\Controllers\JenjangController;
use App\Http\Controllers\ObatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/pengaturan/permissions', [PermissionController::class, 'index'])->name('permissions.list');
    Route::post('/pengaturan/permissions/newPermissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/pengaturan/permissions/editPermissions/{permissionscode}', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/pengaturan/permissions/editPermissions/{permissionscode}', [PermissionController::class, 'update'])->name('permissions.put');
    Route::delete('/pengaturan/permissions/deletePermissions/{permissionscode}', [PermissionController::class, 'destroy']);

    Route::get('/pengaturan/roles', [RoleController::class, 'index'])->name('roles.list');
    Route::get('/pengaturan/roles/editRoles/{roleCode}', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('/pengaturan/roles/newRoles', [RoleController::class, 'store'])->name('roles.store');
    Route::put('/pengaturan/roles/updateRoles{roleCode}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/pengaturan/roles/deleteRoles/{roleCode}', [RoleController::class, 'destroy']);

    Route::get('/pengaturan/users', [UserController::class, 'index'])->name('users.list');
    Route::get('/pengaturan/users/tambahUser', [UserController::class, 'create'])->name('users.create');
    Route::get('/pengaturan/users/editUser/{usercode}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/pengaturan/users/newUser', [UserController::class, 'store'])->name('users.store');
    Route::put('/pengaturan/users/updateUser/{usercode}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/pengaturan/users/deleteUser/{usercode}', [UserController::class, 'destroy']);
});

require __DIR__ . '/auth.php';
