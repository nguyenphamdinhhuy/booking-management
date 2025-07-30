<?php $__env->startSection('content'); ?>
<style>
    body {
        background: #f0f2f5;
        font-family: 'Segoe UI', sans-serif;
    }

    .form-wrapper {
        max-width: 420px;
        margin: 50px auto;
        padding: 35px 30px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }

    .form-wrapper h2 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 24px;
        color: #333;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
        color: #333;
    }

    .form-control {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
        transition: 0.3s ease;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.15);
        outline: none;
    }

    .form-error {
        color: #dc3545;
        font-size: 13px;
        margin-top: 6px;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .btn-submit {
        width: 100%;
        padding: 12px;
        background-color: #007bff;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        color: #fff;
        transition: background 0.3s ease;
        cursor: pointer;
    }

    .btn-submit:hover {
        background-color: #0056b3;
    }

    .form-footer {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
    }

    .form-footer a {
        color: #007bff;
        text-decoration: none;
    }

    .form-footer a:hover {
        text-decoration: underline;
    }

    .social-login {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 25px;
    }

    .social-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
        font-size: 14px;
        color: #fff;
        transition: 0.3s ease;
    }

    .social-btn.google {
        background: #db4437;
    }

    .social-btn.zalo {
        background: #018fe2;
    }

    .social-btn i {
        margin-right: 8px;
    }

    .social-login {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 25px;
    }

    .social-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px 16px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .social-btn i {
        margin-right: 10px;
        font-size: 18px;
    }

    .social-btn.google {
        background-color: #fff;
        color: #444;
        border: 1px solid #ddd;
    }

    .social-btn.google:hover {
        background-color: #f7f7f7;
        border-color: #ccc;
    }

    .social-btn.google i {
        color: #333;
    }

    .social-btn.zalo {
        background-color: #018fe2;
        color: #fff;
        border: none;
    }

    .social-btn.zalo:hover {
        background-color: #0075c4;
    }

    /* Optional: icon as circle (Google-style) */
    .social-btn i::before {
        display: inline-block;
        width: 20px;
        height: 20px;
        text-align: center;
    }
</style>

<div class="form-wrapper">
    <h2>Đăng nhập</h2>
    <form method="POST" action="<?php echo e(route('login')); ?>">
        <?php echo csrf_field(); ?>

        <div class="form-group">
            <label for="email" class="form-label">Email:</label>
            <input id="email" placeholder="Nhập email của bạn" type="email" class="form-control" name="email" value="<?php echo e(old('email')); ?>" autofocus>
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
            <label for="password" class="form-label">Mật khẩu:</label>
            <input id="password" placeholder="Nhập mật khẩu của bạn" type="password" class="form-control" name="password">
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

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
            <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
        </div>

        <button type="submit" class="btn-submit">Đăng nhập</button>

        <div class="form-footer">
            <a href="<?php echo e(route('password.request')); ?>">Quên mật khẩu?</a><br>
            Chưa có tài khoản? <a href="<?php echo e(route('register')); ?>">Đăng ký</a>
        </div>

        <div class="social-login">
            <a href="<?php echo e(url('/auth/google')); ?>" class="social-btn google">
                <i class="fab fa-google"></i> Đăng nhập bằng Google
            </a>
            <a href="<?php echo e(url('/auth/zalo')); ?>" class="social-btn zalo">
                <i class="fas fa-comment-dots"></i> Đăng nhập bằng Zalo
            </a>
        </div>
    </form>
</div>

<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\booking-management\resources\views/auth/login.blade.php ENDPATH**/ ?>