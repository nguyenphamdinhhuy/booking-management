<?php

use App\Http\Controllers\admin\service\service_controller;
use App\Http\Controllers\admin\rooms_controller;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\admin\service\serviceCategory_cotroller;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('user.home');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/admin/dashboard', action: function () {
    return view('admin.dashboard');
});

// Routes cho rooms
Route::get('/admin/rooms/create', [rooms_controller::class, 'add_room_form'])->name('admin.rooms.create');
Route::post('/admin/rooms', [rooms_controller::class, 'add_room_handle'])->name('admin.rooms.store');
Route::get('/admin/rooms', [rooms_controller::class, 'rooms_management'])->name('admin.rooms.management');
Route::delete('/admin/rooms/{id}', [rooms_controller::class, 'delete_room'])->name('admin.rooms.delete');
Route::get('/admin/rooms/{id}', [rooms_controller::class, 'view_room'])->name('admin.rooms.view');
Route::get('/admin/add_room', [rooms_controller::class, 'add_room_form'])->name('admin.rooms.add_room_form');

// Routes cho service category
Route::get('/admin/service_categories', [serviceCategory_cotroller::class, 'index'])->name('service-categories.index');
Route::get('/admin/service_category', [serviceCategory_cotroller::class, 'create'])->name('service-categories.create');
Route::post('/admin/add_service_category', [serviceCategory_cotroller::class, 'store']);
Route::delete('/admin/service_categories/{id}', [serviceCategory_cotroller::class, 'destroy'])->name('service-categories.destroy');
Route::get('/admin/service-categories/{id}/edit', [serviceCategory_cotroller::class, 'edit'])->name('service-categories.edit');
Route::put('/admin/service-categories/{id}', [serviceCategory_cotroller::class, 'update'])->name('service-categories.update');

// Routes cho service
Route::get('/admin/services', [service_controller::class, 'index'])->name('services.index');
Route::get('/admin/service', [service_controller::class, 'create'])->name('service.create');
Route::post('/admin/add_service', [service_controller::class, 'store']);
Route::delete('/admin/service/{id}', [service_controller::class, 'destroy'])->name('service.destroy');
Route::get('/admin/service/{id}/edit', [service_controller::class, 'edit'])->name('service.edit');
Route::put('/admin/service/{id}', [service_controller::class, 'update'])->name('service.update');
