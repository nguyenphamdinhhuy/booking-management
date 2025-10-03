

<?php $__env->startSection('content'); ?>


<?php if(session('success')): ?>
<div class="custom-alert custom-alert-success" id="alert-success">
    <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

</div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="custom-alert custom-alert-error" id="alert-error">
    <i class="fas fa-times-circle"></i> <?php echo e(session('error')); ?>

</div>
<?php endif; ?>

<div class="vouchers-page">
    <!-- Header -->
    <div class="vouchers-header">
        <div class="header-content">
            <div class="header-text">
                <h1>Ưu đãi & Khuyến mãi</h1>
                <p>Khám phá những ưu đãi tuyệt vời để tiết kiệm cho chuyến đi của bạn</p>
            </div>
            <div class="header-stats">
                <div class="stat-item">
                    <div class="stat-number"><?php echo e($all_vouchers->count()); ?></div>
                    <div class="stat-label">Ưu đãi khả dụng</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo e($featured_vouchers->count()); ?></div>
                    <div class="stat-label">Ưu đãi nổi bật</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="vouchers-filters">
        <div class="filters-container">
            <div class="search-box">
                <form method="GET" action="<?php echo e(route('user.vouchers')); ?>">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" placeholder="Tìm kiếm mã giảm giá..." value="<?php echo e(request('search')); ?>">
                        <button type="submit">Tìm kiếm</button>
                    </div>
                </form>
            </div>

            <div class="filter-tabs">
                <a href="<?php echo e(route('user.vouchers')); ?>" class="filter-tab <?php echo e(!request('type') ? 'active' : ''); ?>">
                    <i class="fas fa-star"></i> Tất cả
                </a>
                <a href="<?php echo e(route('user.vouchers', ['type' => 'hotel'])); ?>" class="filter-tab <?php echo e(request('type') == 'hotel' ? 'active' : ''); ?>">
                    <i class="fas fa-bed"></i> Khách sạn
                </a>
                <a href="<?php echo e(route('user.vouchers', ['type' => 'service'])); ?>" class="filter-tab <?php echo e(request('type') == 'service' ? 'active' : ''); ?>">
                    <i class="fas fa-concierge-bell"></i> Dịch vụ
                </a>
            </div>
        </div>
    </div>

    <!-- Featured Vouchers -->
    <?php if($featured_vouchers->count() > 0): ?>
    <div class="featured-section">
        <div class="section-header">
            <h2>Ưu đãi nổi bật</h2>
            <p>Những ưu đãi có giá trị cao nhất dành cho bạn</p>
        </div>

        <div class="featured-vouchers">
            <?php $__currentLoopData = $featured_vouchers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $voucher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="featured-voucher">
                <div class="voucher-badge">
                    <i class="fas fa-crown"></i>
                    <span>Nổi bật</span>
                </div>

                <div class="voucher-discount">
                    <div class="discount-percent"><?php echo e($voucher->discount_percent); ?>%</div>
                    <div class="discount-label">GIẢM GIÁ</div>
                </div>

                <div class="voucher-content">
                    <div class="voucher-code">
                        <span class="code-label">Mã:</span>
                        <span class="code-value"><?php echo e($voucher->v_code); ?></span>
                    </div>

                    <div class="voucher-description">
                        <?php echo e($voucher->description ?: 'Giảm giá ' . $voucher->discount_percent . '% cho đơn hàng của bạn'); ?>

                    </div>

                    <div class="voucher-validity">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Có hiệu lực đến: <?php echo e(\Carbon\Carbon::parse($voucher->end_date)->format('d/m/Y')); ?></span>
                    </div>

                    <div class="voucher-actions">
                        <button class="copy-btn" onclick="copyVoucherCode('<?php echo e($voucher->v_code); ?>', <?php echo e($voucher->v_id); ?>)">
                            <i class="fas fa-copy"></i>
                            <span>Sao chép mã</span>
                        </button>
                        <a href="<?php echo e(route('all_rooms')); ?>" class="use-btn">
                            <i class="fas fa-arrow-right"></i>
                            <span>Sử dụng ngay</span>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- All Vouchers -->
    <div class="all-vouchers-section">
        <div class="section-header">
            <h2>Tất cả ưu đãi</h2>
            <p><?php echo e($all_vouchers->count()); ?> ưu đãi khả dụng</p>
        </div>

        <?php if($all_vouchers->count() > 0): ?>
        <div class="vouchers-grid">
            <?php $__currentLoopData = $all_vouchers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $voucher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="voucher-card">
                <div class="voucher-card-header">
                    <div class="discount-badge">
                        <span class="discount-value"><?php echo e($voucher->discount_percent); ?>%</span>
                        <span class="discount-text">OFF</span>
                    </div>

                    <?php
                    $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($voucher->end_date));
                    ?>

                    <?php if($daysLeft <= 7): ?>
                        <div class="urgency-badge">
                        <i class="fas fa-clock"></i>
                        <span><?php echo e($daysLeft); ?> ngày</span>
                </div>
                <?php endif; ?>
            </div>

            <div class="voucher-card-body">
                <div class="voucher-title">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Mã: <?php echo e($voucher->v_code); ?></span>
                </div>

                <div class="voucher-desc">
                    <?php echo e($voucher->description ?: 'Giảm ' . $voucher->discount_percent . '% cho đơn hàng của bạn'); ?>

                </div>

                <div class="voucher-details">
                    <div class="detail-item">
                        <i class="fas fa-calendar-check"></i>
                        <span>Từ: <?php echo e(\Carbon\Carbon::parse($voucher->start_date)->format('d/m/Y')); ?></span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-calendar-times"></i>
                        <span>Đến: <?php echo e(\Carbon\Carbon::parse($voucher->end_date)->format('d/m/Y')); ?></span>
                    </div>
                </div>
            </div>

            <div class="voucher-card-footer">
                <button class="copy-button" onclick="copyVoucherCode('<?php echo e($voucher->v_code); ?>', <?php echo e($voucher->v_id); ?>)">
                    <i class="fas fa-copy"></i>
                    <span>Sao chép</span>
                </button>
                <a href="<?php echo e(route('all_rooms')); ?>" class="use-button">
                    <i class="fas fa-arrow-right"></i>
                    <span>Sử dụng</span>
                </a>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fas fa-ticket-alt"></i>
        </div>
        <h3>Không có ưu đãi nào</h3>
        <p>Hiện tại không có ưu đãi nào phù hợp với tìm kiếm của bạn.</p>
        <a href="<?php echo e(route('user.vouchers')); ?>" class="btn-primary">Xem tất cả ưu đãi</a>
    </div>
    <?php endif; ?>
