<?php $__env->startSection('title', $service->name); ?>



<?php $__env->startSection('content'); ?>
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <!-- <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?php echo e(route('index')); ?>"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <i class="fas fa-home mr-2"></i>
                        Trang chủ
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="<?php echo e(route('user.services.index')); ?>"
                            class="text-sm font-medium text-gray-700 hover:text-blue-600">
                            Dịch vụ
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500"><?php echo e($service->name); ?></span>
                    </div>
                </li>
            </ol>
        </nav> -->

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Service Image -->
                <div class="service-detail-image">
                    <?php if($service->image): ?>
                        <img src="<?php echo e(asset($service->image)); ?>" alt="<?php echo e($service->name); ?>" class="w-full h-96 object-cover">
                    <?php else: ?>
                        <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-image text-6xl text-gray-400"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Service Details -->
                <div class="service-detail-content">
                    <div class="flex items-center justify-between mb-4">
                        <h1 class="text-3xl font-bold text-gray-800"><?php echo e($service->name); ?></h1>
                        <span class="service-status">
                            Có sẵn
                        </span>
                    </div>

                    <div class="flex items-center mb-6">
                        <span class="service-detail-price"><?php echo e(number_format($service->price)); ?> VNĐ</span>
                        <span class="text-gray-500 ml-2">/ <?php echo e($service->unit); ?></span>
                    </div>

                    <?php if($service->description): ?>
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Mô tả dịch vụ</h3>
                            <p class="text-gray-600 leading-relaxed"><?php echo e($service->description); ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Service Category -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Danh mục</h3>
                        <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                            <?php echo e($service->category->name); ?>

                        </span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-4">
                        <button class="action-btn action-btn-primary contact-btn">
                            <i class="fas fa-phone mr-2"></i>Liên hệ đặt dịch vụ
                        </button>
                        <button class="action-btn action-btn-secondary favorite-btn">
                            <i class="fas fa-heart mr-2"></i>Yêu thích
                        </button>
                        <button class="action-btn action-btn-secondary share-btn">
                            <i class="fas fa-share mr-2"></i>Chia sẻ
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Quick Info -->
                <div class="sidebar-card">
                    <h3>Thông tin nhanh</h3>
                    <div class="space-y-3">
                        <div class="info-item">
                            <i class="fas fa-tag text-blue-600"></i>
                            <span class="text-gray-600">Danh mục: <?php echo e($service->category->name); ?></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-money-bill text-green-600"></i>
                            <span class="text-gray-600">Giá: <?php echo e(number_format($service->price)); ?> VNĐ</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-ruler text-purple-600"></i>
                            <span class="text-gray-600">Đơn vị: <?php echo e($service->unit); ?></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-check-circle text-green-600"></i>
                            <span class="text-gray-600">Trạng thái: Có sẵn</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <!-- <div class="sidebar-card">
                    <h3>Liên hệ</h3>
                    <div class="space-y-3">
                        <div class="info-item">
                            <i class="fas fa-phone text-blue-600"></i>
                            <span class="text-gray-600">0123 456 789</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-envelope text-blue-600"></i>
                            <span class="text-gray-600">info@hotel.com</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-map-marker-alt text-blue-600"></i>
                            <span class="text-gray-600">123 Đường ABC, Quận XYZ</span>
                        </div>
                    </div>
                </div> -->

                <!-- Related Services -->
                <?php if($relatedServices->count() > 0): ?>
                    <div class="sidebar-card">
                        <h3>Dịch vụ liên quan</h3>
                        <div class="space-y-4">
                            <?php $__currentLoopData = $relatedServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedService): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="related-service">
                                    <?php if($relatedService->image): ?>
                                        <img src="<?php echo e(asset($relatedService->image)); ?>" alt="<?php echo e($relatedService->name); ?>"
                                            class="w-16 h-16 object-cover rounded-lg">
                                    <?php else: ?>
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="related-service-info">
                                        <h4 class="related-service-name"><?php echo e($relatedService->name); ?></h4>
                                        <p class="related-service-price"><?php echo e(number_format($relatedService->price)); ?> VNĐ</p>
                                    </div>
                                    <a href="<?php echo e(route('user.services.show', $relatedService->s_id)); ?>"
                                        class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php $__env->stopSection(); ?>

<style>
    /* Service Detail Page Styles */
    
    /* Service Detail Image */
    .service-detail-image {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;
    }

    .service-detail-image img {
        width: 100%;
        height: 400px;
        object-fit: cover;
    }

    /* Service Detail Content */
    .service-detail-content {
        background: white;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    /* Service Status */
    .service-status {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Service Detail Price */
    .service-detail-price {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        font-size: 24px;
        font-weight: bold;
        display: inline-block;
        margin-bottom: 24px;
    }

    /* Action Buttons */
    .action-btn {
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
        font-size: 16px;
    }

    .action-btn-primary {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    .action-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(59, 130, 246, 0.3);
    }

    .action-btn-secondary {
        background: white;
        color: #374151;
        border: 2px solid #e5e7eb;
    }

    .action-btn-secondary:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        transform: translateY(-2px);
    }

    /* Sidebar */
    .sidebar-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .sidebar-card h3 {
        color: #1f2937;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 16px;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 8px;
    }

    .info-item {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        padding: 8px 0;
    }

    .info-item i {
        width: 20px;
        margin-right: 12px;
        font-size: 16px;
    }

    /* Related Services */
    .related-service {
        display: flex;
        align-items: center;
        padding: 12px;
        border-radius: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .related-service:hover {
        background: #f9fafb;
        transform: translateX(4px);
    }

    .related-service img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 12px;
    }

    .related-service-info {
        flex: 1;
    }

    .related-service-name {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 4px;
        font-size: 14px;
    }

    .related-service-price {
        color: #ef4444;
        font-weight: 600;
        font-size: 14px;
    }

    /* Breadcrumb */
    .breadcrumb {
        display: flex;
        align-items: center;
        margin-bottom: 32px;
        font-size: 14px;
    }

    .breadcrumb-item {
        color: #6b7280;
        transition: color 0.3s ease;
    }

    .breadcrumb-item:hover {
        color: #3b82f6;
    }

    .breadcrumb-separator {
        margin: 0 8px;
        color: #d1d5db;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .service-detail-content {
            padding: 20px;
        }
        
        .action-btn {
            width: 100%;
            margin-bottom: 12px;
        }
        
        .service-detail-price {
            font-size: 20px;
            padding: 10px 20px;
        }
    }
</style>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\booking-management\resources\views/user/services/show.blade.php ENDPATH**/ ?>