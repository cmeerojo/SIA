<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TankController;
use Illuminate\Support\Facades\Route;

Route::resource('customers', CustomerController::class);
Route::patch('/customers/{customer}/dropoff', [CustomerController::class, 'updateDropoff'])->middleware('auth')->name('customers.dropoff.update');

// Items have been replaced by Tanks: route item URLs to TankController
Route::resource('items', TankController::class)->middleware('auth');

// Tanks
Route::resource('tanks', \App\Http\Controllers\TankController::class)->middleware('auth');

// Tank deliveries / dispatch
Route::get('/tank-deliveries', [\App\Http\Controllers\TankDeliveryController::class, 'index'])->middleware('auth')->name('tank.deliveries.index');
Route::post('/tank-deliveries', [\App\Http\Controllers\TankDeliveryController::class, 'store'])->middleware('auth')->name('tank.deliveries.store');

// Drivers CRUD
Route::resource('drivers', \App\Http\Controllers\DriverController::class)->middleware('auth');

    Route::patch('/items/{item}/hide', [TankController::class, 'hide'])->middleware('auth') ->name('items.hide');
    Route::patch('/items/{item}/unhide', [TankController::class, 'unhide'])->middleware('auth') ->name('items.unhide');
    // stock movements (now operate on Tanks.amount)
    Route::post('/items/movements', [TankController::class, 'storeMovement'])->middleware('auth')->name('items.movements.store');

// deliveries
Route::get('/deliveries', [\App\Http\Controllers\DeliveryController::class, 'index'])->middleware('auth')->name('deliveries.index');
Route::post('/deliveries/drivers', [\App\Http\Controllers\DeliveryController::class, 'storeDriver'])->middleware('auth')->name('deliveries.drivers.store');
Route::post('/deliveries', [\App\Http\Controllers\DeliveryController::class, 'storeDelivery'])->middleware('auth')->name('deliveries.store');
Route::patch('/deliveries/{delivery}/status', [\App\Http\Controllers\DeliveryController::class, 'updateStatus'])->middleware('auth')->name('deliveries.status.update');

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

    // Sales management
    Route::get('/sales/overview', [\App\Http\Controllers\SalesController::class, 'overview'])->name('sales.overview');
    Route::get('/sales/manage', [\App\Http\Controllers\SalesController::class, 'manage'])->name('sales.manage');
    Route::post('/sales', [\App\Http\Controllers\SalesController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [\App\Http\Controllers\SalesController::class, 'show'])->name('sales.show');
    Route::patch('/sales/{sale}', [\App\Http\Controllers\SalesController::class, 'update'])->name('sales.update');
});

require __DIR__.'/auth.php';