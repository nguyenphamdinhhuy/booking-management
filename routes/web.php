<?php

use App\Http\Controllers\admin\rooms_controller;
use App\Http\Controllers\ProfileController;
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

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});

// Routes cho rooms
Route::get('/admin/add_room', [rooms_controller::class, 'add_room_form'])->name('admin.rooms.create');

Route::post('/admin/rooms', [rooms_controller::class, 'add_room_handle'])->name('admin.rooms.store');

// Route để load danh sách phòng
Route::get('/admin/rooms', [rooms_controller::class, 'rooms_management'])->name('admin.rooms.management');

// Route để xóa phòng
Route::delete('/admin/rooms/{id}', [rooms_controller::class, 'delete_room'])->name('admin.rooms.delete');

// Route để xem chi tiết phòng
Route::get('/admin/rooms/{id}', [rooms_controller::class, 'view_room'])->name('admin.rooms.view');

// Route cũ - để tương thích
Route::get('/admin/add_room', [rooms_controller::class, 'add_room_form'])->name('admin.rooms.add_room_form');
