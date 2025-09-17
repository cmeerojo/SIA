<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::resource('customers', CustomerController::class);

Route::resource('items', ItemController::class);

    Route::patch('/items/{item}/hide', [ItemController::class, 'hide'])->middleware('auth') ->name('items.hide');
    Route::patch('/items/{item}/unhide', [ItemController::class, 'unhide'])->middleware('auth') ->name('items.unhide');

Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('users', UserManagementController::class);
    Route::patch('/users/{user}/block', [UserManagementController::class, 'block'])->name('users.block');
});

require __DIR__.'/auth.php';