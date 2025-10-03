
<?php $__env->startSection("content"); ?>


<h1 class="page-title">
    <i class="fas fa-tachometer-alt"></i>
    Dashboard Tổng quan
</h1>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Tổng đặt phòng hôm nay</h3>
        <div class="number">24</div>
        <div class="trend">
            <i class="fas fa-arrow-up"></i> +12% so với hôm qua
        </div>
    </div>
    <div class="stat-card">
        <h3>Tỷ lệ lấp đầy</h3>
        <div class="number">85%</div>
        <div class="trend">
            <i class="fas fa-arrow-up"></i> +5% so với tuần trước
        </div>
    </div>
    <div class="stat-card">
        <h3>Doanh thu tháng này</h3>
        <div class="number">450M</div>
        <div class="trend">
            <i class="fas fa-arrow-up"></i> +18% so với tháng trước
        </div>
    </div>
    <div class="stat-card">
        <h3>Khách hàng mới</h3>
        <div class="number">156</div>
        <div class="trend">
            <i class="fas fa-arrow-up"></i> +25% so với tuần trước
        </div>
    </div>
</div>

<div class="content-section">
    <h2 class="section-title">
        <i class="fas fa-calendar-alt"></i>
        Đặt phòng gần đây
    </h2>
    <p>Danh sách các đặt phòng mới nhất sẽ được hiển thị tại đây...</p>
</div>

<div class="content-section">
    <h2 class="section-title">
        <i class="fas fa-chart-bar"></i>
        Thống kê nhanh
    </h2>
    <p>Biểu đồ và báo cáo thống kê sẽ được hiển thị tại đây...</p>
</div>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>