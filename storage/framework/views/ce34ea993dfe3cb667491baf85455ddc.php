

<?php $__env->startSection('title', 'Chi tiết loại phòng'); ?>

<?php $__env->startSection('content'); ?>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    .room-type-detail {
        background: #fff;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding-bottom: 20px;
    }

    .detail-header {
        background: #f7f8fa;
        padding: 20px;
        border-bottom: 1px solid #e0e0e0;
        text-align: center;
    }

    .detail-header h1 {
        margin: 0;
        font-size: 1.8em;
        font-weight: 600;
        color: #333;
    }

    .detail-header .subtitle {
        margin-top: 5px;
        font-size: 0.95em;
        color: #777;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        padding: 20px;
    }

    .info-card {
        background: #fafafa;
        border: 1px solid #eee;
        border-radius: 6px;
        padding: 15px;
    }

    .info-card h3 {
        font-size: 0.9em;
        font-weight: 600;
        margin-bottom: 8px;
        color: #555;
    }

    .info-card .value {
        font-size: 1.1em;
        font-weight: 500;
        color: #222;
    }

    .price-value {
        color: #e74c3c;
        font-weight: bold;
    }

    .section-title {
        font-size: 1.2em;
        font-weight: 600;
        margin-bottom: 15px;
        border-bottom: 2px solid #007bff;
        display: inline-block;
        padding-bottom: 5px;
        color: #333;
    }

    .description-section {
        padding: 20px;
    }

    .description-text {
        color: #555;
        line-height: 1.6;
    }

    .amenities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 10px;
    }

    .amenity-item {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f8f9fa;
        padding: 8px 12px;
        border-radius: 4px;
        font-size: 0.95em;
        color: #333;
        border: 1px solid #e0e0e0;
    }

    .amenity-item i {
        color: #007bff;
        font-size: 1.1em;
    }

    .images-gallery {
        padding: 20px;
    }

    .images-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .image-item img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #ddd;
        cursor: pointer;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
        padding: 15px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 8px 20px;
        border-radius: 4px;
        font-size: 0.9em;
        font-weight: 500;
        border: none;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-primary {
        background: #007bff;
        color: white;
    }

    .btn-success {
        background: #28a745;
        color: white;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modal.show {
        display: flex;
        opacity: 1;
    }

    .modal-content {
        position: relative;
        max-width: 90vw;
        max-height: 90vh;
    }

    .modal img {
        width: 100%;
        max-width: 800px;
        max-height: 600px;
        border-radius: 6px;
        object-fit: contain;
    }

    .close {
        position: absolute;
        top: -35px;
        right: 0;
        color: white;
        font-size: 28px;
        cursor: pointer;
    }
</style>
<div class="room-type-detail">
    <div class="detail-header">
        <h1><?php echo e($roomType->type_name); ?></h1>
        <p class="subtitle">Chi tiết thông tin loại phòng</p>
    </div>

    <!-- Thông tin cơ bản -->
    <div class="info-grid">
        <div class="info-card">
            <h3><i class="fas fa-money-bill-wave"></i> Giá cơ bản</h3>
            <p class="value price-value"><?php echo e(number_format($roomType->base_price, 0, ',', '.')); ?> VNĐ</p>
        </div>
        <div class="info-card">
            <h3><i class="fas fa-user-group"></i> Số khách tối đa</h3>
            <p class="value"><?php echo e($roomType->max_guests); ?> người</p>
        </div>
        <div class="info-card">
            <h3><i class="fas fa-bed"></i> Số giường</h3>
            <p class="value"><?php echo e($roomType->number_beds); ?> giường</p>
        </div>
        <div class="info-card">
            <h3><i class="fas fa-ruler-combined"></i> Diện tích phòng</h3>
            <p class="value"><?php echo e($roomType->room_size ?: 'Chưa cập nhật'); ?></p>
        </div>
        <div class="info-card">
            <h3><i class="fas fa-calendar-alt"></i> Ngày tạo</h3>
            <p class="value"><?php echo e($roomType->created_at->format('d/m/Y H:i')); ?></p>
        </div>
        <div class="info-card">
            <h3><i class="fas fa-toggle-on"></i> Trạng thái</h3>
            <p class="value">
                <?php if($roomType->status): ?>
                <span style="color:green;font-weight:600;"><i class="fas fa-check-circle"></i> Hoạt động</span>
                <?php else: ?>
                <span style="color:red;font-weight:600;"><i class="fas fa-times-circle"></i> Tạm ngưng</span>
                <?php endif; ?>
            </p>
        </div>
    </div>

    <!-- Mô tả -->
    <?php if($roomType->description): ?>
    <div class="description-section">
        <h2 class="section-title"><i class="fas fa-file-alt"></i> Mô tả chi tiết</h2>
        <div class="description-text">
            <?php echo nl2br(e($roomType->description)); ?>

        </div>
    </div>
    <?php endif; ?>

    <!-- Tiện nghi -->
    <?php if($roomType->amenities): ?>
    <div class="description-section">
        <h2 class="section-title"><i class="fas fa-concierge-bell"></i> Tiện nghi phòng</h2>
        <?php
        $amenities = json_decode($roomType->amenities, true);
        $amenityIcons = [
        'wifi' => 'fa-solid fa-wifi',
        'tivi' => 'fa-solid fa-tv',
        'tv' => 'fa-solid fa-tv',
        'máy lạnh' => 'fa-regular fa-snowflake',
        'điều hòa' => 'fa-regular fa-snowflake',
        'bồn tắm' => 'fa-solid fa-bath',
        'giường' => 'fa-solid fa-bed',
        'ban công' => 'fa-solid fa-umbrella-beach',
        'điện thoại' => 'fa-solid fa-phone',
        'tủ lạnh' => 'fa-solid fa-ice-cream',
        ];
        ?>
        <?php if($amenities && count($amenities) > 0): ?>
        <div class="amenities-grid">
            <?php $__currentLoopData = $amenities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(trim($amenity)): ?>
            <?php
            $icon = 'fa-solid fa-circle-check';
            foreach ($amenityIcons as $key => $val) {
            if (stripos($amenity, $key) !== false) {
            $icon = $val;
            break;
            }
            }
            ?>
            <div class="amenity-item">
                <i class="<?php echo e($icon); ?>"></i> <?php echo e($amenity); ?>

            </div>
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="no-data">Chưa có thông tin tiện nghi</div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Hình ảnh -->
    <?php if($roomType->images): ?>
    <div class="images-gallery">
        <h2 class="section-title"><i class="fas fa-camera"></i> Hình ảnh phòng</h2>
        <?php
        $images = json_decode($roomType->images, true);
        ?>
        <?php if($images && count($images) > 0): ?>
        <div class="images-grid">
            <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="image-item">
                <img src="<?php echo e(asset($image)); ?>"
                    alt="Hình ảnh <?php echo e($roomType->type_name); ?>"
                    onclick="openModal('<?php echo e(asset($image)); ?>')"
                    onerror="this.src='<?php echo e(asset('images/no-image.jpg')); ?>'; this.onerror=null;">
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="no-data">Chưa có hình ảnh</div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Nút hành động -->
    <div class="action-buttons">
        <a href="<?php echo e(route('admin.roomType.edit', $roomType->rt_id)); ?>" class="btn btn-primary"><i class="fas fa-pen"></i> Chỉnh sửa</a>
        <a href="<?php echo e(route('admin.roomType.create')); ?>" class="btn btn-success"><i class="fas fa-plus"></i> Thêm loại phòng mới</a>
        <a href="<?php echo e(route('admin.roomType.index')); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại danh sách</a>
    </div>
</div>

<!-- Modal xem ảnh -->
<div id="imageModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <img id="modalImage" src="" alt="Hình ảnh chi tiết">
    </div>
</div>
<script>
    function openModal(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        modalImg.src = imageSrc;
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('show'), 10);
    }

    function closeModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.remove('show');
        setTimeout(() => modal.style.display = 'none', 300);
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/admin/room_types/show.blade.php ENDPATH**/ ?>