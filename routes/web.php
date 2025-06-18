<?php

use App\Http\Controllers\admin\service\service_controller;
use App\Http\Controllers\admin\rooms_controller;
use App\Http\Controllers\admin\service\serviceCategory_cotroller;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\PendingRegisterController;


// Trang người dùng
Route::get('/', fn() => view('user.home'));

// Dashboard người dùng
Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile người dùng
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Nhóm route dành cho admin
Route::middleware(['auth', 'verified', 'checkrole:admin'])->prefix('admin')->group(function () {

    // Dashboard admin
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');

    // Rooms
    Route::get('/rooms/create', [rooms_controller::class, 'add_room_form'])->name('admin.rooms.create');
    Route::post('/rooms', [rooms_controller::class, 'add_room_handle'])->name('admin.rooms.store');
    Route::get('/rooms', [rooms_controller::class, 'rooms_management'])->name('admin.rooms.management');
    Route::delete('/rooms/{id}', [rooms_controller::class, 'delete_room'])->name('admin.rooms.delete');
    Route::get('/rooms/{id}', [rooms_controller::class, 'view_room'])->name('admin.rooms.view');
    Route::get('/add_room', [rooms_controller::class, 'add_room_form'])->name('admin.rooms.add_room_form');

    // Service Categories
    Route::get('/service_categories', [serviceCategory_cotroller::class, 'index'])->name('service-categories.index');
    Route::get('/service_category', [serviceCategory_cotroller::class, 'create'])->name('service-categories.create');
    Route::post('/add_service_category', [serviceCategory_cotroller::class, 'store']);
    Route::delete('/service_categories/{id}', [serviceCategory_cotroller::class, 'destroy'])->name('service-categories.destroy');
    Route::get('/service-categories/{id}/edit', [serviceCategory_cotroller::class, 'edit'])->name('service-categories.edit');
    Route::put('/service-categories/{id}', [serviceCategory_cotroller::class, 'update'])->name('service-categories.update');

    // Services
    Route::get('/services', [service_controller::class, 'index'])->name('services.index');
    Route::get('/service', [service_controller::class, 'create'])->name('service.create');
    Route::post('/add_service', [service_controller::class, 'store']);
    Route::delete('/service/{id}', [service_controller::class, 'destroy'])->name('service.destroy');
    Route::get('/service/{id}/edit', [service_controller::class, 'edit'])->name('service.edit');
    Route::put('/service/{id}', [service_controller::class, 'update'])->name('service.update');
});

// Trang sau đăng nhập
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route xác thực người dùng & xác thực email
Auth::routes(['verify' => true]);
Route::post('/pending-register', [PendingRegisterController::class, 'register'])->name('pending.register');
Route::get('/verify-account', [PendingRegisterController::class, 'verify']);
