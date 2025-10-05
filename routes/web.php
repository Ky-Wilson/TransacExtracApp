<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;






Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::middleware(['auth', 'super_admin'])->group(function () {
    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');
    Route::get('/superadmin/manage-managers', [SuperAdminController::class, 'manageManagers'])->name('superadmin.manage.managers');
    Route::get('/superadmin/manage-admins', [SuperAdminController::class, 'manageAdmins'])->name('superadmin.manage.admins');
});

Route::middleware(['auth', 'company_admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/create-manager', [AdminController::class, 'showCreateManagerForm'])->name('admin.create.manager.form');
    Route::post('/admin/create-manager', [AdminController::class, 'createManager'])->name('admin.create.manager');
    Route::get('/admin/manage-managers', [AdminController::class, 'manageManagers'])->name('admin.manage.managers');
Route::put('/admin/manage-managers/{manager}/status', [AdminController::class, 'updateManagerStatus'])->name('admin.update.manager.status');});

Route::middleware(['auth', 'manager'])->group(function () {
    Route::get('/gestionnaire/dashboard', [ManagerController::class, 'dashboard'])->name('gestionnaire.dashboard');
});