</div>

<!-- How to use section -->
<div class="how-to-use-section">
    <div class="section-header">
        <h2>Cách sử dụng mã giảm giá</h2>
    </div>

    <div class="steps-container">
        <div class="step-item">
            <div class="step-number">1</div>
            <div class="step-content">
                <h4>Chọn ưu đãi</h4>
                <p>Chọn mã giảm giá phù hợp và sao chép mã</p>
            </div>
        </div>

        <div class="step-item">
            <div class="step-number">2</div>
            <div class="step-content">
                <h4>Đặt phòng</h4>
                <p>Chọn phòng và tiến hành đặt phòng bình thường</p>
            </div>
        </div>

        <div class="step-item">
            <div class="step-number">3</div>
            <div class="step-content">
                <h4>Nhập mã</h4>
                <p>Nhập mã giảm giá khi thanh toán để được giảm giá</p>
            </div>
        </div>

        <div class="step-item">
            <div class="step-number">4</div>
            <div class="step-content">
                <h4>Tiết kiệm</h4>
                <p>Hoàn tất thanh toán và tận hưởng ưu đãi</p>
            </div>
        </div>
    </div>
</div>
</div>

<style>
    .vouchers-page {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Header */
    .vouchers-header {
        background: linear-gradient(135deg, #003580 0%, #0057b8 100%);
        border-radius: 16px;
        padding: 40px;
        margin-bottom: 32px;
        color: white;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 40px;
    }

    .header-text h1 {
        font-size: 36px;
        font-weight: 700;
        margin: 0 0 12px 0;
    }

    .header-text p {
        font-size: 18px;
        opacity: 0.9;
        margin: 0;
    }

    .header-stats {
        display: flex;
        gap: 32px;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 32px;
        font-weight: 700;
        line-height: 1;
    }

    .stat-label {
        font-size: 14px;
        opacity: 0.8;
        margin-top: 4px;
    }

    /* Filters */
    .vouchers-filters {
        background: white;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 32px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .filters-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 24px;
    }

    .search-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        background: #f8f9fa;
        border-radius: 12px;
        padding: 0 16px;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .search-input-wrapper:focus-within {
        border-color: #003580;
        box-shadow: 0 0 0 3px rgba(0, 53, 128, 0.1);
    }

    .search-input-wrapper i {
        color: #666;
        margin-right: 12px;
    }

    .search-input-wrapper input {
        border: none;
        background: none;
        padding: 14px 0;
        font-size: 16px;
        width: 300px;
        outline: none;
    }

    .search-input-wrapper button {
        background: #003580;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        margin-left: 12px;
        transition: all 0.3s ease;
    }

    .search-input-wrapper button:hover {
        background: #002147;
    }

    .filter-tabs {
        display: flex;
        gap: 8px;
    }

    .filter-tab {
        padding: 12px 20px;
        border-radius: 12px;
        text-decoration: none;
        color: #666;
        background: #f8f9fa;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
    }

    .filter-tab:hover {
        background: #e3f2fd;
        color: #003580;
    }

    .filter-tab.active {
        background: #003580;
        color: white;
    }

    /* Section Headers */
    .section-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .section-header h2 {
        font-size: 28px;
        font-weight: 700;
        color: #333;
        margin: 0 0 8px 0;
    }

    .section-header p {
        color: #666;
        font-size: 16px;
        margin: 0;
    }

    /* Featured Vouchers */
    .featured-section {
        margin-bottom: 48px;
    }

    .featured-vouchers {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 24px;
    }

    .featured-voucher {
        background: linear-gradient(135deg, #fff 0%, #f8fafe 100%);
        border-radius: 20px;
        padding: 24px;
        border: 2px solid #e3f2fd;
        position: relative;
        box-shadow: 0 8px 30px rgba(0, 53, 128, 0.1);
        transition: all 0.3s ease;
    }

    .featured-voucher:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 53, 128, 0.15);
    }

    .voucher-badge {
        position: absolute;
        top: -8px;
        right: 20px;
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .voucher-discount {
        text-align: center;
        margin-bottom: 20px;
    }

    .discount-percent {
        font-size: 48px;
        font-weight: 700;
        color: #003580;
        line-height: 1;
    }

    .discount-label {
        font-size: 14px;
        font-weight: 600;
        color: #666;
        letter-spacing: 1px;
    }

    .voucher-code {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        padding: 12px;
        background: #f0f6ff;
        border-radius: 8px;
        border-left: 4px solid #003580;
    }

    .code-label {
        font-size: 14px;
        color: #666;
    }

    .code-value {
        font-family: 'Courier New', monospace;
        font-size: 16px;
        font-weight: 700;
        color: #003580;
    }

    .voucher-description {
        font-size: 16px;
        color: #333;
        margin-bottom: 16px;
        line-height: 1.5;
    }

    .voucher-validity {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #666;
        font-size: 14px;
        margin-bottom: 20px;
    }

    .voucher-actions {
        display: flex;
        gap: 12px;
    }

    .copy-btn,
    .use-btn {
        flex: 1;
        padding: 12px;
        border-radius: 12px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
    }

    .copy-btn {
        background: #f8f9fa;
        color: #333;
        border: 2px solid #e0e0e0;
    }

    .copy-btn:hover {
        background: #e9ecef;
        border-color: #003580;
    }

    .use-btn {
        background: #003580;
        color: white;
    }

    .use-btn:hover {
        background: #002147;
        transform: translateY(-1px);
    }

    /* Vouchers Grid */
    .all-vouchers-section {
        margin-bottom: 48px;
    }

    .vouchers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
    }

    .voucher-card {
        background: white;
        border-radius: 16px;
        border: 2px solid #e0e0e0;
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
    }

    .voucher-card:hover {
        border-color: #003580;
        box-shadow: 0 8px 25px rgba(0, 53, 128, 0.1);
        transform: translateY(-2px);
    }

    .voucher-card-header {
        background: linear-gradient(135deg, #003580 0%, #0057b8 100%);
        padding: 16px 20px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .discount-badge {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .discount-value {
        font-size: 24px;
        font-weight: 700;
    }

    .discount-text {
        font-size: 12px;
        font-weight: 600;
        opacity: 0.9;
    }

    .urgency-badge {
        background: rgba(255, 107, 53, 0.2);
        padding: 4px 8px;
        border-radius: 8px;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 4px;
        border: 1px solid rgba(255, 107, 53, 0.3);
    }

    .voucher-card-body {
        padding: 20px;
    }

    .voucher-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        font-weight: 600;
        color: #333;
    }

    .voucher-desc {
        color: #666;
        line-height: 1.5;
        margin-bottom: 16px;
    }

    .voucher-details {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #666;
    }

    .detail-item i {
        width: 16px;
        color: #003580;
    }

    .voucher-card-footer {
        padding: 16px 20px;
        border-top: 1px solid #e0e0e0;
        display: flex;
        gap: 12px;
    }

    .copy-button,
    .use-button {
        flex: 1;
        padding: 10px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
        font-size: 14px;
    }

    .copy-button {
        background: #f8f9fa;
        color: #333;
        border: 1px solid #e0e0e0;
    }

    .copy-button:hover {
        background: #e9ecef;
        border-color: #003580;
    }

    .use-button {
        background: #003580;
        color: white;
    }

    .use-button:hover {
        background: #002147;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }

    .empty-icon {
        font-size: 64px;
        color: #ccc;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 24px;
        margin-bottom: 12px;
        color: #333;
    }

    .empty-state p {
        font-size: 16px;
        margin-bottom: 24px;
    }

    .btn-primary {
        background: #003580;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: #002147;
    }

    /* How to use section */
    .how-to-use-section {
        background: #f8f9fa;
        border-radius: 16px;
        padding: 40px;
    }

    .steps-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 32px;
    }

    .step-item {
        display: flex;
        gap: 16px;
        align-items: flex-start;
    }

    .step-number {
        width: 40px;
        height: 40px;
        background: #003580;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        flex-shrink: 0;
    }

    .step-content h4 {
        margin: 0 0 8px 0;
        font-size: 18px;
        color: #333;
    }

    .step-content p {
        margin: 0;
        color: #666;
        line-height: 1.5;
    }

    /* Alerts */
    .custom-alert {
        padding: 14px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        font-size: 16px;
        font-weight: 500;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 10px;
        animation: slideInDown 0.5s ease;
    }

    .custom-alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .custom-alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ===== RESPONSIVE BREAKPOINTS ===== */
    @media (max-width: 1024px) {
        .header-content {
            flex-direction: column;
            text-align: center;
        }

        .filters-container {
            flex-direction: column;
            align-items: flex-start;
        }

        .search-input-wrapper input {
            width: 100%;
        }

        .search-box {
            width: 100%;
        }

        .filter-tabs {
            flex-wrap: wrap;
            justify-content: center;
        }

        .featured-vouchers,
        .vouchers-grid {
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        }

        .steps-container {
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .vouchers-header {
            padding: 24px;
        }

        .header-text h1 {
            font-size: 28px;
        }

        .header-text p {
            font-size: 16px;
        }

        .stat-number {
            font-size: 24px;
        }

        .stat-label {
            font-size: 13px;
        }

        .section-header h2 {
            font-size: 22px;
        }

        .section-header p {
            font-size: 14px;
        }

        .voucher-card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .voucher-card-footer {
            flex-direction: column;
        }

        .copy-button,
        .use-button {
            width: 100%;
        }

        .voucher-actions {
            flex-direction: column;
        }

        .copy-btn,
        .use-btn {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .vouchers-page {
            padding: 16px;
        }

        .search-input-wrapper input {
            font-size: 14px;
        }

        .search-input-wrapper button {
            padding: 10px 16px;
            font-size: 14px;
        }

        .filter-tab {
            padding: 10px 14px;
            font-size: 14px;
        }

        .featured-voucher {
            padding: 16px;
        }

        .discount-percent {
            font-size: 36px;
        }

        .voucher-description {
            font-size: 14px;
        }

        .voucher-validity {
            font-size: 13px;
        }

        .step-content h4 {
            font-size: 16px;
        }

        .step-content p {
            font-size: 14px;
        }

        .custom-alert {
            font-size: 14px;
            padding: 12px 16px;
        }

        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 16px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            min-width: 300px;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
        }

        .toast.show {
            opacity: 1;
            transform: translateX(0);
        }

        .toast.error {
            background: #dc3545;
        }

        .toast.warning {
            background: #ffc107;
            color: #333;
        }

        .toast i {
            font-size: 18px;
        }

        .toast-close {
            background: none;
            border: none;
            color: inherit;
            font-size: 18px;
            cursor: pointer;
            margin-left: auto;
            opacity: 0.8;
            transition: opacity 0.2s ease;
        }

        .toast-close:hover {
            opacity: 1;
        }

        /* Button loading state */
        .copy-btn.loading,
        .copy-button.loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }

        .copy-btn.loading span,
        .copy-button.loading span {
            opacity: 0;
        }

        .copy-btn.loading::after,
        .copy-button.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 16px;
            height: 16px;
            margin: -8px 0 0 -8px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Success state for buttons */
        .copy-btn.success,
        .copy-button.success {
            background: #28a745 !important;
            border-color: #28a745 !important;
        }

        .copy-btn.success i,
        .copy-button.success i {
            animation: checkmark 0.5s ease;
        }

        @keyframes checkmark {
            0% {
                transform: scale(0);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }
    }
</style>

<script>
    // CSRF token for Laravel
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo e(csrf_token()); ?>';

    /**
     * Copy voucher code function
     * Tích hợp với controller copyVoucherCode
     */
    async function copyVoucherCode(voucherCode, voucherId) {
        const button = event.target.closest('.copy-btn, .copy-button');

        if (!button) return;

        // Prevent double clicks
        if (button.classList.contains('loading')) return;

        // Add loading state
        button.classList.add('loading');

        try {
            // Call the API to validate voucher
            const response = await fetch('<?php echo e(route("vouchers.copy")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    voucher_id: voucherId
                })
            });

            const data = await response.json();

            if (data.success) {
                // Copy to clipboard
                const copySuccess = await copyToClipboard(data.code);

                if (copySuccess) {
                    // Show success state
                    showSuccessButton(button);
                    showToast('success', data.message, 'fas fa-check-circle');
                } else {
                    // Fallback: show code in prompt for manual copy
                    showCodeInPrompt(data.code);
                    showToast('warning', `Mã voucher: ${data.code} (Đã hiển thị để bạn sao chép thủ công)`, 'fas fa-exclamation-triangle');
                }
            } else {
                showToast('error', data.message || 'Có lỗi xảy ra khi sao chép mã voucher', 'fas fa-times-circle');
            }

        } catch (error) {
            console.error('Error copying voucher:', error);

            // Fallback: try to copy the code directly
            const copySuccess = await copyToClipboard(voucherCode);
            if (copySuccess) {
                showSuccessButton(button);
                showToast('success', `Đã sao chép mã: ${voucherCode}`, 'fas fa-check-circle');
            } else {
                showCodeInPrompt(voucherCode);
                showToast('error', 'Không thể kết nối đến server. Vui lòng thử lại!', 'fas fa-wifi');
            }
        } finally {
            // Remove loading state
            setTimeout(() => {
                button.classList.remove('loading');
            }, 500);
        }
    }

    /**
     * Copy text to clipboard using modern API with fallback
     */
    async function copyToClipboard(text) {
        try {
            // Modern browsers
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(text);
                return true;
            } else {
                // Fallback for older browsers
                return fallbackCopyToClipboard(text);
            }
        } catch (error) {
            console.error('Clipboard API failed:', error);
            return fallbackCopyToClipboard(text);
        }
    }

    /**
     * Fallback copy method for older browsers
     */
    function fallbackCopyToClipboard(text) {
        try {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            const successful = document.execCommand('copy');
            document.body.removeChild(textArea);

            return successful;
        } catch (error) {
            console.error('Fallback copy failed:', error);
            return false;
        }
    }

    /**
     * Show code in prompt as last resort
     */
    function showCodeInPrompt(code) {
        const message = `Mã voucher của bạn là: ${code}\n\nVui lòng sao chép mã này thủ công.`;

        // For mobile devices, try to select the text
        if (/Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            const confirmed = confirm(message + '\n\nNhấn OK để đóng.');
        } else {
            prompt('Sao chép mã voucher này:', code);
        }
    }

    /**
     * Show success state on button
     */
    function showSuccessButton(button) {
        const originalHTML = button.innerHTML;

        button.classList.add('success');
        button.innerHTML = '<i class="fas fa-check"></i><span>Đã sao chép!</span>';

        setTimeout(() => {
            button.classList.remove('success');
            button.innerHTML = originalHTML;
        }, 2000);
    }

    /**
     * Show toast notification
     */
    function showToast(type, message, iconClass) {
        // Remove existing toasts
        const existingToasts = document.querySelectorAll('.toast');
        existingToasts.forEach(toast => toast.remove());

        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
        <i class="${iconClass}"></i>
        <span>${message}</span>
        <button class="toast-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;

        // Add to page
        document.body.appendChild(toast);

        // Show toast
        setTimeout(() => toast.classList.add('show'), 100);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }
        }, 5000);
    }

    // Handle auto-hide alerts
    document.addEventListener('DOMContentLoaded', function() {
        // Auto hide success/error alerts after 5 seconds
        const alerts = document.querySelectorAll('.custom-alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/user/vouchers.blade.php ENDPATH**/ ?>