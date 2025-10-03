<?php

use App\Http\Controllers\admin\ReviewController;
use App\Http\Controllers\admin\atatistical_Controller;
use App\Http\Controllers\admin\PostController;
use App\Http\Controllers\admin\BannerController;
use App\Http\Controllers\admin\typeRoomController;
use App\Http\Controllers\admin\vouchers_controller;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserServiceController;
use App\Http\Controllers\admin\contactAdmin_Controller;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\admin\service\service_controller; // 1
use App\Http\Controllers\admin\rooms_controller; // 2
use App\Http\Controllers\admin\service\serviceCategory_cotroller; //3
use App\Http\Controllers\ProfileController; //4
use Illuminate\Support\Facades\Route; // 5
use Illuminate\Support\Facades\Auth; // 6
use App\Http\Controllers\Auth\PendingRegisterController; //
use App\Http\Controllers\Auth\SocialController; // 7 

use App\Http\Controllers\Auth\RegisteredUserController; // chưa có 
use App\Http\Controllers\homeController;
use App\Http\Controllers\roomTypeUserContoller;
use App\Http\Controllers\StaffController; // chưa có
use Illuminate\Foundation\Auth\EmailVerificationRequest; // chưa có

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // 
    return redirect('/profile');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Trang người dùng
Route::post('/room-type/check-availability', [roomTypeUserContoller::class, 'checkAvailability'])
    ->name('room-type.check-availability');

Route::post('/room-type/get-available-rooms', [roomTypeUserContoller::class, 'getAvailableRooms'])
    ->name('room-type.get-available-rooms');

// Route xử lý form đặt phòng (validate trước khi chuyển đến payment)
Route::get('/room-type/validate-booking', [roomTypeUserContoller::class, 'validateBooking'])
    ->name('room-type.validate-booking');
Route::get('/room-types', [roomTypeUserContoller::class, 'index'])->name('room_types.index');

// Route hiển thị chi tiết loại phòng
Route::get('/room-type/{id}', [roomTypeUserContoller::class, 'show'])->name('room_types.show');

// Route kiểm tra tính khả dụng
Route::post('/room-type/check-availability', [roomTypeUserContoller::class, 'checkAvailability'])->name('room-type.check-availability');

Route::post('/search', [rooms_controller::class, 'search'])->name("search");

Route::get('/about', function () {
    return view('user.about');
})->name('about');

Route::get('/', [homeController::class, 'home'])->name('index');
Route::get('/detail/{id}', [rooms_controller::class, 'room_detail'])->name('rooms_detail');
Route::get('/payment', [rooms_controller::class, 'payment'])->middleware('auth')->name('payment');
Route::get('/vouchers', [vouchers_controller::class, 'user_vouchers'])->name('user.vouchers');
Route::get('/all-rooms', [rooms_controller::class, 'all_rooms'])->name('all_rooms');
Route::get('/filter-rooms', [rooms_controller::class, 'filter_rooms'])->name('filter_rooms');

// AJAX copy voucher code
Route::post('/vouchers/copy', [vouchers_controller::class, 'copyVoucherCode'])->name('vouchers.copy');

// User Services
Route::get('/user/services', [service_controller::class, 'index'])->name('user.services.index');

Route::get('/services/category/{categoryId}', [UserServiceController::class, 'category'])->name('user.services.category');
Route::get('/services/{id}', [UserServiceController::class, 'show'])->name('user.services.show');

Route::get('/services/category/{id}', [serviceCategory_cotroller::class, 'byCategory'])->name('Service.byCategory');

// VNPay payment routes
Route::middleware(['auth', 'verified'])->group(function () {});

