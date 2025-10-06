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
    Route::put('/superadmin/manage-admins/{adminId}/status', [SuperAdminController::class, 'updateAdminStatus'])->name('superadmin.update.admin.status');
});

Route::middleware(['auth', 'company_admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/create-manager', [AdminController::class, 'showCreateManagerForm'])->name('admin.create.manager.form');
    Route::post('/admin/create-manager', [AdminController::class, 'createManager'])->name('admin.create.manager');
    Route::get('/admin/manage-managers', [AdminController::class, 'manageManagers'])->name('admin.manage.managers');
Route::put('/admin/manage-managers/{manager}/status', [AdminController::class, 'updateManagerStatus'])->name('admin.update.manager.status');
});

Route::middleware(['auth', 'manager'])->group(function () {
    Route::get('/gestionnaire/dashboard', [ManagerController::class, 'dashboard'])->name('gestionnaire.dashboard');
    Route::get('/gestionnaire/orange', [ManagerController::class, 'showOrangeForm'])->name('manager.orange.form'); // Nouvelle route GET pour afficher la vue
    Route::post('/gestionnaire/orange', [ManagerController::class, 'orange'])->name('manager.orange'); // Route POST pour traiter l'upload
    // New route for listing transactions
    Route::get('/gestionnaire/orange/transactions', [ManagerController::class, 'listOrangeTransactions'])->name('manager.orange.transactions');
});