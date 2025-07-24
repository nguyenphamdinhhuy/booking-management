<?php $__env->startSection('title', 'System Statistics'); ?>

<?php $__env->startSection('content'); ?>
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-header-content">
                <h1><i class="fas fa-chart-bar"></i> System Statistics</h1>
                <p>Thống kê tổng quan về hệ thống</p>
            </div>
        </div>

        <div class="profile-content">
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo e(number_format($stats['total_users'])); ?></h3>
                        <p>Tổng số người dùng</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo e(number_format($stats['active_users'])); ?></h3>
                        <p>Người dùng hoạt động</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo e(number_format($stats['admin_users'])); ?></h3>
                        <p>Quản trị viên</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo e(number_format($stats['recent_registrations'])); ?></h3>
                        <p>Đăng ký mới (7 ngày)</p>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="profile-card">
                <div class="card-header">
                    <h2><i class="fas fa-chart-line"></i> Biểu đồ thống kê</h2>
                </div>
                <div class="charts-content">
                    <div class="chart-placeholder">
                        <i class="fas fa-chart-area text-4xl text-gray-300 mb-4"></i>
                        <h3>Biểu đồ người dùng theo thời gian</h3>
                        <p>Chức năng này sẽ được phát triển trong tương lai</p>
                    </div>
                </div>
            </div>

            <!-- System Info -->
            <div class="profile-card">
                <div class="card-header">
                    <h2><i class="fas fa-info-circle"></i> Thông tin hệ thống</h2>
                </div>
                <div class="system-info">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Phiên bản Laravel:</span>
                            <span class="info-value"><?php echo e(app()->version()); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Phiên bản PHP:</span>
                            <span class="info-value"><?php echo e(phpversion()); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Môi trường:</span>
                            <span class="info-value"><?php echo e(config('app.env')); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Debug Mode:</span>
                            <span class="info-value"><?php echo e(config('app.debug') ? 'Bật' : 'Tắt'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Timezone:</span>
                            <span class="info-value"><?php echo e(config('app.timezone')); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Locale:</span>
                            <span class="info-value"><?php echo e(config('app.locale')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .profile-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .profile-header {
            background: linear-gradient(135deg, #003580 0%, #004a9e 100%);
            color: white;
            padding: 40px 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            text-align: center;
        }

        .profile-header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .profile-header p {
            font-size: 1rem;
            opacity: 0.9;
        }

        .profile-content {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .stat-card:nth-child(1) .stat-icon {
            background: linear-gradient(135deg, #007bff, #0056b3);
        }

        .stat-card:nth-child(2) .stat-icon {
            background: linear-gradient(135deg, #28a745, #218838);
        }

        .stat-card:nth-child(3) .stat-icon {
            background: linear-gradient(135deg, #dc3545, #c82333);
        }

        .stat-card:nth-child(4) .stat-icon {
            background: linear-gradient(135deg, #ffc107, #e0a800);
        }

        .stat-content h3 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 8px 0;
            color: #333;
        }

        .stat-content p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }

        .profile-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-header {
            background: #f8f9fa;
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .card-header h2 {
            margin: 0;
            font-size: 1.25rem;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header i {
            color: #003580;
        }

        .charts-content {
            padding: 40px 20px;
        }

        .chart-placeholder {
            text-align: center;
            padding: 40px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px dashed #dee2e6;
        }

        .chart-placeholder h3 {
            margin: 16px 0 8px 0;
            color: #333;
        }

        .chart-placeholder p {
            color: #666;
            margin: 0;
        }

        .system-info {
            padding: 20px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 16px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .info-label {
            font-weight: 600;
            color: #333;
        }

        .info-value {
            color: #007bff;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 20px;
            }

            .stat-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }

            .stat-content h3 {
                font-size: 1.5rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .info-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }
        }
    </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\booking-management\resources\views/profile/statistics.blade.php ENDPATH**/ ?>