// Dashboard người dùng
Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile Routes (Unified for both User and Admin)
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/reviews/create/{bookingId}', [ReviewController::class, 'showReviewForm'])->name('reviews.create');
    Route::post('/reviews/{bookingId}', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/my', [ReviewController::class, 'showUserReviews'])->name('reviews.my');
    Route::put('/reviews/{reviewId}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{reviewId}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/rooms/{roomId}/reviews', [ReviewController::class, 'showRoomReviews'])->name('reviews.room');


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

    // User Booking Routes - Updated for new workflow
    Route::get('/booking-history/{userId}', [rooms_controller::class, 'getBookingHistory'])->name('booking.history');
    Route::get('/booking-detail/{id}', [rooms_controller::class, 'bookingDetail'])->name('booking.detail');

    // User booking actions based on status
    // Payment processing
    Route::post('/process-payment', [rooms_controller::class, 'processPayment'])->name('process.payment');
    Route::post('/payment/process/cash',   [rooms_controller::class, 'processPayment'])->name('process.payment.cash');
    Route::get('/vnpay-return', [rooms_controller::class, 'vnpayReturn'])->name('vnpay.return');
    Route::get('/booking-success/{id}', [rooms_controller::class, 'bookingSuccess'])->name('booking.success');

    Route::get('booking-cashPayment')

        // Route::post('/booking/cancel/{id}', [rooms_controller::class, 'cancelBooking'])
        ->name('booking.cancel');

    // Xem lịch sử đặt phòng đã hủy của user
    Route::get('/booking/cancelled', [rooms_controller::class, 'getUserCancelledBookings'])
        ->name('booking.cancelled');

    // REMOVED: User confirm checkout route (only admin can do this now)
    // Route::post('/booking-confirm-checkout/{id}', [rooms_controller::class, 'confirmCheckout'])->name('booking.confirm.checkout');
    // lien hệ
    Route::get('/lien-he', [ContactController::class, 'create'])->name('contact.create');
    Route::post('/lien-he', [ContactController::class, 'store'])->name('contact.store');
});

// Admin Routes (using same ProfileController)
Route::middleware(['auth', 'verified', 'checkrole:admin,staff'])->prefix('admin')->group(function () {
    // Dashboard admin
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');
    Route::post('/admin/bookings/update-payment-status', [rooms_controller::class, 'updatePaymentStatus'])->name('admin.bookings.updatePaymentStatus');
    Route::post('{id}/refund', [rooms_controller::class, 'refund'])->name('admin.bookings.refund');


    Route::patch('/reviews/{id}/toggle', [ReviewController::class, 'toggleStatus'])->name('admin.reviews.toggle');
    // ADD Route
    Route::get('/user', [ProfileController::class, 'user'])->name('admin.user');

    // chi tiết thống kê
    // statistical (thống kê)
    Route::get('/statistical', [atatistical_Controller::class, 'index'])->name('statistical.index');
    Route::get('detailedStatistics/{type}', [atatistical_Controller::class, 'show'])->name('admin.statistical.detailed');
    // Admin Profile Routes (using ProfileController)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::get('/password', [ProfileController::class, 'password'])->name('admin.password');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('admin.password.update');
    Route::get('/settings', [ProfileController::class, 'settings'])->name('admin.settings');
    Route::put('/settings', [ProfileController::class, 'updateSettings'])->name('admin.settings.update');
    Route::put('/admin/users/{user}/status', [ProfileController::class, 'updateStatus'])->name('admin.users.status');
    Route::get('/staff/show', [StaffController::class, 'index'])->name('staff.show');
    Route::get('/register/staff/add', [RegisteredUserController::class, 'createStaff'])->name('staff.register');
    Route::post('/register/staff', [RegisteredUserController::class, 'storeStaff'])->name('register.staff');
    Route::get('/staff/{id}/detail', [StaffController::class, 'ajaxDetail'])->name('admin.staff.ajaxDetail');
    Route::get('/users', [ProfileController::class, 'users'])->name('admin.users');
    Route::put('/users/{user}/status', [ProfileController::class, 'updateUserStatus'])->name('admin.users.status');
    Route::delete('/users/{user}', [ProfileController::class, 'deleteUser'])->name('admin.users.delete');
    Route::get('/statistics', [ProfileController::class, 'statistics'])->name('admin.statistics');

    // Rooms
    Route::get('/rooms/create', [rooms_controller::class, 'add_room_form'])->name('admin.rooms.create');
    Route::post('/rooms', [rooms_controller::class, 'add_room_handle'])->name('admin.rooms.store');
    Route::get('/rooms', [rooms_controller::class, 'rooms_management'])->name('admin.rooms.management');
    Route::get('/rooms/delete/{id}', [rooms_controller::class, 'delete_room'])->name('admin.rooms.delete');
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

    Route::get('/service/trashCan', [service_controller::class, 'trashCan'])->name('service.trashCan');
    Route::get('/service/delete_all', [service_controller::class, 'delete_all'])->name('service.delete_all');
    Route::get('service/restore_all', [service_controller::class, 'restore_all'])->name('service.restore_all');
    Route::delete('/service/delete/{s_id}', [service_controller::class, 'deleted'])->name('service.deleted');
    Route::get('/service/restore/{s_id}', [service_controller::class, 'restore'])->name('service.restore');

    // Admin Booking Management Routes - Updated for new workflow
    Route::get('/bookings', [rooms_controller::class, 'bookingManagement'])->name('admin.bookings.management');
    Route::get('/bookings/{id}/view', [rooms_controller::class, 'viewBooking'])->name('admin.bookings.view');

    // Admin booking actions based on status
    Route::post('/bookings/{id}/confirm', [rooms_controller::class, 'confirmBooking'])
        ->name('admin.bookings.confirm'); // Status 1 -> 2: Admin xác nhận đơn đặt phòng

    Route::post('/bookings/{id}/confirm-checkout', [rooms_controller::class, 'confirmCheckoutSuccess'])
        ->name('admin.bookings.confirm.checkout'); // Status 3 -> 4: Admin xác nhận trả phòng thành công

    Route::get('bookings/create', [rooms_controller::class, 'create'])->name('admin.bookings.create');
    Route::post('bookings/store', [rooms_controller::class, 'store'])->name('admin.bookings.store');
    // ADD
    Route::get('/post', function () {
        return view('admin.post.add_management');
    })->name('post.index');

    // Vouchers (moved inside admin middleware)
    Route::get('/vouchers', [vouchers_controller::class, 'management'])->name('vouchers.management');
    Route::get('/vouchers/create', [vouchers_controller::class, 'create'])->name('vouchers.create');
    Route::post('/vouchers', [vouchers_controller::class, 'store'])->name('vouchers.store');
    Route::get('/vouchers/edit/{id}', [vouchers_controller::class, 'edit'])->name('vouchers.edit');
    Route::put('/vouchers/{id}', [vouchers_controller::class, 'update'])->name('vouchers.update');
    Route::get('/vouchers/delete/{id}', [vouchers_controller::class, 'delete'])->name('vouchers.delete');





    Route::post('/admin/bookings/{id}/confirm-checkin', [rooms_controller::class, 'confirmCheckin'])
        ->name('admin.bookings.confirm.checkin');

    // API lấy thống kê hủy đặt phòng

    // liên hệ 
    Route::get('contacts', [contactAdmin_Controller::class, 'index'])->name('admin.contacts.index');
    Route::get('contacts/{id}', [contactAdmin_Controller::class, 'show'])->name('admin.contacts.show');
    Route::post('contacts/{id}/reply', [contactAdmin_Controller::class, 'reply'])->name('admin.contacts.reply');

    // loại phòng
    Route::get('/create', [typeRoomController::class, 'create'])->name('admin.roomType.create');
    Route::post('/store', [typeRoomController::class, 'store'])->name('admin.roomType.store');
    Route::get('/index', [typeRoomController::class, 'index'])->name('admin.roomType.index');
    Route::get('/show/{id}', [typeRoomController::class, 'show'])->name('admin.roomType.show');
    Route::get('/edit/{id}', [typeRoomController::class, 'edit'])->name('admin.roomType.edit');
    Route::put('/update/{id}', [typeRoomController::class, 'update'])->name('admin.roomType.update');
    Route::delete('/delete/{id}', [typeRoomController::class, 'destroy'])->name('admin.roomType.destroy');

    Route::post('/admin/bookings/check-user', [rooms_controller::class, 'checkUser'])
        ->name('admin.bookings.checkUser');

    Route::post('/admin/bookings/store', [rooms_controller::class, 'store'])
        ->name('admin.bookings.store');
});

