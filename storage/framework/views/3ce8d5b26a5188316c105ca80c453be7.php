<?php $__env->startSection('content'); ?>
<section class="hero-slider-section">
  <div class="hero-swiper swiper">
    <div class="swiper-wrapper">
      <div class="swiper-slide">
        <img src="images/image1.jpg" alt="Resort view" />
        <div class="hero-slide-overlay">
          <h2>Khám phá khách sạn tuyệt vời trên toàn thế giới</h2>
          <p>Bước chân đến những điểm đến mơ ước cùng error 404!</p>
        </div>
      </div>
      <div class="swiper-slide">
        <img src="images/image2.jpeg" alt="Beach" />
        <div class="hero-slide-overlay">
          <h2>Đặt phòng dễ dàng & nhanh chóng</h2>
          <p>Chỉ với vài cú click, hành trình đã trong tầm tay bạn.</p>
        </div>
      </div>
      <div class="swiper-slide">
        <img src="images/image3.jpg" alt="Mountain" />
        <div class="hero-slide-overlay">
          <h2>Ưu đãi mỗi ngày, lựa chọn phong phú</h2>
          <p>error 404 giúp bạn tiết kiệm & thoả sức trải nghiệm!</p>
        </div>
      </div>
    </div>
    <!-- Add Pagination -->
    <div class="swiper-pagination"></div>
    <!-- Add Navigation -->
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
  </div>
</section>
<form class="b-search-bar">
  <div class="b-search-field b-search-loc">
    <span class="b-search-icon">
      <i class="fas fa-map-marker-alt"></i>

    </span>
    <input type="text" placeholder="Bạn muốn đi đâu?" />
  </div>
  <div class="b-search-field b-search-date">
    <span class="b-search-icon">
      <i class="fa-solid fa-calendar"></i>

    </span>
    <input type="date" placeholder="Ngày nhận phòng" />
  </div>

  <div class="b-search-field b-search-date">
    <span class="b-search-icon">
      <i class="fa-solid fa-calendar-minus"></i>
    </span>
    <input type="date" placeholder="Ngày trả phòng" />
  </div>

  <div class="b-search-field b-search-guests">
    <span class="b-search-icon">
      <i class="fas fa-bed"></i>
    </span>
    <input type="number" placeholder="2 người lớn · 0 trẻ em · 1 phòng" />
  </div>
  <button class="b-search-btn" type="submit">
    <svg viewBox="0 0 24 24" fill="none" width="22" height="22">
      <circle cx="11" cy="11" r="8.5" stroke="#fff" stroke-width="2"></circle>
      <path d="M21 21l-4.35-4.35" stroke="#fff" stroke-width="2" stroke-linecap="round" />
    </svg>
  </button>
</form>


<section class="explore">
  <h2>Khám phá các dịch vụ của <span>Error 404</span></h2>
  <div class="explore-list">
    <div class="explore-item">
      <img src="images/image1.jpg" alt="Khách sạn">
      <p>Khách sạn</p>
    </div>
    <div class="explore-item">
      <img src="images/food.png" alt="Căn hộ">
      <p>Thức ăn</p>
    </div>
    <div class="explore-item">
      <img src="images/drink.webp" alt="Resort">
      <p>Đồ uống</p>
    </div>
    <div class="explore-item">
      <img src="images/combo.jpeg" alt="Nhà nghỉ">
      <p>Combo</p>
    </div>
  </div>
</section>

<section class="offers-section">
  <h2>Ưu đãi</h2>
  <p class="offers-subtitle">Khuyến mãi, giảm giá và ưu đãi đặc biệt dành riêng cho bạn</p>
  <div class="offers-container">

    <!-- Ưu đãi trắng -->
    <div class="offer-card white-card">
      <div class="offer-info">
        <h3>Kỳ nghỉ ngắn ngày chất lượng</h3>
        <p>Tiết kiệm đến 20% với Ưu Đãi Mùa Du Lịch</p>
        <a href="#" class="offer-button">Săn ưu đãi</a>
      </div>
      <img src="images/image2.jpeg" alt="Ưu đãi 1" />
    </div>

    <!-- Ưu đãi nền xanh -->
    <div class="offer-card blue-card">
      <img src="images/image1.jpg" alt="Ưu đãi 2" />
      <div class="offer-info white-text">
        <p class="tagline">Tặng thưởng mới, chỉ dành cho thành viên như bạn</p>
        <h3>Thông báo giá vé máy bay Genius</h3>
        <p>Tiết kiệm cho vé máy bay với thông báo giá mọi lúc. Thiết lập thông báo để được cập nhật khi giá giảm.</p>
        <a href="#" class="offer-button white">Tải ứng dụng</a>
      </div>
    </div>

  </div>
</section>

<!-- Rooms Section -->
<section class="rooms-section">
  <div class="rooms-container">
    <h2>Phòng nghỉ được đề xuất</h2>
    <p class="rooms-subtitle">Các lựa chọn phòng nghỉ tuyệt vời cho chuyến đi của bạn</p>

    <?php if(isset($error)): ?>
    <div class="alert alert-error">
      <?php echo e($error); ?>

    </div>
    <?php endif; ?>

    <div class="rooms-list">
      <?php if($rooms && $rooms->count() > 0): ?>
      <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <a href="<?php echo e(route('rooms_detail', ['id' => $room->r_id])); ?>" class="room-card">
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
                  <span class="currency">₫</span>
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
          <p>Hiện tại không có phòng nào phù hợp với tiêu chí tìm kiếm của bạn.</p>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <?php if($rooms && $rooms->count() > 0): ?>
    <div class="view-more">
      <a href="" class="view-more-btn">
        Xem tất cả phòng
        <?php if(isset($stats['total_rooms'])): ?>
        (<?php echo e($stats['total_rooms']); ?> phòng)
        <?php endif; ?>
      </a>
    </div>
    <?php endif; ?>


  </div>
</section>





<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\booking-management\resources\views/user/home.blade.php ENDPATH**/ ?>