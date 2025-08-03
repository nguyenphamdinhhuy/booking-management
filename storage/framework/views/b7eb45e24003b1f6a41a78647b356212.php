

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


<?php if($errors->any()): ?>
<div class="custom-alert custom-alert-error" id="alert-validate">
    <i class="fas fa-exclamation-triangle"></i>
    <ul style="margin: 0; padding-left: 20px;">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li><?php echo e($error); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>
<?php endif; ?>


<section class="page-hero">
    <div class="page-hero-content">
        <h1>Tất cả phòng nghỉ</h1>
        <p>Khám phá toàn bộ các lựa chọn phòng nghỉ tuyệt vời của chúng tôi</p>
    </div>
</section>


<section class="filter-section">
    <div class="filter-container">
        <h3>Bộ lọc</h3>
        <div class="filter-options">
            <div class="filter-group">
                <label>Trạng thái phòng:</label>
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="all">Tất cả</button>
                    <button class="filter-btn" data-filter="available">Còn trống</button>
                    <button class="filter-btn" data-filter="unavailable">Hết phòng</button>
                </div>
            </div>

            <div class="filter-group">
                <label>Sắp xếp theo:</label>
                <select class="sort-select">
                    <option value="price-asc">Giá thấp đến cao</option>
                    <option value="price-desc">Giá cao đến thấp</option>
                    <option value="rating-desc">Đánh giá cao nhất</option>
                    <option value="name-asc">Tên A-Z</option>
                </select>
            </div>
        </div>
    </div>
</section>


<section class="rooms-section">
    <div class="rooms-container">
        <div class="rooms-header">
            <h2>Danh sách tất cả phòng nghỉ</h2>
            <div class="rooms-stats">
                <?php if(isset($stats)): ?>
                <p class="rooms-subtitle">
                    Tìm thấy <?php echo e($stats['total_rooms'] ?? 0); ?> phòng
                    (<?php echo e($stats['available_rooms'] ?? 0); ?> còn trống, <?php echo e($stats['unavailable_rooms'] ?? 0); ?> hết phòng)
                </p>
                <?php else: ?>
                <p class="rooms-subtitle">Hiển thị tất cả các phòng nghỉ có sẵn</p>
                <?php endif; ?>
            </div>
        </div>

        <?php if(isset($error)): ?>
        <div class="alert alert-error">
            <?php echo e($error); ?>

        </div>
        <?php endif; ?>

        <div class="rooms-list">
            <?php if($rooms && $rooms->count() > 0): ?>
            <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('rooms_detail', ['id' => $room->r_id])); ?>" class="room-card <?php echo e(!$room->available ? 'room-unavailable' : ''); ?>">
                <div class="room-image">
                    <img src="<?php echo e($room->images ? asset($room->images) : asset('assets/images/default-room.jpg')); ?>"
                        alt="<?php echo e($room->name); ?>">

                    <div class="room-status <?php echo e($room->available ? 'available' : 'unavailable'); ?>">
                        <?php echo e($room->available ? 'Còn trống' : 'Hết phòng'); ?>

                    </div>

                </div>

                <div class="room-info">
                    <div class="room-header">
                        <h3 class="room-name"><?php echo e($room->name); ?></h3>
                        <div class="room-rating">
                            <span class="rating-score"><?php echo e($room->rating ?? '4.5'); ?></span>
                            <span class="rating-text">
                                <?php if(($room->rating ?? 4.5) >= 4.5): ?>
                                Tuyệt vời
                                <?php elseif(($room->rating ?? 4.5) >= 4.0): ?>
                                Rất tốt
                                <?php elseif(($room->rating ?? 4.5) >= 3.5): ?>
                                Tốt
                                <?php else: ?>
                                Khá tốt
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>

                    <div class="room-details">
                        <div class="room-specs">
                            <?php if($room->max_guests): ?>
                            <span class="spec-item">
                                <i class="fas fa-user-friends"></i>
                                Tối đa <?php echo e($room->max_guests); ?> khách
                            </span>
                            <?php endif; ?>

                            <?php if($room->number_beds): ?>
                            <span class="spec-item">
                                <i class="fas fa-bed"></i>
                                <?php echo e($room->number_beds); ?> giường
                            </span>
                            <?php endif; ?>

                            <span class="spec-item">
                                <i class="fas fa-wifi"></i>
                                WiFi miễn phí
                            </span>

                            <span class="spec-item">
                                <i class="fas fa-map-marker-alt"></i>
                                Việt Nam
                            </span>
                        </div>

                        <?php if($room->description): ?>
                        <div class="room-description">
                            <p><?php echo e(Str::limit($room->description, 100)); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="room-pricing">
                        <div class="price-info">
                            <span class="price-label">Giá mỗi đêm từ</span>
                            <div class="price-amount">
                                <div class="current-price">
                                    <span class="price"><?php echo e(number_format($room->price_per_night, 0, ',', '.')); ?></span>
                                    <span class="currency">VND</span>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
            <div class="no-rooms">
                <div class="no-rooms-content">
                    <i class="fas fa-bed"></i>
                    <h3>Không có phòng nào</h3>
                    <p>Hiện tại không có phòng nào trong hệ thống.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <?php if($rooms && $rooms->hasPages()): ?>
        <div class="pagination-wrapper">
            <?php echo e($rooms->links()); ?>

        </div>
        <?php endif; ?>

        
        <div class="back-to-home">
            <a href="<?php echo e(route('index')); ?>" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Quay lại trang chủ
            </a>
        </div>
    </div>
