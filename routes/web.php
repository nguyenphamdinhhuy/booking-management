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
use App\Http\Controllers\Auth\RegisteredUserController;



use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // ← Dòng quan trọng: cập nhật email_verified_at
    return redirect('/profile'); // hoặc về trang bạn muốn
})->middleware(['auth', 'signed'])->name('verification.verify');


// Trang người dùng
Route::get('/', [rooms_controller::class, 'user_home'])->name('index');
Route::get('/detail/{id}', [rooms_controller::class, 'room_detail'])->name('rooms_detail');
Route::get('/payment', [rooms_controller::class, 'payment'])->middleware('auth')->name('payment');
Route::get('/vouchers', [vouchers_controller::class, 'user_vouchers'])->name('user.vouchers');
Route::get('/all-rooms', [rooms_controller::class, 'all_rooms'])->name('all_rooms');
Route::get('/filter-rooms', [rooms_controller::class, 'filter_rooms'])->name('filter_rooms');

// AJAX copy voucher code
Route::post('/vouchers/copy', [vouchers_controller::class, 'copyVoucherCode'])->name('vouchers.copy');
// trang dich vu nguoi dung

Route::get('/service/{id}', [service_controller::class, 'serviceDetails'])->name('Service.byCategory');
// User Services
Route::get('/services', [App\Http\Controllers\UserServiceController::class, 'index'])->name('user.services.index');
Route::get('/services/category/{categoryId}', [App\Http\Controllers\UserServiceController::class, 'category'])->name('user.services.category');
Route::get('/services/{id}', [App\Http\Controllers\UserServiceController::class, 'show'])->name('user.services.show');

Route::get('/services/category/{id}', [serviceCategory_cotroller::class, 'byCategory'])->name('Service.byCategory');

Route::get('/booking/history/{userId}', [rooms_controller::class, 'getBookingHistory'])
    ->name('booking.history');




// VNPay payment routes
Route::middleware(['auth', 'verified'])->group(function () {});

// Dashboard người dùng
Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile người dùng
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Password routes
    Route::get('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Orders routes
    Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');

    // Favorites routes
    Route::get('/profile/favorites', [ProfileController::class, 'favorites'])->name('profile.favorites');

    // Settings routes
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::put('/profile/settings', [ProfileController::class, 'updateSettings'])->name('profile.settings.update');


    Route::get('/booking-history/{userId}', [rooms_controller::class, 'getBookingHistory'])->name('booking.history');
    Route::get('/booking-detail/{id}', [rooms_controller::class, 'bookingDetail'])->name('booking.detail');
    Route::post('/booking-confirm-checkout/{id}', [rooms_controller::class, 'confirmCheckout'])->name('booking.confirm.checkout');
    Route::post('/process-payment', [rooms_controller::class, 'processPayment'])->name('process.payment');
    Route::get('/vnpay-return', [rooms_controller::class, 'vnpayReturn'])->name('vnpay.return');
    Route::get('/booking-success/{id}', [rooms_controller::class, 'bookingSuccess'])->name('booking.success');
});

// Nhóm route dành cho admin
Route::middleware(['auth', 'verified', 'checkrole:admin'])->prefix('admin')->group(function () {
    // Dashboard admin
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');
<<<<<<< HEAD
=======
    Route::get('/user', [ProfileController::class, 'user'])->name('admin.user');

    // Admin Profile Routes (using ProfileController)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::get('/password', [ProfileController::class, 'password'])->name('admin.password');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('admin.password.update');
    Route::get('/settings', [ProfileController::class, 'settings'])->name('admin.settings');
    Route::put('/settings', [ProfileController::class, 'updateSettings'])->name('admin.settings.update');
    Route::put('/admin/users/{user}/status', [ProfileController::class, 'updateStatus'])->name('admin.users.status');


    Route::get('/register/staff/add', [RegisteredUserController::class, 'createStaff'])->name('staff.register');
    Route::post('/register/staff', [RegisteredUserController::class, 'storeStaff'])->name('register.staff');

    // Route::post('/settings/update', [ProfileController::class, 'updatesetting'])->name('admin.settings.updatesetting');



    // Admin-only routes
    // Route::get('/logs', [ProfileController::class, 'logs'])->name('admin.logs');
    // Route::get('/backup', [ProfileController::class, 'backup'])->name('admin.backup');
    // Route::post('/backup', [ProfileController::class, 'createBackup'])->name('admin.backup.create');
    Route::get('/users', [ProfileController::class, 'users'])->name('admin.users');
    Route::put('/users/{user}/status', [ProfileController::class, 'updateUserStatus'])->name('admin.users.status');
    Route::delete('/users/{user}', [ProfileController::class, 'deleteUser'])->name('admin.users.delete');
    Route::get('/statistics', [ProfileController::class, 'statistics'])->name('admin.statistics');
>>>>>>> 15562ff48cbc10022713d2f9903b20e98561c4af

    
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

    // profile route
    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::get('/password', [ProfileController::class, 'password'])->name('admin.password');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('admin.password.update');
    Route::get('/settings', [ProfileController::class, 'settings'])->name('admin.settings');
    Route::put('/settings', [ProfileController::class, 'updateSettings'])->name('admin.settings.update');
    Route::put('/admin/users/{user}/status', [ProfileController::class, 'updateStatus'])->name('admin.users.status');
    Route::post('/settings/update', [ProfileController::class, 'updatesetting'])->name('admin.settings.updatesetting');

    // Admin-only routes
    Route::get('/logs', [ProfileController::class, 'logs'])->name('admin.logs');
    Route::get('/backup', [ProfileController::class, 'backup'])->name('admin.backup');
    Route::post('/backup', [ProfileController::class, 'createBackup'])->name('admin.backup.create');
    Route::get('/users', [ProfileController::class, 'users'])->name('admin.users');
    Route::put('/users/{user}/status', [ProfileController::class, 'updateUserStatus'])->name('admin.users.status');
    Route::delete('/users/{user}', [ProfileController::class, 'deleteUser'])->name('admin.users.delete');
    Route::get('/statistics', [ProfileController::class, 'statistics'])->name('admin.statistics');
});

// Trang sau đăng nhập

// Route xác thực người dùng & xác thực email
Auth::routes(['verify' => true]);
Route::post('/pending-register', [PendingRegisterController::class, 'register'])->name('pending.register');
Route::get('/verify-account', [PendingRegisterController::class, 'verify']);

use App\Http\Controllers\Auth\SocialController;

Route::get('/auth/google', [SocialController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [SocialController::class, 'handleGoogleCallback']);

Route::get('/auth/zalo', [SocialController::class, 'redirectToZalo']);
Route::get('/auth/zalo/callback', [SocialController::class, 'handleZaloCallback']);
