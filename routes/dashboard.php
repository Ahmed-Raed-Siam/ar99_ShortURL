<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\LinkController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\UserRoleController;
use App\Http\Controllers\Dashboard\UserTrashController;
use Illuminate\Support\Facades\Route;

Route::name('dashboard.')->middleware(['auth'])->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);

//    Route::patch('users/trash/{user_id}', [UserTrashController::class, 'restore'])->name('users.trash.restore');
    Route::put('users/trash/{user_id}', [UserTrashController::class, 'restore'])->name('users.trash.restore');

    Route::name('users')->resource('users/trash', UserTrashController::class)->parameters([
        'trash' => 'user_id'
    ])->only([
        'index', 'show', 'destroy'
    ]);

    Route::name('users')->resource('users/roles', UserRoleController::class)->parameters([
        'role' => 'user_role'
    ]);

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('links', LinkController::class);
    Route::get('ar99/{code}', [LinkController::class, 'shortenLink'])->name('shorten.link');

});