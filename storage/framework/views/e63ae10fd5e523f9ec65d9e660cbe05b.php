<?php $__env->startSection('title', 'Chi tiết đặt phòng'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .booking-detail-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .booking-header {
        background: linear-gradient(135deg, #0066cc, #004499);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
    }

    .booking-header h1 {
        margin: 0 0 10px 0;
        font-size: 28px;
        font-weight: 600;
    }

    .booking-id {
        font-size: 16px;
        opacity: 0.9;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
        margin-top: 15px;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-confirmed {
        background-color: #d4edda;
        color: #155724;
    }

    .status-completed {
        background-color: #d1ecf1;
        color: #0c5460;
    }

    .status-cancelled {
        background-color: #f8d7da;
        color: #721c24;
    }

    .booking-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }

    .main-info {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .room-info {
        padding: 25px;
    }

    .room-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .room-name {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
    }

    .room-description {
        color: #666;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .room-features {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .feature {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #555;
    }

    .feature-icon {
        font-size: 18px;
    }

    .booking-dates {
        background: #f8f9fa;
        padding: 20px;
        border-top: 1px solid #eee;
    }

    .date-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .date-item {
        text-align: center;
    }

    .date-label {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .date-value {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .booking-summary {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 25px;
        height: fit-content;
        position: sticky;
        top: 78px;
    }

    .summary-title {
        font-size: 20px;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .summary-item:last-child {
        border-bottom: none;
        margin-top: 15px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
        font-weight: 600;
        font-size: 18px;
        color: #0066cc;
    }

    .summary-label {
        color: #666;
    }

    .summary-value {
        font-weight: 500;
        color: #333;
    }

    .action-buttons {
        margin-top: 25px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .btn {
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        text-align: center;
        border: none;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: #0066cc;
        color: white;
    }

    .btn-primary:hover {
        background: #0052a3;
    }

    .btn-success {
        background: #28a745;
        color: white;
    }

    .btn-success:hover {
        background: #218838;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }

    .btn:disabled {
        background: #ccc;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        font-weight: 500;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .nights-info {
        background: #e8f4fd;
        padding: 15px;
        border-radius: 8px;
        margin: 20px 0;
        text-align: center;
    }

    .nights-number {
        font-size: 24px;
        font-weight: 700;
        color: #0066cc;
        display: block;
    }

    .nights-text {
        color: #666;
        margin-top: 5px;
    }

    @media (max-width: 768px) {
        .booking-content {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .date-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .room-features {
            flex-direction: column;
            gap: 10px;
        }

        .booking-detail-container {
            padding: 15px;
        }
    }
</style>

<div class="booking-detail-container">
    <?php if(session('success')): ?>
    <div class="alert alert-success">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="alert alert-error">
        <?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="booking-header">
        <h1>Chi tiết đặt phòng</h1>
        <div class="booking-id">Mã đặt phòng: #<?php echo e($booking->b_id); ?></div>
        <div class="status-badge status-<?php echo e($booking->status == 0 ? 'pending' : ($booking->status == 1 ? 'confirmed' : 'completed')); ?>">
            <?php if($booking->status == 0): ?>
            🕐 Chờ xác nhận
            <?php elseif($booking->status == 1): ?>
            ✅ Đã xác nhận
            <?php else: ?>
            🏁 Đã hoàn thành
            <?php endif; ?>
        </div>
    </div>

    <div class="booking-content">
        <!-- Thông tin chính -->
        <div class="main-info">
            <div class="room-info">
                <img src="<?php echo e(asset($booking->images)); ?>" alt="Room Image" class="room-image">

                <h2 class="room-name"><?php echo e($booking->room_name); ?></h2>

                <p class="room-description"><?php echo e($booking->room_description); ?></p>

                <div class="room-features">
                    <div class="feature">
                        <span class="feature-icon">👥</span>
                        <span>Tối đa <?php echo e($booking->max_guests); ?> khách</span>
                    </div>
                    <div class="feature">
                        <span class="feature-icon">🛏️</span>
                        <span><?php echo e($booking->number_beds); ?> giường</span>
                    </div>
                </div>

                <?php if($booking->booking_description): ?>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 15px;">
                    <strong>Ghi chú đặt phòng:</strong>
                    <p style="margin: 8px 0 0 0; color: #666;"><?php echo e($booking->booking_description); ?></p>
                </div>
                <?php endif; ?>
            </div>

            <div class="booking-dates">
                <div class="date-grid">
                    <div class="date-item">
                        <div class="date-label">Nhận phòng</div>
                        <div class="date-value"><?php echo e(\Carbon\Carbon::parse($booking->check_in_date)->format('d/m/Y')); ?></div>
                        <div style="font-size: 12px; color: #666; margin-top: 4px;">15:00</div>
                    </div>
                    <div class="date-item">
                        <div class="date-label">Trả phòng</div>
                        <div class="date-value"><?php echo e(\Carbon\Carbon::parse($booking->check_out_date)->format('d/m/Y')); ?></div>
                        <div style="font-size: 12px; color: #666; margin-top: 4px;">12:00</div>
                    </div>
                </div>

                <div class="nights-info">
                    <span class="nights-number"><?php echo e(\Carbon\Carbon::parse($booking->check_in_date)->diffInDays($booking->check_out_date)); ?></span>
                    <span class="nights-text">đêm lưu trú</span>
                </div>
            </div>
        </div>

        <!-- Thông tin tóm tắt -->
        <div class="booking-summary">
            <h3 class="summary-title">Tóm tắt đặt phòng</h3>

            <div class="summary-item">
                <span class="summary-label">Giá phòng/đêm</span>
                <span class="summary-value"><?php echo e(number_format($booking->price_per_night, 0, ',', '.')); ?> VNĐ</span>
            </div>

            <div class="summary-item">
                <span class="summary-label">Số đêm</span>
                <span class="summary-value"><?php echo e(\Carbon\Carbon::parse($booking->check_in_date)->diffInDays($booking->check_out_date)); ?> đêm</span>
            </div>

            <div class="summary-item">
                <span class="summary-label">Ngày đặt</span>
                <span class="summary-value"><?php echo e(\Carbon\Carbon::parse($booking->created_at)->format('d/m/Y H:i')); ?></span>
            </div>

            <div class="summary-item">
                <span class="summary-label">Trạng thái thanh toán</span>
                <span class="summary-value">
                    <?php if($booking->payment_status == 1): ?>
                    <span style="color: #28a745;">✅ Đã thanh toán</span>
                    <?php else: ?>
                    <span style="color: #dc3545;">❌ Chưa thanh toán</span>
                    <?php endif; ?>
                </span>
            </div>

            <div class="summary-item">
                <span class="summary-label">Tổng tiền</span>
                <span class="summary-value"><?php echo e(number_format($booking->total_price, 0, ',', '.')); ?> VNĐ</span>
            </div>

            <!-- Action buttons -->
            <div class="action-buttons">
                <?php if($booking->status == 0 && $booking->payment_status == 1): ?>
                <button class="btn btn-secondary" disabled>
                    🕐 Chờ xác nhận từ khách sạn
                </button>
                <?php elseif($booking->status == 1): ?>
                <form action="<?php echo e(route('booking.confirm.checkout', $booking->b_id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success" onclick="return confirm('Xác nhận bạn đã trả phòng?')">
                        ✅ Xác nhận trả phòng
                    </button>
                </form>
                <?php elseif($booking->status == 2): ?>
                <button class="btn btn-primary" disabled>
                    🏁 Đã hoàn thành
                </button>
                <?php endif; ?>

                <a href="<?php echo e(route('booking.history', ['userId' => auth()->id()])); ?>" class="btn btn-secondary">
                    ← Quay lại lịch sử
                </a>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app\resources\views/user/booking_detail.blade.php ENDPATH**/ ?>