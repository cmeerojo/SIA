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
Route::get('/tank-deliveries/{tank_delivery}/map', [\App\Http\Controllers\TankDeliveryController::class, 'showMap'])->middleware('auth')->name('tank.deliveries.map');
Route::patch('/tank-deliveries/{tank_delivery}/status', [\App\Http\Controllers\TankDeliveryController::class, 'updateStatus'])->middleware('auth')->name('tank.deliveries.status.update');

// Drivers CRUD
Route::resource('drivers', \App\Http\Controllers\DriverController::class)->middleware('auth');

    Route::patch('/items/{item}/hide', [TankController::class, 'hide'])->middleware('auth') ->name('items.hide');
    Route::patch('/items/{item}/unhide', [TankController::class, 'unhide'])->middleware('auth') ->name('items.unhide');
    // stock movements (now operate on Tanks.amount)
    Route::post('/items/movements', [TankController::class, 'storeMovement'])->middleware('auth')->name('items.movements.store');

// deliveries module disabled

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
    Route::get('/sales/{sale}/receipt', [\App\Http\Controllers\SalesController::class, 'receipt'])->name('sales.receipt');

    // Vehicles (manager only)
    Route::get('/vehicles', [\App\Http\Controllers\VehicleController::class, 'index'])->name('vehicles.index');
    Route::post('/vehicles', [\App\Http\Controllers\VehicleController::class, 'store'])->name('vehicles.store');
    Route::delete('/vehicles/{vehicle}', [\App\Http\Controllers\VehicleController::class, 'destroy'])->name('vehicles.destroy');
});

require __DIR__.'/auth.php';