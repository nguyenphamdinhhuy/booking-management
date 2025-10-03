<?php $__env->startSection('title', 'Lịch sử đặt phòng'); ?>

<?php $__env->startSection('content'); ?>
<div class="history-container">
    <!-- Header -->
    <div class="history-header">
        <h1 class="history-title">Lịch sử đặt phòng</h1>
        <p class="history-subtitle">Xem và quản lý tất cả các đặt phòng của bạn</p>
    </div>

    <!-- Filters -->
    <div class="history-filters">
        <div class="history-filter-group">
            <label class="history-filter-label">Trạng thái</label>
            <select class="history-filter-select" id="status-filter">
                <option value="">Tất cả</option>
                <option value="confirmed">Đã xác nhận</option>
                <option value="completed">Hoàn thành</option>
                <option value="cancelled">Đã hủy</option>
            </select>
        </div>
        <div class="history-filter-group">
            <label class="history-filter-label">Tìm kiếm</label>
            <input type="text" class="history-filter-input" id="search-box" placeholder="Tên khách sạn, mã đặt phòng...">
        </div>
    </div>

    <!-- Booking List -->
    <?php if($bookings->count() > 0): ?>
    <div class="history-list">
        <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="history-item">
            <div class="history-item-header">
                <div class="history-booking-id">Mã đặt phòng: #<?php echo e($booking->id); ?></div>
                <div class="history-status <?php echo e(strtolower($booking->status)); ?>">
                    <?php if($booking->status === 'confirmed'): ?> Đã xác nhận
                    <?php elseif($booking->status === 'completed'): ?> Hoàn thành
                    <?php elseif($booking->status === 'cancelled'): ?> Đã hủy
                    <?php endif; ?>
                </div>
            </div>
            <div class="history-item-content">
                <div class="history-hotel-info">
                    <img src="<?php echo e(asset($booking->images)); ?>" alt="Hotel Image" class="history-hotel-image">
                    <div class="history-hotel-details">
                        <h3 class="history-hotel-name"><?php echo e($booking->room_type); ?></h3>
                        <p class="history-hotel-location">Error - 404</p>
                        <div class="history-hotel-rating">
                            <span class="history-rating-stars">★★★★★</span>
                            <span class="history-rating-score"><?php echo e($booking->rating); ?></span>
                        </div>
                    </div>
                </div>

                <div class="history-booking-details">

                    <div class="history-detail-item">
                        <span class="history-detail-label">Ngày trả phòng</span>
                        <span class="history-detail-value"><?php echo e(\Carbon\Carbon::parse($booking->checkout_date)->format('d/m/Y')); ?></span>
                    </div>
                    <div class="history-detail-item">
                        <span class="history-detail-label">Số đêm</span>
                        <span class="history-detail-value"><?php echo e(\Carbon\Carbon::parse($booking->checkin_date)->diffInDays($booking->checkout_date)); ?> đêm</span>
                    </div>

                    <div class="history-detail-item">
                        <span class="history-detail-label">Số khách</span>
                        <span class="history-detail-value"><?php echo e($booking->guests); ?></span>
                    </div>
                    <div class="history-detail-item">
                        <span class="history-detail-label">Ngày nhận phòng</span>
                        <span class="history-detail-value"><?php echo e(\Carbon\Carbon::parse($booking->checkin_date)->format('d/m/Y')); ?></span>
                    </div>
                    <?php if($booking->status == '0'): ?>
                    <div class="history-detail-item">
                        <span class="history-detail-label">Ngày hủy</span>
                        <span class="history-detail-value"><?php echo e(\Carbon\Carbon::parse($booking->canceled_at)->format('d/m/Y')); ?></span>
                    </div>
                    <?php else: ?>
                    <div class="history-detail-item">
                        <span class="history-detail-label">Ngày đặt</span>
                        <span class="history-detail-value"><?php echo e(\Carbon\Carbon::parse($booking->created_at)->format('d/m/Y')); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="history-price-summary">
                    <span class="history-price-label">
                        <?php echo e($booking->status === 'cancelled' ? 'Số tiền hoàn lại:' : 'Tổng giá tiền:'); ?>

                    </span>
                    <span class="history-price-value">
                        <?php echo e(number_format($booking->status === 'cancelled' ? $booking->refund_price : $booking->price, 0, ',', '.')); ?> VNĐ
                    </span>
                </div>

                <div class="history-actions">
                    <a href="<?php echo e(route('booking.detail', ['id' => $booking->id])); ?>" class="history-btn history-btn-primary">Xem chi tiết</a>
                    <?php if($booking->status !== 'cancelled'): ?>
                    <a href="" class="history-btn history-btn-secondary">Tải hóa đơn</a>
                    <?php endif; ?>
                    <a href="" class="history-btn history-btn-secondary">Đặt lại</a>
                    <?php if($booking->status === 'completed'): ?>
                    <a href="" class="history-btn history-btn-secondary">Đánh giá</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php else: ?>
    <div class="history-empty">
        <div class="history-empty-icon">🏨</div>
        <h3 class="history-empty-title">Chưa có đặt phòng nào</h3>
        <p class="history-empty-text">Bạn chưa có lịch sử đặt phòng nào. Hãy bắt đầu khám phá và đặt phòng ngay!</p>
    </div>
    <?php endif; ?>
</div>

<!-- Optional: filter script -->


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/user/booking_bill.blade.php ENDPATH**/ ?>