</section>

<style>
    /* Hero Section */
    .page-hero {
        background: linear-gradient(to right, #003580, #0071c2);
        /* màu xanh chủ đạo như Booking */
        color: white;
        padding: 60px 20px;
        text-align: center;
    }

    .page-hero h1 {
        font-size: 42px;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .page-hero p {
        font-size: 18px;
        font-weight: 400;
        opacity: 0.9;
    }

    /* Filter Section */
    .filter-section {
        background-color: #f5f5f5;
        padding: 30px 20px;
        border-bottom: 1px solid #ddd;
    }

    .filter-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .filter-container h3 {
        font-size: 20px;
        margin-bottom: 15px;
        color: #333;
    }

    .filter-options {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        align-items: center;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 10px;
        min-width: 200px;
    }

    .room-unavailable {
        pointer-events: none;
        opacity: 0.6;
        cursor: not-allowed;
    }

    .filter-group label {
        font-weight: 600;
        color: #333;
    }

    /* Filter Buttons */
    .filter-buttons {
        display: flex;
        gap: 10px;
    }

    .filter-btn {
        padding: 8px 14px;
        border: 1px solid #0071c2;
        background-color: white;
        color: #0071c2;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
        transition: 0.3s;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background-color: #0071c2;
        color: white;
    }

    /* Sort Select */
    .sort-select {
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        min-width: 180px;
        font-size: 15px;
        color: #333;
        background-color: white;
    }

    @media (max-width: 768px) {
        .filter-options {
            flex-direction: column;
            align-items: flex-start;
        }

        .page-hero h1 {
            font-size: 32px;
        }

        .page-hero p {
            font-size: 16px;
        }
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        const roomCards = document.querySelectorAll('.room-card');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');

                const filter = this.getAttribute('data-filter');

                roomCards.forEach(card => {
                    const isAvailable = card.querySelector('.room-status.available');

                    if (filter === 'all') {
                        card.style.display = 'block';
                    } else if (filter === 'available' && isAvailable) {
                        card.style.display = 'block';
                    } else if (filter === 'unavailable' && !isAvailable) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Sort functionality
        const sortSelect = document.querySelector('.sort-select');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                const sortBy = this.value;
                const roomsList = document.querySelector('.rooms-list');
                const rooms = Array.from(roomCards);

                rooms.sort((a, b) => {
                    switch (sortBy) {
                        case 'price-asc':
                            const priceA = parseInt(a.querySelector('.price').textContent.replace(/\D/g, ''));
                            const priceB = parseInt(b.querySelector('.price').textContent.replace(/\D/g, ''));
                            return priceA - priceB;
                        case 'price-desc':
                            const priceA2 = parseInt(a.querySelector('.price').textContent.replace(/\D/g, ''));
                            const priceB2 = parseInt(b.querySelector('.price').textContent.replace(/\D/g, ''));
                            return priceB2 - priceA2;
                        case 'rating-desc':
                            const ratingA = parseFloat(a.querySelector('.rating-score').textContent);
                            const ratingB = parseFloat(b.querySelector('.rating-score').textContent);
                            return ratingB - ratingA;
                        case 'name-asc':
                            const nameA = a.querySelector('.room-name').textContent;
                            const nameB = b.querySelector('.room-name').textContent;
                            return nameA.localeCompare(nameB);
                        default:
                            return 0;
                    }
                });

                // Clear and re-append sorted rooms
                roomsList.innerHTML = '';
                rooms.forEach(room => roomsList.appendChild(room));
            });
        }

        // Auto-hide alerts
        const alerts = document.querySelectorAll('.custom-alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }, 5000);
        });
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app\resources\views/user/all_rooms.blade.php ENDPATH**/ ?>