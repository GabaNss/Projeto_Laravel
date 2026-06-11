<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EventController::class, 'index']);
Route::get('/contact', [PageController::class, 'contact']);
Route::get('/products', [PageController::class, 'products']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [EventController::class, 'dashboard'])->name('dashboard');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/edit/{id}', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::post('/events/join/{id}', [EventController::class, 'joinEvent'])->name('events.join');
    Route::delete('/events/leave/{id}', [EventController::class, 'leaveEvent'])->name('events.leave');
});

Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
