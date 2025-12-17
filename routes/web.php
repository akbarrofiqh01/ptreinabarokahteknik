<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacultiesController;
use App\Http\Controllers\JenjangController;
use App\Http\Controllers\ObatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserEksternalController;

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

    Route::get('/users/data-users-internal', [UserController::class, 'index'])->name('users.list');
    Route::get('/users/tambah-users-internal', [UserController::class, 'create'])->name('users.create');
    Route::post('/users/postUserInternalBaru', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/edit-users-internal/{usercode}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/updateUserInternal/{usercode}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/deleteUserInternal/{usercode}', [UserController::class, 'destroy']);

    Route::get('/users/data-users-eksternal', [UserEksternalController::class, 'index'])->name('usersEksternal.list');
    Route::get('/users/edit-users-eksternal/{usercode}', [UserEksternalController::class, 'edit'])->name('usersEksternal.edit');
    Route::get('/users/detil-users-eksternal/{usercode}', [UserEksternalController::class, 'detil'])->name('usersEksternal.detil');
    Route::put('/users/update-users-eksternal/{usercode}', [UserEksternalController::class, 'update'])->name('users.update');
    Route::get('/users/user-eksternal-promote-internal/{usercode}', [UserEksternalController::class, 'permitInternalForm'])->name('usersEksternal.permitInternal');
    Route::post('/users/approve-user-eksternal-permit-internal/{usercode}', [UserEksternalController::class, 'permitInternalStore'])->name('usersEksternal.permitInternal.store');
    Route::post('/users/users-eksternal-suspend/{usercode}', [UserEksternalController::class, 'suspend'])->name('usersEksternal.suspend');
    Route::post('/users/users-eksternal-approve/{usercode}', [UserEksternalController::class, 'approve'])->name('usersEksternal.approve');
    Route::delete('/users/users-eksternal-delete/{usercode}', [UserEksternalController::class, 'destroy']);

    Route::get('/master/data-bank/list-bank', [BankController::class, 'index'])->name('bank.list');
    Route::get('/master/data-bank/tambah-bank', [BankController::class, 'create'])->name('bank.create');
    Route::post('/master/data-bank/newbank', [BankController::class, 'store'])->name('bank.store');
    Route::get('/master/data-bank/edit-bank/{bnkcode}', [BankController::class, 'edit'])->name('bank.edit');
    Route::put('/master/data-bank/Updatebank/{bnkcode}', [BankController::class, 'update'])->name('bank.update');
    Route::delete('/master/data-bank/Deletebank/{usercode}', [BankController::class, 'destroy']);
});


require __DIR__ . '/auth.php';
