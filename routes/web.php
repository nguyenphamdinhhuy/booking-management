<?php

use App\Http\Controllers\admin\PostController;
use App\Http\Controllers\admin\service\service_controller;
use App\Http\Controllers\admin\rooms_controller;
use App\Http\Controllers\admin\vouchers_controller;
use App\Http\Controllers\admin\service\serviceCategory_cotroller;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\PendingRegisterController;

// Trang người dùng
Route::get('/', [rooms_controller::class, 'user_home'])->name('index');
Route::get('/detail/{id}', [rooms_controller::class, 'room_detail'])->name('rooms_detail');
Route::get('/payment', [rooms_controller::class, 'payment'])->middleware('auth')->name('payment');
// trang dich vu nguoi dung

Route::get('/service/{id}', [service_controller::class, 'serviceDetails'])->name('Service.byCategory');




// VNPay payment routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/process-payment', [rooms_controller::class, 'processPayment'])->name('process.payment');
    Route::get('/vnpay-return', [rooms_controller::class, 'vnpayReturn'])->name('vnpay.return');
    Route::get('/booking-success/{id}', [rooms_controller::class, 'bookingSuccess'])->name('booking.success');
});

// Dashboard người dùng
Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile người dùng
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/booking-history/{userId}', [rooms_controller::class, 'getBookingHistory'])->name('booking.history');
    Route::get('/booking-detail/{id}', [rooms_controller::class, 'bookingDetail'])->name('booking.detail');
    Route::post('/booking-confirm-checkout/{id}', [rooms_controller::class, 'confirmCheckout'])->name('booking.confirm.checkout');
});

// Nhóm route dành cho admin
Route::middleware(['auth', 'verified', 'checkrole:admin'])->prefix('admin')->group(function () {
    // Dashboard admin
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');

    // Rooms
    Route::get('/rooms/create', [rooms_controller::class, 'add_room_form'])->name('admin.rooms.create');
    Route::post('/rooms', [rooms_controller::class, 'add_room_handle'])->name('admin.rooms.store');
    Route::get('/rooms', [rooms_controller::class, 'rooms_management'])->name('admin.rooms.management');
    Route::get('/rooms/delete/{id}', [rooms_controller::class, 'delete_room'])->name('admin.rooms.delete');
    Route::get('/rooms/{id}', [rooms_controller::class, 'view_room'])->name('admin.rooms.view');
    Route::get('/add_room', [rooms_controller::class, 'add_room_form'])->name('admin.rooms.add_room_form');
    Route::get('/rooms/edit/{id}', [rooms_controller::class, 'edit_room_form'])->name('admin.rooms.edit');
    Route::put('/rooms/{id}', [rooms_controller::class, 'edit_room_handle'])->name('admin.rooms.update');

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

    // post
    Route::get('/post', [PostController::class, 'index'])->name('post.index');


    // Vouchers
    Route::get('/vouchers', [vouchers_controller::class, 'management'])->name('vouchers.management');
    Route::get('/vouchers/create', [vouchers_controller::class, 'create'])->name('vouchers.create');
    Route::post('/vouchers', [vouchers_controller::class, 'store'])->name('vouchers.store');
    Route::get('/vouchers/edit/{id}', [vouchers_controller::class, 'edit'])->name('vouchers.edit');
    Route::put('/vouchers/{id}', [vouchers_controller::class, 'update'])->name('vouchers.update');
    Route::get('/vouchers/delete/{id}', [vouchers_controller::class, 'delete'])->name('vouchers.delete');


    // Booking Management Routes
    Route::get('/bookings', [rooms_controller::class, 'bookingManagement'])->name('admin.bookings.management');
    Route::get('/bookings/{id}/view', [rooms_controller::class, 'viewBooking'])->name('admin.bookings.view');
    Route::post('/bookings/{id}/confirm', [rooms_controller::class, 'confirmBooking'])->name('admin.bookings.confirm');
    Route::post('/bookings/{id}/confirm-checkout', [rooms_controller::class, 'confirmCheckoutSuccess'])->name('admin.bookings.confirm.checkout');
});

// Trang sau đăng nhập
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route xác thực người dùng & xác thực email
Auth::routes(['verify' => true]);
Route::post('/pending-register', [PendingRegisterController::class, 'register'])->name('pending.register');
Route::get('/verify-account', [PendingRegisterController::class, 'verify']);

use App\Http\Controllers\Auth\SocialController;

Route::get('/auth/google', [SocialController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [SocialController::class, 'handleGoogleCallback']);

Route::get('/auth/zalo', [SocialController::class, 'redirectToZalo']);
Route::get('/auth/zalo/callback', [SocialController::class, 'handleZaloCallback']);
