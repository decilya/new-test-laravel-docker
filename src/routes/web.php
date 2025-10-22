<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuidesController;

Route::get('/', function () {
    return view('welcome');
});


use App\Http\Controllers\BookingsController;

Route::get('/bookings', [BookingsController::class, 'index'])->name('bookings.index');
Route::get('/bookings/create', [BookingsController::class, 'create'])->name('bookings.create');
Route::post('/bookings', [BookingsController::class, 'store'])->name('bookings.store');
Route::get('/bookings/{booking}', [BookingsController::class, 'show'])->name('bookings.show');
Route::put('/bookings/{booking}', [BookingsController::class, 'update'])->name('bookings.update');
Route::post('/check-date', [BookingsController::class, 'checkDate'])->name('check-date');

Route::delete('/bookings/{booking}', [BookingsController::class, 'destroy'])
    ->name('bookings.destroy');

Route::get('/bookings/{booking}/edit', [BookingsController::class, 'edit'])
    ->name('bookings.edit');

Route::put('/bookings/{booking}', [BookingsController::class, 'update'])
    ->name('bookings.update');

Route::resource('guides', \App\Http\Controllers\GuidesController::class);

// В файле routes/web.php

// В routes/web.php
Route::group(['prefix' => 'guides', 'as' => 'guides.'], function () {
    Route::get('/', [GuidesController::class, 'index'])->name('index');
    Route::get('/create', [GuidesController::class, 'create'])->name('create');
    Route::post('/', [GuidesController::class, 'store'])->name('store');
    Route::get('/{guide}/edit', [GuidesController::class, 'edit'])->name('edit');
    Route::put('/{guide}', [GuidesController::class, 'update'])->name('update');
    Route::delete('/{guide}', [GuidesController::class, 'destroy'])->name('destroy');
});


Route::get('/bookings/{booking}/edit', [BookingsController::class, 'edit'])
    ->name('bookings.edit');

Route::get('/guides', [GuidesController::class, 'index'])
    ->name('guides.index');

Route::get('/guides/{guide}/edit', [GuidesController::class, 'edit'])
    ->name('guides.edit');

// В routes/web.php
Route::delete('/guides/{guide}', [GuidesController::class, 'destroy'])
    ->name('guides.destroy');

