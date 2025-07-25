<?php $__env->startSection('content'); ?>
<style>
    .form-wrapper {
        max-width: 450px;
        margin: 40px auto;
        padding: 30px;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        font-family: 'Segoe UI', sans-serif;
    }

    .form-wrapper h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #333;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #444;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        transition: border 0.3s;
    }

    .form-control:focus {
        border-color: #007bff;
        outline: none;
    }

    .form-error {
        color: #dc3545;
        font-size: 13px;
        margin-top: 5px;
    }

    .btn-submit {
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        border: none;
        color: white;
        font-weight: bold;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-submit:hover {
        background-color: #0056b3;
    }

    .form-footer {
        text-align: center;
        margin-top: 15px;
        font-size: 14px;
    }

    .form-footer a {
        color: #007bff;
        text-decoration: none;
    }

    .form-footer a:hover {
        text-decoration: underline;
    }
</style>

<div class="form-wrapper">
    <h2>Đăng ký tài khoản</h2>
    <form method="POST" action="<?php echo e(route('pending.register')); ?>">

        <?php echo csrf_field(); ?>

        <div class="form-group">
            <label for="name" class="form-label">Họ và tên</label>
            <input id="name" type="text" placeholder="Nhập tên đầy đủ" class="form-control" name="name" value="<?php echo e(old('name')); ?>" autofocus>
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="form-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" placeholder="Nhập email của bạn" class="form-control" name="email" value="<?php echo e(old('email')); ?>">
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="form-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Mật khẩu</label>
            <input id="password" placeholder="Nhập mật khẩu" type="password" class="form-control" name="password">
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="form-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label for="password-confirm" class="form-label">Xác nhận mật khẩu</label>
            <input id="password-confirm" placeholder="Nhập lại mật khẩu" type="password" class="form-control" name="password_confirmation">
        </div>

        <button type="submit" class="btn-submit">Đăng ký</button>

        <div class="form-footer">
            Bạn đã có tài khoản? <a href="<?php echo e(route('login')); ?>">Đăng nhập</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\booking-management\resources\views/auth/register.blade.php ENDPATH**/ ?>