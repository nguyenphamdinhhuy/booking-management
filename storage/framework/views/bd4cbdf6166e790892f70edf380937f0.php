
<?php $__env->startSection("content"); ?>

<style>
    .cancel-form-container {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .form-header {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        padding: 25px;
        text-align: center;
    }

    .form-header h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }

    .form-body {
        padding: 30px;
    }

    .booking-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 25px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #dee2e6;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 500;
        color: #666;
    }

    .info-value {
        font-weight: 600;
        color: #333;
    }

    .warning-box {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 25px;
    }

    .warning-title {
        color: #856404;
        font-weight: 600;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .warning-content {
        color: #856404;
        line-height: 1.5;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #0066cc;
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
    }

    .form-actions {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        padding-top: 20px;
        border-top: 1px solid #dee2e6;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background: #c82333;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #545b62;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
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

    @media (max-width: 768px) {
        .form-actions {
            flex-direction: column;
        }

        .btn {
            text-align: center;
        }
    }
</style>


<?php if(session('success')): ?>
<div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
    <?php echo e(session('success')); ?>

</div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
    <?php echo e(session('error')); ?>

</div>
<?php endif; ?>

<div class="cancel-form-container">
    <div class="form-header">
        <h1>🗑️ Hủy đặt phòng</h1>
        <p style="margin: 8px 0 0 0; opacity: 0.9;">Mã đặt phòng: #<?php echo e($booking->b_id); ?></p>
    </div>

    <div class="form-body">
        <!-- Thông tin đặt phòng -->
        <div class="booking-info">
            <h3 style="margin: 0 0 15px 0; color: #333;">📋 Thông tin đặt phòng</h3>
            
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Khách hàng:</span>
                    <span class="info-value"><?php echo e($booking->user_name); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?php echo e($booking->user_email); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Phòng:</span>
                    <span class="info-value"><?php echo e($booking->room_name); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Ngày nhận phòng:</span>
                    <span class="info-value"><?php echo e($booking->formatted_check_in); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Ngày trả phòng:</span>
                    <span class="info-value"><?php echo e($booking->formatted_check_out); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tổng tiền:</span>
                    <span class="info-value"><?php echo e($booking->formatted_total_price); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Trạng thái:</span>
                    <span class="info-value">
                        <?php if($booking->status == 1): ?>
                        <span class="status-badge status-waiting-admin">
                            ⏳ Chờ xác nhận
                        </span>
                        <?php elseif($booking->status == 2): ?>
                        <span class="status-badge status-confirmed">
                            🔑 Đã xác nhận
                        </span>
                        <?php elseif($booking->status == 3): ?>
                        <span class="status-badge status-checkedin">
                            🛏️ Đã nhận phòng
                        </span>
                        <?php endif; ?>
                    </span>
                </div>
            </div>

            <?php if($booking->booking_description): ?>
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #dee2e6;">
                <div class="info-item">
                    <span class="info-label">Ghi chú:</span>
                    <span class="info-value"><?php echo e($booking->booking_description); ?></span>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Cảnh báo -->
        <div class="warning-box">
            <div class="warning-title">
                <i class="fas fa-exclamation-triangle"></i>
                Lưu ý quan trọng
            </div>
            <div class="warning-content">
                <ul style="margin: 8px 0 0 20px;">
                    <li>Việc hủy đặt phòng sẽ không thể hoàn tác</li>
                    <li>Khách hàng sẽ được hoàn tiền 100% (admin hủy)</li>
                    <li>Phòng sẽ được giải phóng và có thể đặt lại</li>
                    <li>Khách hàng sẽ nhận thông báo qua email</li>
                </ul>
            </div>
        </div>

        <!-- Form hủy đặt phòng -->
        <form method="POST" action="<?php echo e(route('admin.bookings.cancel', $booking->b_id)); ?>">
            <?php echo csrf_field(); ?>
            
            <div class="form-group">
                <label for="cancel_reason" class="form-label">
                    📝 Lý do hủy đặt phòng <span style="color: #dc3545;">*</span>
                </label>
                <textarea 
                    name="cancel_reason" 
                    id="cancel_reason" 
                    class="form-control" 
                    rows="4" 
                    placeholder="Vui lòng nhập lý do hủy đặt phòng (tối đa 500 ký tự)..."
                    maxlength="500" 
                    required><?php echo e(old('cancel_reason')); ?></textarea>
                
                <?php $__errorArgs = ['cancel_reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div style="color: #dc3545; font-size: 13px; margin-top: 5px;">
                    <?php echo e($message); ?>

                </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                
                <div style="text-align: right; font-size: 12px; color: #6c757d; margin-top: 5px;">
                    <span id="charCount">0</span>/500 ký tự
                </div>
            </div>

            <div class="form-actions">
                <a href="<?php echo e(route('admin.bookings.management')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Quay lại
                </a>
                
                <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn hủy đặt phòng này không?')">
                    <i class="fas fa-times-circle"></i>
                    Xác nhận hủy đặt phòng
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Đếm ký tự
document.getElementById('cancel_reason').addEventListener('input', function() {
    const charCount = this.value.length;
    document.getElementById('charCount').textContent = charCount;
    
    if (charCount > 500) {
        document.getElementById('charCount').style.color = '#dc3545';
    } else if (charCount > 450) {
        document.getElementById('charCount').style.color = '#ffc107';
    } else {
        document.getElementById('charCount').style.color = '#6c757d';
    }
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/admin/bookings/cancel_form.blade.php ENDPATH**/ ?>