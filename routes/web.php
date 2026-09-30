<?php

use App\Http\Controllers\Auth\AuthController;

/* admin controller */
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\Admin\TicketRequestController as AdminTicketRequestController;
use App\Http\Controllers\Admin\EmployeeController;

/* user controller */
use App\Http\Controllers\User\TicketRequestController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserTicketController;
use Illuminate\Support\Facades\Route;

/* public authentication routes */
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.authenticate');

/* authenticated routes */
Route::middleware('auth')->group(function () {

    /* logout route */
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /* admin routes */
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        /* admin ticket request list */
        Route::get('/ticket-requests', [AdminTicketRequestController::class, 'index'])->name('admin.ticket-requests.index');
        Route::get('/ticket-requests/{id}', [AdminTicketRequestController::class, 'show'])->name('admin.ticket-requests.show');
        Route::put('/ticket-requests/{id}/review', [AdminTicketRequestController::class, 'review'])->name('admin.ticket-requests.review');

        /* admin ticket listing and assign */
        Route::get('/tickets', [AdminTicketController::class, 'index'])->name('admin.tickets.index');
        Route::put('/tickets/{id}/assign', [AdminTicketController::class, 'assign'])->name('admin.tickets.assign');
        Route::get('/tickets/{id}/edit', [AdminTicketController::class, 'edit'])->name('admin.tickets.edit');
        Route::put('/tickets/{id}', [AdminTicketController::class, 'update'])->name('admin.tickets.update');
        Route::put('/tickets/{id}/status', [AdminTicketController::class, 'updateStatus'])->name('admin.tickets.status');

        /* user management */
        Route::get('/users', [EmployeeController::class, 'users'])->name('admin.users.index');
        Route::get('/users/create', [EmployeeController::class, 'createUser'])->name('admin.users.create');
        Route::post('/users', [EmployeeController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/users/{id}/edit', [EmployeeController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/users/{id}', [EmployeeController::class, 'updateUser'])->name('admin.users.update');
    });

    /* employee routes */
    Route::middleware('employee')->prefix('user')->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
        
        /* user ticket request management */
        Route::get('/ticket-requests', [TicketRequestController::class, 'index'])->name('user.ticket-requests.index');
        Route::get('/ticket-requests/create', [TicketRequestController::class, 'create'])->name('user.ticket-requests.create');
        Route::post('/ticket-requests', [TicketRequestController::class, 'store'])->name('user.ticket-requests.store');
        Route::get('/ticket-requests/{id}/edit', [TicketRequestController::class, 'edit'])->name('user.ticket-requests.edit');
        Route::put('/ticket-requests/{id}', [TicketRequestController::class, 'update'])->name('user.ticket-requests.update');

        /* user ticket management */
        Route::get('/tickets', [UserTicketController::class, 'index'])->name('user.tickets.index');
        Route::get('/tickets/{id}', [UserTicketController::class, 'show'])->name('user.tickets.show');
        Route::put('/tickets/{id}/status', [UserTicketController::class, 'updateStatus'])->name('user.tickets.status');
    });
});