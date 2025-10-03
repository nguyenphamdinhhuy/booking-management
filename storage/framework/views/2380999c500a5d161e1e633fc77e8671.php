<?php $__env->startSection('title', 'Chi tiết đơn đặt phòng'); ?>

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

    .room-type-info {
        padding: 25px;
    }

    .room-type-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .room-type-name {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
    }

    .room-type-features {
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

    .room-assignment-section {
        background: #f8f9fa;
        padding: 25px;
        border-top: 1px solid #eee;
    }

    .assignment-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .current-assignment {
        background: #e8f5e8;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 2px solid #d4edda;
    }

    .assignment-form {
        background: white;
        padding: 20px;
        border-radius: 8px;
        border: 2px solid #dee2e6;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-weight: 500;
        margin-bottom: 8px;
        color: #333;
    }

    .form-select {
        width: 100%;
        padding: 12px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        font-size: 14px;
        background: white;
    }

    .form-select:focus {
        outline: none;
        border-color: #0066cc;
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
    }

    .room-option-details {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 6px;
        margin-top: 10px;
        font-size: 13px;
        color: #666;
    }

    .booking-dates {
        background: #f8f9fa;
        padding: 20px;
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
        gap: 12px;
        flex-wrap: wrap;
        justify-content: center;
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

    .adbks-svcard {
        border: 2px solid #e7ecf5;
        border-radius: 12px;
        background: #fbfdff;
        padding: 16px;
        margin-top: 16px
    }

    .adbks-svcard__head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px
    }

    .adbks-svcard__ico {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #eaf1ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #003580
    }

    .adbks-svlist {
        display: flex;
        flex-direction: column;
        gap: 10px
    }

    .adbks-svrow {
        display: grid;
        grid-template-columns: 1fr 90px 180px;
        gap: 12px;
        align-items: center;
        border: 1px solid #e9eef6;
        background: #fff;
        border-radius: 10px;
        padding: 10px
    }

    .adbks-svrow__name {
        font-weight: 700;
        color: #0f224a
    }

    .adbks-svrow__meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 2px
    }

    .adbks-chip {
        background: #e9f2ff;
        color: #003580;
        border: 1px solid #cfe0ff;
        border-radius: 999px;
        padding: 3px 8px;
        font-size: 12px;
        font-weight: 700
    }

    .adbks-dot {
        color: #9aa8c7
    }

    .adbks-muted {
        color: #6c7aa6
    }

    .adbks-svrow__mid {
        text-align: center;
        color: #1b2b55;
        font-weight: 700
    }

    .adbks-svrow__right {
        text-align: right
    }

    .adbks-svrow__unit {
        color: #6c7aa6;
        font-size: 13px
    }

    .adbks-svrow__sum {
        font-weight: 900;
        color: #0e1e46
    }

    .adbks-svtotal {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px dashed #d9e4ff;
        margin-top: 8px;
        padding-top: 10px;
        color: #003580
    }

    .adbks-svempty {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6c7aa6;
        background: #f7faff;
        border: 1px dashed #d9e4ff;
        padding: 10px;
        border-radius: 10px
    }

    @media (max-width: 768px) {
        .adbks-svrow {
            grid-template-columns: 1fr 70px 140px
        }
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

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background: #c82333;
    }

    .btn-info {
        background: #17a2b8;
        color: white;
    }

    .btn-info:hover {
        background: #138496;
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

    .urgency-indicator {
        background: #fff3cd;
        border: 2px solid #ffc107;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .urgency-indicator.urgent {
        background: #f8d7da;
        border-color: #dc3545;
        color: #721c24;
    }

    .checkin-ready-indicator {
        background: #e8f4fd;
        border: 2px solid #17a2b8;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #0c5460;
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

        .room-type-features {
            flex-direction: column;
            gap: 10px;
        }

        .booking-detail-container {
            padding: 15px;
        }
    }
</style>

<div class="booking-detail-container">
    <?php if(session('error')): ?>
    <div class="alert alert-error">
        <?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>

    <?php if(session('success')): ?>
    <div class="alert alert-success">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <!-- Urgency Indicator -->
    <?php if($booking->status == 1): ?>
    <?php
    $checkInDate = \Carbon\Carbon::parse($booking->check_in_date);
    $now = \Carbon\Carbon::now();
    $daysUntilCheckIn = $now->diffInDays($checkInDate, false);
    ?>

    <div class="urgency-indicator <?php echo e($daysUntilCheckIn <= 1 ? 'urgent' : ''); ?>">
        <i class="fas fa-exclamation-triangle" style="font-size: 20px;"></i>
        <div>
            <strong>Cần xử lý gấp!</strong>
            <?php if($daysUntilCheckIn <= 0): ?>
                Khách hàng sẽ nhận phòng hôm nay. Vui lòng chọn phòng ngay lập tức.
                <?php elseif($daysUntilCheckIn==1): ?>
                Khách hàng sẽ nhận phòng vào ngày mai. Cần chọn phòng sớm nhất có thể.
                <?php else: ?>
                Còn <?php echo e($daysUntilCheckIn); ?> ngày đến ngày nhận phòng. Cần chọn phòng cho khách hàng.
                <?php endif; ?>
                </div>
        </div>
        <?php endif; ?>

        <!-- Check-in Ready Indicator -->
        <?php if($booking->status == 2): ?>
        <div class="checkin-ready-indicator">
            <i class="fas fa-door-open" style="font-size: 20px;"></i>
            <div>
                <strong>Sẵn sàng nhận phòng!</strong>
                Phòng <?php echo e($booking->assigned_room_name); ?> đã được chọn. Khách hàng có thể nhận phòng từ ngày <?php echo e(\Carbon\Carbon::parse($booking->check_in_date)->format('d/m/Y')); ?>.
                <br><small>Bấm nút "Xác nhận nhận phòng" khi khách hàng đã đến và nhận phòng thành công.</small>
            </div>
        </div>
        <?php endif; ?>

        <!-- Header -->
        <div class="booking-header">
            <h1>Chi tiết đơn đặt phòng</h1>
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
                ⏳ Chờ admin xử lý (chọn phòng)
                <?php elseif($booking->status == 2): ?>
                🔑 Chờ admin xác nhận nhận phòng
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
                <div class="room-type-info">
                    <!-- Tên loại phòng -->
                    <h2 class="room-type-name"><?php echo e($booking->room_type_name ?? 'Loại phòng không xác định'); ?></h2>

                    <div class="room-type-features">
                        <div class="feature">
                            <span class="feature-icon">👥</span>
                            <span>Tối đa <?php echo e($booking->max_guests ?? 'N/A'); ?> khách</span>
                        </div>
                        <div class="feature">
                            <span class="feature-icon">🛏️</span>
                            <span><?php echo e($booking->number_beds ?? 'N/A'); ?> giường</span>
                        </div>
                        <div class="feature">
                            <span class="feature-icon">📏</span>
                            <span><?php echo e($booking->room_size ?? 'N/A'); ?></span>
                        </div>
                    </div>

                    <!-- Mô tả loại phòng -->
                    <?php if($booking->room_type_description): ?>
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 15px;">
                        <strong>Mô tả loại phòng:</strong>
                        <p style="margin: 8px 0 0 0; color: #666;"><?php echo e($booking->room_type_description); ?></p>
                    </div>
                    <?php endif; ?>

                    
                    <?php if(isset($services)): ?>
                    <div class="adbks-svcard">
                        <div class="adbks-svcard__head">
                            <div class="adbks-svcard__ico"><i class="fas fa-concierge-bell"></i></div>
                            <h3>Dịch vụ kèm theo</h3>
                        </div>

                        <?php if($services->count() > 0): ?>
                        <div class="adbks-svlist">
                            <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="adbks-svrow">
                                <div class="adbks-svrow__left">
                                    <div class="adbks-svrow__name"><?php echo e($svc->service_name); ?></div>
                                    <div class="adbks-svrow__meta">
                                        <span class="adbks-chip"><?php echo e($svc->pricing_label); ?></span>
                                        <?php if($svc->scheduled_fmt): ?>
                                        <span class="adbks-dot">•</span>
                                        <span class="adbks-muted">Lịch: <?php echo e($svc->scheduled_fmt); ?></span>
                                        <?php endif; ?>
                                        <?php if($svc->location): ?>
                                        <span class="adbks-dot">•</span>
                                        <span class="adbks-muted">Khu vực: <?php echo e($svc->location); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="adbks-svrow__mid">
                                    x<?php echo e($svc->quantity_int); ?>

                                </div>

                                <div class="adbks-svrow__right">
                                    <div class="adbks-svrow__unit"><?php echo e($svc->unit_price_fmt); ?></div>
                                    <div class="adbks-svrow__sum"><?php echo e($svc->subtotal_fmt); ?></div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="adbks-svtotal">
                            <span>Tổng dịch vụ</span>
                            <strong><?php echo e($services_total_fmt); ?></strong>
                        </div>
                        <?php else: ?>
                        <div class="adbks-svempty">
                            <i class="far fa-smile"></i> Không có dịch vụ kèm theo.
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>


                    <!-- Ghi chú đặt phòng -->
                    <?php if($booking->booking_description): ?>
                    <div style="background: #e8f4fd; padding: 15px; border-radius: 8px; margin-top: 15px;">
                        <strong>Ghi chú từ khách hàng:</strong>
                        <p style="margin: 8px 0 0 0; color: #666;"><?php echo e($booking->booking_description); ?></p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Room Assignment Section -->
                <div class="room-assignment-section">
                    <h3 class="assignment-title">
                        <i class="fas fa-door-open"></i>
                        Quản lý phòng
                    </h3>

                    <!-- Current Assignment -->
                    <?php if($booking->r_id_assigned): ?>
                    <div class="current-assignment">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            <i class="fas fa-check-circle" style="color: #28a745; font-size: 20px;"></i>
                            <strong style="color: #28a745;">Đã chọn phòng:</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <strong style="font-size: 16px;"><?php echo e($booking->assigned_room_name); ?></strong>
                                <div style="font-size: 14px; color: #666; margin-top: 2px;">
                                    Phòng số <?php echo e($booking->assigned_room_name); ?> - <?php echo e($booking->room_type_name); ?>

                                </div>
                            </div>
                            <?php if($booking->status == 1): ?>
                            <form method="POST" action="<?php echo e(route('admin.bookings.confirm.room', $booking->b_id)); ?>" style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-success btn-sm"
                                    onclick="return confirm('Xác nhận phòng đã chọn và chuyển trạng thái sang chờ nhận phòng?')">
                                    <i class="fas fa-check"></i> Xác nhận phòng
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Room Selection Form -->
                    <?php if($booking->status == 1): ?>
                    <div class="assignment-form">
                        <form method="POST" action="<?php echo e(route('admin.bookings.assign.room', $booking->b_id)); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-list"></i>
                                    Chọn phòng trống cho loại phòng "<?php echo e($booking->room_type_name); ?>"
                                </label>
                                <select name="room_id" class="form-select" onchange="showRoomDetails(this)" required>
                                    <option value="">-- Chọn phòng --</option>
                                    <?php $__currentLoopData = $availableRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($room->r_id); ?>"
                                        data-name="<?php echo e($room->name); ?>"
                                        data-description="<?php echo e($room->description); ?>"
                                        data-price="<?php echo e(number_format($room->price_per_night, 0, ',', '.')); ?>"
                                        <?php echo e($booking->r_id_assigned == $room->r_id ? 'selected' : ''); ?>>
                                        Phòng <?php echo e($room->name); ?> - <?php echo e(number_format($room->price_per_night, 0, ',', '.')); ?> VNĐ/đêm
                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>

                                <?php if(count($availableRooms) == 0): ?>
                                <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 6px; margin-top: 10px;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>Không có phòng trống!</strong>
                                    Tất cả phòng loại "<?php echo e($booking->room_type_name); ?>" đều đã được đặt trong khoảng thời gian này.
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Room Details Preview -->
                            <div id="roomDetails" class="room-option-details" style="display: none;">
                                <strong>Chi tiết phòng đã chọn:</strong>
                                <div id="roomDetailsContent"></div>
                            </div>

                            <?php if(count($availableRooms) > 0): ?>
                            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                                <button type="submit" class="btn btn-primary"
                                    onclick="return confirm('Chọn phòng này cho khách hàng?')">
                                    <i class="fas fa-save"></i> <?php echo e($booking->r_id_assigned ? 'Cập nhật phòng' : 'Chọn phòng'); ?>

                                </button>
                            </div>
                            <?php endif; ?>
                        </form>
                    </div>
                    <?php endif; ?>

                    <!-- Status Messages for other statuses -->
                    <?php if($booking->status == 2): ?>
                    <div style="background: #e8f4fd; padding: 15px; border-radius: 8px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-info-circle" style="color: #0066cc; font-size: 18px;"></i>
                            <div>
                                <strong>Đã chọn phòng <?php echo e($booking->assigned_room_name); ?></strong>
                                <div style="font-size: 14px; color: #666; margin-top: 5px;">
                                    Phòng đã được xác nhận. Chờ admin xác nhận khách hàng nhận phòng.
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php elseif($booking->status >= 3): ?>
                    <div style="background: #d4edda; padding: 15px; border-radius: 8px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-check-circle" style="color: #28a745; font-size: 18px;"></i>
                            <div>
                                <strong>Phòng <?php echo e($booking->assigned_room_name); ?> đã được giao</strong>
                                <div style="font-size: 14px; color: #666; margin-top: 5px;">
                                    Khách hàng đã nhận phòng thành công.
                                </div>
                            </div>
                        </div>
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
            <h3 class="summary-title">Thông tin đặt phòng</h3>

            <div class="summary-item">
                <span class="summary-label">Khách hàng</span>
                <span class="summary-value"><?php echo e($booking->user_name); ?></span>
            </div>

            <div class="summary-item">
                <span class="summary-label">Email</span>
                <span class="summary-value"><?php echo e($booking->user_email); ?></span>
            </div>

            <div class="summary-item">
                <span class="summary-label">Loại phòng</span>
                <span class="summary-value"><?php echo e($booking->room_type_name); ?></span>
            </div>

            <div class="summary-item">
                <span class="summary-label">Giá cơ bản/đêm</span>
                <span class="summary-value"><?php echo e(number_format($booking->base_price, 0, ',', '.')); ?> VNĐ</span>
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
                <span class="summary-label">Tiền phòng (ước tính)</span>
                <span class="summary-value"><?php echo e($room_only_total_fmt); ?></span>
            </div>

            <div class="summary-item">
                <span class="summary-label">Dịch vụ kèm theo</span>
                <span class="summary-value"><?php echo e($services_total_fmt); ?></span>
            </div>

            <div class="summary-item">
                <span class="summary-label">Tổng tiền</span>
                <span class="summary-value"><?php echo e(number_format($booking->total_price, 0, ',', '.')); ?> VNĐ</span>
            </div>

            <!-- Action buttons -->
            <div class="action-buttons">
                
                <?php if($booking->status == 1 && $booking->r_id_assigned): ?>
                <form method="POST" action="<?php echo e(route('admin.bookings.confirm.room', $booking->b_id)); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success"
                        onclick="return confirm('Xác nhận phòng <?php echo e($booking->assigned_room_name); ?> và chuyển trạng thái sang chờ nhận phòng?')">
                        <i class="fas fa-check"></i> Xác nhận phòng đã chọn
                    </button>
                </form>
                <?php endif; ?>

                
                <?php if($booking->status == 2): ?>
                <form method="POST" action="<?php echo e(route('admin.bookings.confirm.checkin', $booking->b_id)); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-info"
                        onclick="return confirm('Xác nhận khách hàng đã nhận phòng <?php echo e($booking->assigned_room_name); ?>?')">
                        <i class="fas fa-door-open"></i> Xác nhận khách đã nhận phòng
                    </button>
                </form>
                <?php endif; ?>

                
                <?php if($booking->status == 3): ?>
                <form method="POST" action="<?php echo e(route('admin.bookings.confirm.checkout', $booking->b_id)); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success"
                        onclick="return confirm('Xác nhận khách hàng đã trả phòng thành công?')">
                        <i class="fas fa-check-double"></i> Xác nhận trả phòng
                    </button>
                </form>
                <?php endif; ?>

                
                <?php if(in_array($booking->status, [1,2])): ?>
                <?php if($booking->payment_status == 1): ?>
                
                <button type="button" class="btn btn-secondary" disabled title="Đơn đã thanh toán, không thể hủy">
                    <i class="fas fa-ban"></i> Hủy đặt phòng
                </button>

                <form method="POST" action="<?php echo e(route('admin.bookings.refund', $booking->b_id)); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-warning"
                        onclick="return confirm('Xác nhận hoàn tiền cho đơn #<?php echo e($booking->b_id); ?>?');">
                        <i class="fas fa-undo"></i> Hoàn tiền
                    </button>
                </form>
                <?php else: ?>
                
                <a href="<?php echo e(route('admin.bookings.cancel.form', $booking->b_id)); ?>" class="btn btn-danger">
                    <i class="fas fa-times"></i> Hủy đặt phòng
                </a>
                <?php endif; ?>
                <?php endif; ?>

                <a href="<?php echo e(route('admin.bookings.management')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                </a>
            </div>

        </div>
    </div>
</div>

<script>
    function showRoomDetails(select) {
        const selectedOption = select.options[select.selectedIndex];
        const detailsDiv = document.getElementById('roomDetails');
        const contentDiv = document.getElementById('roomDetailsContent');

        if (selectedOption.value) {
            const name = selectedOption.dataset.name;
            const description = selectedOption.dataset.description;
            const price = selectedOption.dataset.price;

            contentDiv.innerHTML = `
            <div style="margin-top: 8px;">
                <div><strong>Phòng:</strong> ${name}</div>
                <div><strong>Giá:</strong> ${price} VNĐ/đêm</div>
                ${description ? `<div><strong>Mô tả:</strong> ${description}</div>` : ''}
            </div>
        `;
            detailsDiv.style.display = 'block';
        } else {
            detailsDiv.style.display = 'none';
        }
    }

    // Auto show room details if room is already selected
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.querySelector('select[name="room_id"]');
        if (select && select.value) {
            showRoomDetails(select);
        }
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/admin/bookings/view.blade.php ENDPATH**/ ?>