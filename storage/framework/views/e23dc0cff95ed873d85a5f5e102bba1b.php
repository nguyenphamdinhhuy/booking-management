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

    .status-waiting-admin {
        background-color: #e3f2fd;
        color: #1565c0;
    }

    .status-confirmed {
        background-color: #fff3e0;
        color: #ef6c00;
    }

    .status-checkedin {
        background-color: #fce4ec;
        color: #c2185b;
    }

    .status-completed {
        background-color: #d4edda;
        color: #155724;
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

    .btn-warning {
        background: #ffc107;
        color: #212529;
    }

    .btn-warning:hover {
        background: #e0a800;
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
        <div class="status-badge 
            <?php if($booking->status == 0): ?> status-pending
            <?php elseif($booking->status == 1): ?> status-waiting-admin  
            <?php elseif($booking->status == 2): ?> status-confirmed
            <?php elseif($booking->status == 3): ?> status-checkedin
            <?php elseif($booking->status == 4): ?> status-completed
            <?php else: ?> status-cancelled
            <?php endif; ?>">

            <?php if($booking->status == 0): ?>
            🕐 Chờ thanh toán
            <?php elseif($booking->status == 1): ?>
            ⏳ Chờ khách sạn xác nhận
            <?php elseif($booking->status == 2): ?>
            🔑 Đã xác nhận - Có thể nhận phòng
            <?php elseif($booking->status == 3): ?>
            🛏️ Đã nhận phòng - Chờ trả phòng
            <?php elseif($booking->status == 4): ?>
            ✅ Đã hoàn thành
            <?php else: ?>
            ❌ Đã hủy
            <?php endif; ?>
        </div>
    </div>

    <div class="booking-content">
        <!-- Thông tin chính -->
        <div class="main-info">
            <div class="room-info">
                <!-- Hiển thị ảnh phòng - sử dụng đúng tên field từ controller -->
                <?php if($booking->images): ?>
                <img src="<?php echo e(asset($booking->images)); ?>" alt="Room Image" class="room-image" onerror="this.src='<?php echo e(asset('assets/images/default-room.jpg')); ?>'">
                <?php else: ?>
                <img src="<?php echo e(asset('assets/images/default-room.jpg')); ?>" alt="Default Room Image" class="room-image">
                <?php endif; ?>

                <!-- Tên phòng - sử dụng đúng tên field từ controller -->
                <h2 class="room-name"><?php echo e($booking->room_name ?? $booking->name ?? 'Tên phòng không xác định'); ?></h2>



                <div class="room-features">
                    <div class="feature">
                        <span class="feature-icon">👥</span>
                        <span>Tối đa <?php echo e($booking->max_guests ?? 'N/A'); ?> khách</span>
                    </div>
                    <div class="feature">
                        <span class="feature-icon">🛏️</span>
                        <span><?php echo e($booking->number_beds ?? 'N/A'); ?> giường</span>
                    </div>
                </div>

                <!-- Ghi chú đặt phòng -->
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

                <!-- Tính số đêm -->
                <?php
                $nights = \Carbon\Carbon::parse($booking->check_in_date)->diffInDays($booking->check_out_date);
                if ($nights < 1) $nights=1;
                    ?>
                    <div class="nights-info">
                    <span class="nights-number"><?php echo e($nights); ?></span>
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
            <span class="summary-value"><?php echo e($nights); ?> đêm</span>
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

        <!-- Action buttons theo status của controller -->
        <div class="action-buttons">
            
            <?php if($booking->status == 0): ?>
            <button class="btn btn-warning" disabled>
                🕐 Chờ thanh toán
            </button>

            
            <?php elseif($booking->status == 1): ?>
            <button class="btn btn-secondary" disabled>
                ⏳ Chờ khách sạn xác nhận đơn đặt phòng
            </button>

            <!-- Nút hủy đặt phòng cho user -->
            <?php
            $checkInTime = strtotime($booking->check_in_date);
            $currentTime = time();
            $timeUntilCheckIn = $checkInTime - $currentTime;
            $canCancel = $timeUntilCheckIn >= 86400; // 24 giờ = 86400 giây
            ?>

            <?php if($canCancel): ?>
            <button class="btn btn-danger" onclick="showCancelModal(<?php echo e($booking->b_id); ?>)">
                ❌ Hủy đặt phòng
            </button>
            <?php else: ?>
            <button class="btn btn-secondary" disabled title="Không thể hủy trong vòng 24h trước ngày nhận phòng">
                ❌ Quá hạn hủy
            </button>
            <?php endif; ?>

            
            <?php elseif($booking->status == 2): ?>
            <form action="<?php echo e(url('/booking-checkin/' . $booking->b_id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-success"
                    onclick="return confirm('Xác nhận bạn đã nhận phòng?')">
                    🔑 Xác nhận đã nhận phòng
                </button>
            </form>
            <div style="background: #e8f5e8; padding: 12px; border-radius: 6px; font-size: 13px; color: #2e7d32; margin-top: 10px;">
                <i class="fas fa-info-circle"></i> Đơn đặt phòng đã được khách sạn xác nhận. Bạn có thể nhận phòng từ 15:00 ngày <?php echo e(\Carbon\Carbon::parse($booking->check_in_date)->format('d/m/Y')); ?>.
            </div>

            
            <?php elseif($booking->status == 3): ?>
            <button class="btn btn-secondary" disabled>
                🛏️ Đã nhận phòng - Chờ khách sạn xác nhận trả phòng
            </button>
            <div style="background: #fff3e0; padding: 12px; border-radius: 6px; font-size: 13px; color: #ef6c00; margin-top: 10px;">
                <i class="fas fa-info-circle"></i> Bạn đã nhận phòng thành công. Vui lòng trả phòng trước 12:00 ngày <?php echo e(\Carbon\Carbon::parse($booking->check_out_date)->format('d/m/Y')); ?>.
            </div>

            
            <?php elseif($booking->status == 4): ?>
            <button class="btn btn-success" disabled>
                ✅ Đã hoàn thành
            </button>
            <div style="background: #d4edda; padding: 12px; border-radius: 6px; font-size: 13px; color: #155724; margin-top: 10px;">
                <i class="fas fa-check-circle"></i> Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi! Đơn đặt phòng đã hoàn tất.
            </div>

            
            <?php else: ?>
            <button class="btn btn-secondary" disabled>
                ❌ Đã hủy
            </button>
            <?php endif; ?>

            <!-- Nút quay lại -->
            <a href="<?php echo e(route('booking.history', ['userId' => auth()->id()])); ?>" class="btn btn-secondary">
                ← Quay lại lịch sử
            </a>
        </div>

        <!-- Modal xác nhận hủy đặt phòng -->
        <div id="cancelModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%;">
                <h3 style="margin: 0 0 20px 0; color: #333;">Xác nhận hủy đặt phòng</h3>

                <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <strong>⚠️ Chính sách hủy đặt phòng:</strong>
                    <ul style="margin: 10px 0 0 20px; color: #856404;">
                        <li>Hủy trước 7 ngày: Hoàn 100% tiền</li>
                        <li>Hủy trước 3 ngày: Hoàn 80% tiền</li>
                        <li>Hủy trước 1 ngày: Hoàn 50% tiền</li>
                        <li>Hủy trong 24h: Không hoàn tiền</li>
                    </ul>
                </div>

                <?php
                $totalPrice = $booking->total_price;
                $checkInTime = strtotime($booking->check_in_date);
                $currentTime = time();
                $hoursUntilCheckIn = ($checkInTime - $currentTime) / 3600;

                if ($hoursUntilCheckIn >= 168) { // 7 ngày
                $refundAmount = $totalPrice;
                $refundPercent = 100;
                } elseif ($hoursUntilCheckIn >= 72) { // 3 ngày
                $refundAmount = $totalPrice * 0.8;
                $refundPercent = 80;
                } elseif ($hoursUntilCheckIn >= 24) { // 1 ngày
                $refundAmount = $totalPrice * 0.5;
                $refundPercent = 50;
                } else {
                $refundAmount = 0;
                $refundPercent = 0;
                }
                ?>

                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>Tổng tiền đã thanh toán:</span>
                        <strong><?php echo e(number_format($totalPrice, 0, ',', '.')); ?> VNĐ</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; color: #28a745;">
                        <span>Số tiền được hoàn lại (<?php echo e($refundPercent); ?>%):</span>
                        <strong><?php echo e(number_format($refundAmount, 0, ',', '.')); ?> VNĐ</strong>
                    </div>
                </div>

                <p style="color: #666; margin-bottom: 25px;">
                    Bạn có chắc chắn muốn hủy đặt phòng này không? Thao tác này không thể hoàn tác.
                </p>

                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button onclick="hideCancelModal()" class="btn btn-secondary">
                        Không, giữ đặt phòng
                    </button>
                    <form id="cancelForm" method="POST" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-danger">
                            Có, hủy đặt phòng
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<style>
    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background: #c82333;
    }
</style>

<script>
    function showCancelModal(bookingId) {
        document.getElementById('cancelForm').action = '/booking/cancel/' + bookingId;
        document.getElementById('cancelModal').style.display = 'block';
    }

    function hideCancelModal() {
        document.getElementById('cancelModal').style.display = 'none';
    }

    // Đóng modal khi click outside
    document.getElementById('cancelModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideCancelModal();
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/user/booking_detail.blade.php ENDPATH**/ ?>