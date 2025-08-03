<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Admin - Booking</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('admin/css/main.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/profile.css')); ?>">

</head>

<body>
    <?php echo $__env->make('admin/layouts/header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('admin/layouts/sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="main-content">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <script src="<?php echo e(asset('admin/js/script.js')); ?>"></script>
</body>

</html><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app\resources\views/admin/layouts/master.blade.php ENDPATH**/ ?>