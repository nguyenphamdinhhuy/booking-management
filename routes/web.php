<?php


use App\Http\Controllers\admin\service\service_controller;
use App\Http\Controllers\admin\rooms_controller;
use App\Http\Controllers\admin\service\serviceCategory_cotroller;
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

Route::get('/', [rooms_controller::class, 'user_home'])->name('home');
route::get('/detail/{id}', [rooms_controller::class, 'room_detail'])->name('rooms_detail');
route::get('/payment', [rooms_controller::class, 'payment'])->name('payment');

// User Services
Route::get('/services', [App\Http\Controllers\UserServiceController::class, 'index'])->name('user.services.index');
Route::get('/services/category/{categoryId}', [App\Http\Controllers\UserServiceController::class, 'category'])->name('user.services.category');
Route::get('/services/{id}', [App\Http\Controllers\UserServiceController::class, 'show'])->name('user.services.show');


Route::get('/booking/history/{userId}', [rooms_controller::class, 'getBookingHistory'])
    ->name('booking.history');


// Dashboard người dùng
// Route::get('/', fn() => view('home'))
//     ->middleware(['auth', 'verified'])
//     ->name('home');

// Profile Routes (Unified for both User and Admin)
Route::middleware(['auth', 'verified'])->group(function () {
    // Basic Profile Routes
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
});

// Admin Routes (using same ProfileController)
Route::middleware(['auth', 'verified', 'checkrole:admin'])->prefix('admin')->group(function () {

    // Dashboard admin
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');
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
    Route::get('/post', function() {
        return view('admin.post.add_management');
    })->name('post.index');
});

Route::get('/locked', function () {
    return view('auth.locked');
})->name('locked');


// Route xác thực người dùng & xác thực email
Auth::routes(['verify' => true]);
Route::post('/pending-register', [PendingRegisterController::class, 'register'])->name('pending.register');
Route::get('/verify-account', [PendingRegisterController::class, 'verify']);

use App\Http\Controllers\Auth\SocialController;

Route::get('/auth/google', [SocialController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [SocialController::class, 'handleGoogleCallback']);



Route::get('/auth/zalo', [SocialController::class, 'redirectToZalo']);
Route::get('/auth/zalo/callback', [SocialController::class, 'handleZaloCallback']);