Route::get('/locked', function () {
    return view('auth.locked');
})->name('locked');

// Route xác thực người dùng & xác thực email
Auth::routes(['verify' => true]);
Route::post('/pending-register', [PendingRegisterController::class, 'register'])->name('pending.register');
Route::get('/verify-account', [PendingRegisterController::class, 'verify']);

Route::get('/auth/google', [SocialController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [SocialController::class, 'handleGoogleCallback']);

Route::get('/auth/zalo', [SocialController::class, 'redirectToZalo']);
Route::get('/auth/zalo/callback', [SocialController::class, 'handleZaloCallback']);


Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'checkrole:admin'])->group(function () {

    Route::get('/banner', [BannerController::class, 'index'])->name('banner.index');
    Route::get('/banner/create', [BannerController::class, 'create'])->name('banner.create');
    Route::post('/banner', [BannerController::class, 'store'])->name('banner.store');
    Route::put('/banner/album/{albumCode}', [BannerController::class, 'updateAlbum'])->name('banner.updateAlbum');
    Route::delete('/banner/{id}', [BannerController::class, 'destroy'])->name('banner.destroy');

    // Booking Management Routes
    Route::prefix('bookings')->name('bookings.')->group(function () {

        // List all bookings with filter
        Route::get('/', [rooms_controller::class, 'bookingManagement'])
            ->name('management');

        // View booking details
        Route::get('/{id}', [rooms_controller::class, 'viewBooking'])
            ->name('view')
            ->where('id', '[A-Za-z0-9]+');

        // Assign room to booking
        Route::post('/{id}/assign-room', [rooms_controller::class, 'assignRoom'])
            ->name('assign.room')
            ->where('id', '[A-Za-z0-9]+');

        // Confirm room assignment (change status to 2)
        Route::post('/{id}/confirm-room', [rooms_controller::class, 'confirmRoomAssignment'])
            ->name('confirm.room')
            ->where('id', '[A-Za-z0-9]+');

        // Confirm checkout (change status to 4)
        Route::post('/{id}/confirm-checkout', [rooms_controller::class, 'confirmCheckout'])
            ->name('confirm.checkout')
            ->where('id', '[A-Za-z0-9]+');




        // Get booking statistics (for AJAX calls)
        Route::get('/api/stats', [rooms_controller::class, 'getBookingStats'])
            ->name('stats');
    });
});
route::get('/post', [postController::class, 'getAll'])->name('getAllPost');
Route::prefix('admin/post')->name('admin.post.')->group(function () {
    Route::get('/', [postController::class, 'index'])->name('index');
    Route::get('/create', [postController::class, 'create'])->name('create');
    Route::post('/store', [postController::class, 'store'])->name('store');
    Route::get('/{p_id}/edit', [postController::class, 'edit'])->name('edit');
    Route::put('/{p_id}', [postController::class, 'update'])->name('update');
    Route::delete('/{p_id}', [postController::class, 'destroy'])->name('destroy');
});

