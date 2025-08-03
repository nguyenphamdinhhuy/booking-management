

<?php $__env->startSection('content'); ?>
<div class="detail-container">
    <!-- Room Header -->
    <div class="detail-room-header">
        <h1 class="detail-room-title" id="room-name"><?php echo e($room->name); ?></h1>
        <div class="detail-room-meta">
            <div class="detail-rating">
                <div class="detail-stars">
                    <?php for($i = 0; $i < floor($room->rating); $i++): ?>
                        <i class="fas fa-star"></i>
                        <?php endfor; ?>
                        <?php if($room->rating - floor($room->rating) >= 0.5): ?>
                        <i class="fas fa-star-half-alt"></i>
                        <?php endif; ?>
                </div>
                <span>(<?php echo e($room->rating); ?>/5)</span>
            </div>
            <div class="detail-availability-badge" id="availability-status">
                <?php if($room->available): ?>
                <i class="fas fa-check-circle"></i> Còn trống
                <?php else: ?>
                <i class="fas fa-times-circle"></i> Hết phòng
                <?php endif; ?>
            </div>
        </div>
        <div class="detail-room-features">
            <div class="detail-feature">
                <i class="fas fa-users"></i>
                <span>Tối đa <?php echo e($room->max_guests ?? '4'); ?> khách</span>
            </div>
            <div class="detail-feature">
                <i class="fas fa-bed"></i>
                <span><?php echo e($room->number_of_beds ?? '2'); ?> giường</span>
            </div>
            <div class="detail-feature">
                <i class="fas fa-wifi"></i>
                <span>WiFi miễn phí</span>
            </div>
            <div class="detail-feature">
                <i class="fas fa-car"></i>
                <span>Đỗ xe miễn phí</span>
            </div>
        </div>
    </div>

    <!-- Image Gallery -->
    <div class="detail-image-gallery">
        <div class="detail-single-image" id="room-image">
            <img src="<?php echo e(asset($room->images)); ?>" alt="<?php echo e($room->name); ?>" style="width:100%; border-radius:12px;">
        </div>
    </div>

    <!-- Main Content -->
    <div class="detail-main-content">
        <!-- Room Details -->
        <div class="detail-room-details">
            <h2 class="detail-section-title">Mô tả phòng</h2>
            <div class="detail-description" id="room-description">
                <?php echo e($room->description ?? 'Không có mô tả cho phòng này.'); ?>

            </div>

            <div class="detail-amenities">
                <h3 class="detail-section-title">Tiện nghi phòng</h3>
                <div class="detail-amenities-grid">
                    <div class="detail-amenity-item">
                        <i class="fas fa-wifi"></i><span>WiFi miễn phí</span>
                    </div>
                    <div class="detail-amenity-item">
                        <i class="fas fa-snowflake"></i><span>Điều hòa không khí</span>
                    </div>
                    <div class="detail-amenity-item">
                        <i class="fas fa-tv"></i><span>TV màn hình phẳng</span>
                    </div>
                    <div class="detail-amenity-item">
                        <i class="fas fa-bath"></i><span>Phòng tắm riêng</span>
                    </div>
                    <div class="detail-amenity-item">
                        <i class="fas fa-coffee"></i><span>Minibar</span>
                    </div>
                    <div class="detail-amenity-item">
                        <i class="fas fa-door-open"></i><span>Ban công</span>
                    </div>
                    <div class="detail-amenity-item">
                        <i class="fas fa-phone"></i><span>Điện thoại</span>
                    </div>
                    <div class="detail-amenity-item">
                        <i class="fas fa-car"></i><span>Đỗ xe miễn phí</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Card -->
        <div class="detail-booking-card">
            <div class="detail-price-section">
                <span class="detail-price" id="price-per-night"><?php echo e($room->formatted_price); ?></span>
                <span class="detail-price-unit">VNĐ/đêm</span>
            </div>

            <form class="detail-booking-form" method="GET" action="<?php echo e(route('payment')); ?>" onsubmit="return validateVoucher();">
                <div class="detail-form-group">
                    <label class="detail-form-label">Ngày nhận - trả phòng</label>
                    <div class="detail-date-inputs">
                        <input type="date" class="detail-form-input" id="checkin-date" name="checkin" required>
                        <input type="date" class="detail-form-input" id="checkout-date" name="checkout" required>
                    </div>
                </div>
                <div class="detail-form-group">
                    <label class="detail-form-label">Mã giảm giá</label>
                    <input type="text" class="detail-form-input" name="discount_code" id="discount-code" placeholder="Nhập mã giảm giá nếu có">
                    <span id="voucher-error" style="color: #e53935; font-size: 13px; display: none;"></span>
                </div>
                <input type="hidden" name="guests" id="guests-hidden" value="2">
                <input type="hidden" name="r_id" value="<?php echo e($room->r_id); ?>">
                <input type="hidden" name="total_price" id="total-price-hidden" value="">
                <button type="submit" class="detail-book-btn">Đặt phòng ngay</button>
            </form>

            <p class="detail-booking-note">Không tính phí hủy trong 24h</p>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="detail-info-cards">
        <div class="detail-info-card">
            <h3><i class="fas fa-map-marker-alt"></i> Vị trí</h3>
            <p><?php echo e($room->location ?? 'Nằm ngay trung tâm thành phố, gần các điểm tham quan nổi tiếng.'); ?></p>
        </div>
        <div class="detail-info-card">
            <h3><i class="fas fa-utensils"></i> Ăn uống</h3>
            <p>Nhà hàng phục vụ các món ăn địa phương và quốc tế. Room service 24/7.</p>
        </div>
        <div class="detail-info-card">
            <h3><i class="fas fa-swimming-pool"></i> Tiện ích</h3>
            <p>Hồ bơi ngoài trời, spa, phòng gym và các dịch vụ giải trí khác.</p>
        </div>
    </div>

    <!-- Related Rooms -->
    <div class="related-rooms-section">
        <h2 class="related-title">Phòng liên quan</h2>
        <div class="related-rooms-grid">
            <?php $__empty_1 = true; $__currentLoopData = $relatedRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="related-room-card">
                <img src="<?php echo e(asset($item->images)); ?>" alt="<?php echo e($item->name); ?>" class="related-room-image">
                <div class="related-room-info">
                    <h4 class="related-room-name"><?php echo e($item->name); ?></h4>
                    <p class="related-room-price"><?php echo e($item->formatted_price); ?></p>
                    <p class="related-room-rating">Đánh giá: <?php echo e($item->rating); ?>/5</p>
                    <a href="<?php echo e(route('rooms_detail', $item->r_id)); ?>" class="related-room-button">Xem chi tiết</a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p>Không có phòng liên quan.</p>
            <?php endif; ?>
        </div>
    </div>

</div>
<script>
    // Tính tổng giá trị đơn hàng khi submit (giả sử đơn giá * số đêm)
    document.querySelector('.detail-booking-form').onsubmit = function(e) {
        const checkin = document.getElementById('checkin-date').value;
        const checkout = document.getElementById('checkout-date').value;
        const pricePerNight = {
            {
                $room - > price_per_night
            }
        };
        if (checkin && checkout) {
            const nights = (new Date(checkout) - new Date(checkin)) / (1000 * 60 * 60 * 24);
            document.getElementById('total-price-hidden').value = nights > 0 ? nights * pricePerNight : pricePerNight;
        }
    };

    function validateVoucher() {
        var code = document.getElementById('discount-code').value.trim();
        if (code.length > 0) {
            // Có thể kiểm tra định dạng mã ở đây nếu muốn
            // Nếu muốn kiểm tra mã hợp lệ ngay tại client, cần AJAX, còn không thì để backend xử lý
            // Ở đây chỉ reset lỗi
            document.getElementById('voucher-error').style.display = 'none';
        }
        return true;
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app\resources\views/user/room_detail.blade.php ENDPATH**/ ?>