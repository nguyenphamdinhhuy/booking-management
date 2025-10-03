

<?php $__env->startSection('title', 'Hủy đơn đặt phòng'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .cancel-container {
        max-width: 800px;
        margin: 0 auto;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .cancel-box {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, .08);
        padding: 24px;
        margin-top: 20px;
    }

    .cancel-box h2 {
        margin-top: 0;
        font-size: 22px;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #f1f1f1;
        padding-bottom: 12px;
    }

    .cancel-box p {
        color: #555;
        margin: 12px 0 20px 0;
    }

    .cancel-form .form-group {
        margin-bottom: 18px;
    }

    .cancel-form .form-label {
        display: block;
        font-weight: 500;
        margin-bottom: 6px;
        color: #333;
    }

    .cancel-form textarea.form-control {
        width: 100%;
        border: 1px solid #dcdcdc;
        border-radius: 8px;
        padding: 12px;
        font-size: 14px;
        resize: vertical;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .cancel-form textarea.form-control:focus {
        border-color: #0066cc;
        box-shadow: 0 0 0 3px rgba(0,102,204,0.15);
        outline: none;
    }

    .cancel-actions {
        display: flex;
        gap: 12px;
    }

    .btn {
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.2s ease;
        border: none;
    }

    .btn-danger {
        background-color: #dc3545;
        color: #fff;
    }

    .btn-danger:hover {
        background-color: #b52a37;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: #fff;
    }

    .btn-secondary:hover {
        background-color: #565e64;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        font-weight: 500;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
</style>

<div class="cancel-container">
    <?php if(session('error')): ?>
        <div class="alert alert-error"><?php echo e(session('error')); ?></div>
    <?php endif; ?>
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="cancel-box">
        <h2>Hủy đơn #<?php echo e($booking->b_id); ?></h2>
        <p>Khách hàng sẽ <strong>không</strong> bị thu phí vì đơn chưa thanh toán.</p>

        <form method="POST" action="<?php echo e(route('admin.bookings.cancel', $booking->b_id)); ?>" class="cancel-form">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label class="form-label">Lý do hủy <span style="color:#dc3545">*</span></label>
                <textarea name="reason" rows="4" class="form-control" required></textarea>
                <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div style="color:#dc3545;margin-top:6px;font-size:13px;"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="cancel-actions">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-times"></i> Xác nhận hủy
                </button>
                <a href="<?php echo e(route('admin.bookings.view', $booking->b_id)); ?>" class="btn btn-secondary">
                    Quay lại
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/admin/bookings/cancel.blade.php ENDPATH**/ ?>