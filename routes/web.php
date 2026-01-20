<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MtnController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrangeController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\SuperAdminController;

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

    Route::get('/admin/transactions/orange', [AdminController::class, 'transactionsOrange'])->name('admin.transactions.orange');
    Route::get('/admin/transactions/mtn', [AdminController::class, 'transactionsMtn'])->name('admin.transactions.mtn');
});


Route::middleware(['auth', 'manager'])->group(function () {

    Route::get('/gestionnaire/dashboard', [ManagerController::class, 'dashboard'])->name('gestionnaire.dashboard');
    Route::get('/manager/dashboard/pdf', [ManagerController::class, 'dashboardPdf'])->name('manager.dashboard.pdf');


    // Routes Orange Money
    Route::get('/gestionnaire/orange', [OrangeController::class, 'showOrangeForm'])->name('manager.orange.form');
    Route::post('/gestionnaire/orange', [OrangeController::class, 'orange'])->name('manager.orange');
    Route::get('/gestionnaire/orange/transactions', [OrangeController::class, 'listOrangeTransactions'])->name('manager.orange.transactions');

    // Routes MTN Money
    Route::get('/gestionnaire/mtn', [MtnController::class, 'showMtnForm'])->name('manager.mtn.form');
    Route::post('/gestionnaire/mtn', [MtnController::class, 'mtn'])->name('manager.mtn');
    Route::get('/gestionnaire/mtn/transactions', [MtnController::class, 'listMtnTransactions'])->name('manager.mtn.transactions');

});