Route::prefix('admin')->middleware(['auth', 'checkrole:admin'])->name('admin.')->group(function () {
    // Room Management Routes
    Route::prefix('rooms')->name('rooms.')->group(function () {
        // Main management routes


        // Soft delete - chuyển vào thùng rác
        Route::get('/{id}/delete', [rooms_controller::class, 'delete_room'])->name('delete');

        // Trash management routes
        Route::get('/trash', [rooms_controller::class, 'trash'])->name('trash');
        Route::get('/{id}/restore', [rooms_controller::class, 'restore'])->name('restore');
        Route::get('/restore-all', [rooms_controller::class, 'restoreAll'])->name('restoreAll');
        Route::get('/{id}/force-delete', [rooms_controller::class, 'forceDelete'])->name('forceDelete');
        Route::get('/force-delete-all', [rooms_controller::class, 'forceDeleteAll'])->name('forceDeleteAll');

        // AJAX routes if needed
        Route::post('/ajax-filter', [rooms_controller::class, 'ajaxFilter'])->name('ajaxFilter');
    });

    // Room Types Management Route
    Route::prefix('roomTypes')->name('roomType.')->group(function () {
        // Basic CRUD routes (existing routes)

        // Trash management routes
        Route::get('/trash', [typeRoomController::class, 'trash'])->name('trash');
        Route::get('/{id}/restore', [typeRoomController::class, 'restore'])->name('restore');
        Route::get('/restore-all', [typeRoomController::class, 'restoreAll'])->name('restoreAll');
        Route::get('/{id}/force-delete', [typeRoomController::class, 'forceDelete'])->name('forceDelete');
        Route::get('/force-delete-all', [typeRoomController::class, 'forceDeleteAll'])->name('forceDeleteAll');

        // Additional routes
        Route::get('/debug', [typeRoomController::class, 'debug'])->name('debug');
        Route::post('/ajax-filter', [typeRoomController::class, 'ajaxFilter'])->name('ajaxFilter');
    });
});


Route::prefix('admin/bookings')
    ->name('admin.bookings.')
    ->middleware(['auth', 'checkrole:admin'])
    ->group(function () {
        // ... các route khác

        // Form xác nhận hủy
        Route::get('{id}/cancel', [rooms_controller::class, 'cancelForm'])->name('cancel.form');
        // Xử lý hủy
        Route::post('{id}/cancel', [rooms_controller::class, 'cancel'])->name('cancel');
    });

use App\Http\Controllers\Admin\AmenityController;

Route::prefix('admin')
    ->middleware(['auth', 'checkrole:admin']) // thêm 'can:admin' nếu có gate
    ->group(function () {
        Route::get('/amenities',  [AmenityController::class, 'index'])->name('admin.amenities.index');
        Route::post('/amenities', [AmenityController::class, 'store'])->name('admin.amenities.store');
        Route::delete('/amenities', [AmenityController::class, 'delete'])->name('admin.amenities.delete');
    });
