<?php $__env->startSection('title', $service->name); ?>

<?php $__env->startSection('content'); ?>
<div class="booking-container">
    <!-- Breadcrumb -->
    <nav class="booking-breadcrumb">
        <div class="breadcrumb-list">
            <a href="<?php echo e(route('home')); ?>" class="breadcrumb-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
                </svg>
                Trang chủ
            </a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?php echo e(route('user.services.index')); ?>" class="breadcrumb-link">Dịch vụ</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current"><?php echo e($service->name); ?></span>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="booking-main">
        <!-- Left Column -->
        <div class="booking-content">
            <!-- Service Header -->
            <div class="service-header">
                <div class="service-title-section">
                    <h1 class="service-title"><?php echo e($service->name); ?></h1>
                    <div class="service-meta">
                        <span class="service-category"><?php echo e($service->category->name); ?></span>
                        <span class="service-status-badge">Có sẵn</span>
                    </div>
                </div>
            </div>

            <!-- Service Images -->
            <div class="service-gallery">
                <?php if($service->image): ?>
                <div class="main-image">
                    <img src="<?php echo e(asset($service->image)); ?>" alt="<?php echo e($service->name); ?>">
                </div>
                <?php else: ?>
                <div class="main-image placeholder">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z" />
                    </svg>
                </div>
                <?php endif; ?>
            </div>

            <!-- Service Description -->
            <?php if($service->description): ?>
            <div class="service-description">
                <h2 class="section-title">Mô tả dịch vụ</h2>
                <p class="description-text"><?php echo e($service->description); ?></p>
            </div>
            <?php endif; ?>

            <!-- Service Details -->
            <div class="service-details">
                <h2 class="section-title">Chi tiết dịch vụ</h2>
                <div class="details-grid">
                    <div class="detail-item">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
                        </svg>
                        <span>Danh mục: <?php echo e($service->category->name); ?></span>
                    </div>
                    <div class="detail-item">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                        </svg>
                        <span>Đơn vị: <?php echo e($service->unit); ?></span>
                    </div>
                    <div class="detail-item">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
                        </svg>
                        <span>Trạng thái: Có sẵn</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="booking-sidebar">
            <!-- Booking Card -->
            <div class="booking-card">
                <div class="price-section">
                    <span class="price-amount"><?php echo e(number_format($service->price)); ?></span>
                    <span class="price-currency">VNĐ</span>
                    <span class="price-unit">/ <?php echo e($service->unit); ?></span>
                </div>

                <div class="booking-actions">
                    <button class="btn-primary contact-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" />
                        </svg>
                        Liên hệ đặt dịch vụ
                    </button>


                </div>

                <!-- Service Info -->
                <div class="service-info">
                    <div class="info-row">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
                        </svg>
                        <span>Xác nhận tức thì</span>
                    </div>
                    <div class="info-row">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                        </svg>
                        <span>Hỗ trợ 24/7</span>
                    </div>
                </div>
            </div>

            <!-- Related Services -->
            <?php if($relatedServices->count() > 0): ?>
            <div class="related-services">
                <h3 class="related-title">Dịch vụ tương tự</h3>
                <div class="related-list">
                    <?php $__currentLoopData = $relatedServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedService): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('user.services.show', $relatedService->s_id)); ?>" class="related-item">
                        <div class="related-image">
                            <?php if($relatedService->image): ?>
                            <img src="<?php echo e(asset($relatedService->image)); ?>" alt="<?php echo e($relatedService->name); ?>">
                            <?php else: ?>
                            <div class="image-placeholder">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z" />
                                </svg>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="related-info">
                            <div class="related-name"><?php echo e($relatedService->name); ?></div>
                            <div class="related-price"><?php echo e(number_format($relatedService->price)); ?> VNĐ</div>
                        </div>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    /* === GLOBAL RESETS === */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f7f9fb;
        color: #2c3e50;
        margin: 0;
        padding: 0;
    }

    a {
        text-decoration: none;
        color: #2980b9;
        transition: 0.3s;
    }

    a:hover {
        color: #1abc9c;
    }

    /* === BREADCRUMB === */
    .booking-breadcrumb {
        padding: 20px 0;
        background-color: #ecf0f1;
        font-size: 14px;
        border-bottom: 1px solid #dcdcdc;
    }

    .breadcrumb-list {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        gap: 6px;
        color: #7f8c8d;
    }

    .breadcrumb-link svg {
        margin-right: 4px;
        vertical-align: middle;
    }

    .breadcrumb-current {
        color: #2c3e50;
        font-weight: bold;
    }

    /* === MAIN LAYOUT === */
    .booking-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .booking-main {
        display: flex;
        flex-direction: row;
        gap: 30px;
    }

    .booking-content {
        flex: 2;
    }

    .booking-sidebar {
        flex: 1;
        position: sticky;
        top: 20px;
        height: fit-content;
    }

    /* === SERVICE HEADER === */
    .service-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .service-meta {
        display: flex;
        gap: 15px;
        color: #7f8c8d;
        font-size: 14px;
    }

    .service-status-badge {
        background-color: #2ecc71;
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
    }

    /* === IMAGE GALLERY === */
    .service-gallery {
        margin: 20px 0;
    }

    .main-image {
        width: 100%;
        height: 350px;
        background-color: #ecf0f1;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        overflow: hidden;
    }

    .main-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* === DESCRIPTION & DETAILS === */
    .section-title {
        font-size: 20px;
        margin: 20px 0 10px;
        color: #34495e;
    }

    .description-text {
        line-height: 1.6;
        color: #2c3e50;
    }

    .details-grid {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        color: #2c3e50;
    }

    /* === SIDEBAR BOOKING CARD === */
    .booking-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 20px;
    }

    .price-section {
        display: flex;
        align-items: baseline;
        gap: 6px;
        margin-bottom: 15px;
        font-size: 22px;
        color: #e74c3c;
        font-weight: bold;
    }

    .price-unit {
        font-size: 14px;
        color: #7f8c8d;
    }

    .btn-primary {
        width: 100%;
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        border: none;
        padding: 12px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1abc9c, #16a085);
    }

    .service-info {
        margin-top: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        font-size: 14px;
        color: #2c3e50;
    }

    .info-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* === RELATED SERVICES === */
    .related-services {
        margin-top: 40px;
    }

    .related-title {
        font-size: 18px;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .related-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .related-item {
        display: flex;
        gap: 15px;
        background-color: #ffffff;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s;
    }

    .related-item:hover {
        transform: translateY(-3px);
    }

    .related-image img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 6px;
    }

    .related-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
        font-size: 14px;
        color: #2c3e50;
    }

    .related-price {
        color: #e74c3c;
        font-weight: bold;
        margin-top: 5px;
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app\resources\views/user/services/show.blade.php ENDPATH**/ ?>