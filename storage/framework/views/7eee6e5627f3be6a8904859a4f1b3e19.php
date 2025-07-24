

<?php $__env->startSection('title', 'Tài khoản bị khóa'); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .locked-container {
            max-width: 600px;
            margin: 100px auto;
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .locked-container h1 {
            color: #dc3545;
            /* Bootstrap red */
            font-size: 2rem;
        }

        .locked-container p {
            color: #555;
            font-size: 1rem;
        }

        .locked-container a.btn {
            padding: 10px 25px;
            font-size: 1rem;
        }

        body {
            background: #f8f9fa;
        }
    </style>

    <div class="container mt-5 text-center">
        <div class="locked-container">
            <h1 class="text-danger">Tài khoản đã bị khóa</h1>
            <p class="mt-3">Tài khoản của bạn hiện đang bị khóa và không thể đăng nhập vào hệ thống.</p>
            <p>Vui lòng liên hệ quản trị viên để biết thêm chi tiết.</p>

            <a href="<?php echo e(route('login')); ?>" class="btn btn-primary mt-4">Quay lại trang đăng nhập</a>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\booking-management\resources\views/auth/locked.blade.php ENDPATH**/ ?>