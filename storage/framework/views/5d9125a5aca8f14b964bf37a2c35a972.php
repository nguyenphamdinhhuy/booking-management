<?php $__env->startSection('content'); ?>
    <div class="container py-4">

        
        <div class="mb-4">
            <h4 class="mb-3">Các loại dịch vụ</h4>
            <div class="d-flex flex-wrap gap-2">
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="" class="explore-item">
                        <?php echo e($i->name); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>  

        
        <h4 class="mb-4">
            Dịch vụ thuộc danh mục:
            <span class="text-primary fw-semibold">
                <?php echo e($categories->firstWhere('id', $currentCategoryId)?->name); ?>

            </span>
        </h4>

        
        <div class="row">
            <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0 rounded-3">
                        <img src="<?php echo e(asset($service->image)); ?>" class="card-img-top rounded-top-3"
                            style="height: 200px; object-fit: cover;" alt="<?php echo e($service->name); ?>">
                        <div class="card-body">
                            <h5 class="card-title text-truncate"><?php echo e($service->name); ?></h5>
                            <p class="text-muted small mb-2">
                                <?php echo e(Str::limit($service->description, 80)); ?>

                            </p>
                            <p class="fw-bold mb-3">
                                <?php echo e(number_format($service->price, 0, ',', '.')); ?> VNĐ / <?php echo e($service->unit); ?>

                            </p>
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12">
                    <p class="text-muted fst-italic">Không có dịch vụ nào trong danh mục này.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app\resources\views/user/Service/serviceDetails.blade.php ENDPATH**/ ?>