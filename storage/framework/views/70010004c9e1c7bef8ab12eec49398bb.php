<?php $__env->startSection("content"); ?>


<?php if(session('success')): ?>
<div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 20px;">
    <?php echo e(session('success')); ?>

</div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 20px;">
    <?php echo e(session('error')); ?>

</div>
<?php endif; ?>

<h1 class="page-title">
    <i class="fas fa-calendar-check"></i>
    Quản lý Đơn đặt phòng
</h1>

<!-- Booking Management Table -->
<div class="table-container">
    <!-- Table Header -->
    <div class="table-header">
        <h2 class="table-title">Danh sách đơn đặt phòng (<span><?php echo e($bookings->count()); ?></span> đơn)</h2>
    </div>

    <!-- Data Table -->
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID Đơn</th>
                    <th>Khách hàng</th>
                    <th>Phòng</th>
                    <th>Ngày nhận/trả</th>
                    <th>Số đêm</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><strong><?php echo e($booking->b_id); ?></strong></td>
                    <td>
                        <div>
                            <strong><?php echo e($booking->user_name); ?></strong><br>
                            <small style="color: #666;"><?php echo e($booking->user_email); ?></small>
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center;">
                            <div>
                                <strong><?php echo e($booking->room_name); ?></strong><br>
                                <small style="color: #666;"><?php echo e($booking->formatted_price_per_night); ?></small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <strong><?php echo e($booking->formatted_check_in); ?></strong><br>
                            <small style="color: #666;">đến <?php echo e($booking->formatted_check_out); ?></small>
                        </div>
                    </td>
                    <td><strong><?php echo e($booking->nights); ?> đêm</strong></td>
                    <td><strong style="color: #e74c3c;"><?php echo e($booking->formatted_total_price); ?></strong></td>
                    <td>
                        
                        <?php if($booking->status == 0): ?>
                        <span class="status-badge" style="background: #fff3cd; color: #856404; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                            <i class="fas fa-credit-card"></i> Chờ thanh toán
                        </span>
                        <?php elseif($booking->status == 1): ?>
                        <span class="status-badge" style="background: #e3f2fd; color: #1565c0; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                            <i class="fas fa-hourglass-half"></i> Chờ xác nhận đơn
                        </span>
                        <?php elseif($booking->status == 2): ?>
                        <span class="status-badge" style="background: #fff3e0; color: #ef6c00; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                            <i class="fas fa-key"></i> Chờ nhận phòng
                        </span>
                        <?php elseif($booking->status == 3): ?>
                        <span class="status-badge" style="background: #fce4ec; color: #c2185b; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                            <i class="fas fa-clock"></i> Chờ xác nhận trả phòng
                        </span>
                        <?php elseif($booking->status == 4): ?>
                        <span class="status-badge" style="background: #d4edda; color: #155724; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                            <i class="fas fa-check-circle"></i> Hoàn tất
                        </span>
                        <?php else: ?>
                        <span class="status-badge" style="background: #f8d7da; color: #721c24; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                            <i class="fas fa-times-circle"></i> Đã hủy
                        </span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($booking->formatted_created_at); ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="<?php echo e(route('admin.bookings.view', $booking->b_id)); ?>"
                                class="btn btn-primary btn-sm" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>

                            
                            <?php if($booking->status == 1): ?>
                            <form method="POST" action="<?php echo e(route('admin.bookings.confirm', $booking->b_id)); ?>"
                                style="display: inline-block;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-info btn-sm"
                                    title="Xác nhận đơn đặt phòng"
                                    onclick="return confirm('Xác nhận đơn đặt phòng này? Phòng sẽ được đánh dấu là đã được đặt.')">
                                    <i class="fas fa-check"></i> Xác nhận đơn
                                </button>
                            </form>

                            <!-- Nút hủy đặt phòng -->
                            <a href="<?php echo e(route('admin.bookings.cancel.form', $booking->b_id)); ?>"
                                class="btn btn-danger btn-sm" title="Hủy đặt phòng">
                                <i class="fas fa-times"></i> Hủy
                            </a>

                            
                            <?php elseif($booking->status == 2): ?>
                            <button class="btn btn-warning btn-sm" disabled title="Chờ khách xác nhận nhận phòng">
                                <i class="fas fa-key"></i> Chờ nhận phòng
                            </button>

                            <!-- Nút hủy đặt phòng -->
                            <a href="<?php echo e(route('admin.bookings.cancel.form', $booking->b_id)); ?>"
                                class="btn btn-danger btn-sm" title="Hủy đặt phòng">
                                <i class="fas fa-times"></i> Hủy
                            </a>

                            
                            <?php elseif($booking->status == 3): ?>
                            <form method="POST" action="<?php echo e(route('admin.bookings.confirm.checkout', $booking->b_id)); ?>"
                                style="display: inline-block;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-success btn-sm"
                                    title="Xác nhận khách đã trả phòng thành công"
                                    onclick="return confirm('Xác nhận khách hàng đã trả phòng thành công? Phòng sẽ được cập nhật trạng thái còn trống.')">
                                    <i class="fas fa-check-double"></i> Xác nhận trả phòng
                                </button>
                            </form>

                            <!-- Nút hủy đặt phòng -->
                            <a href="<?php echo e(route('admin.bookings.cancel.form', $booking->b_id)); ?>"
                                class="btn btn-danger btn-sm" title="Hủy đặt phòng">
                                <i class="fas fa-times"></i> Hủy
                            </a>

                            
                            <?php elseif($booking->status == 4): ?>
                            <button class="btn btn-secondary btn-sm" disabled title="Đã hoàn tất">
                                <i class="fas fa-check-circle"></i> Đã hoàn tất
                            </button>

                            
                            <?php elseif($booking->status == 0): ?>
                            <button class="btn btn-outline-warning btn-sm" disabled title="Chờ khách thanh toán">
                                <i class="fas fa-credit-card"></i> Chờ thanh toán
                            </button>

                            
                            <?php else: ?>
                            <button class="btn btn-outline-secondary btn-sm" disabled title="Đã hủy">
                                <i class="fas fa-times-circle"></i> Đã hủy
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-calendar-times" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                        <span>Chưa có đơn đặt phòng nào.</span>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Thêm styles cho status badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-weight: 500;
        white-space: nowrap;
    }

    .action-buttons {
        display: flex;
        gap: 5px;
        align-items: center;
        flex-wrap: wrap;
    }

    .action-buttons .btn {
        white-space: nowrap;
    }

    /* Responsive cho mobile */
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
            gap: 2px;
        }

        .action-buttons .btn {
            width: 100%;
            font-size: 11px;
            padding: 4px 6px;
        }
    }
</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app\resources\views/admin/bookings/management.blade.php ENDPATH**/ ?>