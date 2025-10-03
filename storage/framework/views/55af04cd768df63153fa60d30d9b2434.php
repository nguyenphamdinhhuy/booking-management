<?php $__env->startSection('content'); ?>


<section class="hero-slider-section">
  <div class="hero-swiper swiper">
    <div class="swiper-wrapper">
      <?php if(isset($latestAlbum) && $latestAlbum->count() > 0): ?>
      <?php $__currentLoopData = $latestAlbum; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="swiper-slide">
        <img src="<?php echo e(asset('storage/' . $banner->images_path)); ?>" alt="<?php echo e($banner->title); ?>" />
        <div class="hero-slide-overlay">
          <h2><?php echo e($banner->title); ?></h2>
          <p><?php echo e($banner->description); ?></p>
        </div>
      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php else: ?>
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
            <?php endif; ?>
          </div>

          <!-- Add Pagination -->
          <div class="swiper-pagination"></div>
          <!-- Add Navigation -->
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>
</section>

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
<form class="b-search-bar" action="<?php echo e(route('search')); ?>" method="post">
  <?php echo csrf_field(); ?>
  <div class="b-search-field b-search-date">
    <span class="b-search-icon">
      <i class="fa-solid fa-calendar"></i>
    </span>
    <input
      type="date"
      name="check_in_date"
      placeholder="Ngày nhận phòng"
      value="<?php echo e(old('check_in_date', request('check_in_date'))); ?>"
      min="<?php echo e(date('Y-m-d')); ?>"
      required id="checkin-date" />
  </div>

  <div class="b-search-field b-search-date">
    <span class="b-search-icon">
      <i class="fa-solid fa-calendar-minus"></i>
    </span>
    <input
      type="date"
      name="check_out_date"
      placeholder="Ngày trả phòng"
      value="<?php echo e(old('check_out_date', request('check_out_date'))); ?>"
      min="<?php echo e(date('Y-m-d', strtotime('+1 day'))); ?>"
      required id="checkout-date" />
  </div>

  <div class="b-search-field b-search-guests">
    <span class="b-search-icon">
      <i class="fas fa-user-friends"></i>
    </span>
    <input
      type="number"
      name="guests"
      placeholder="Số khách"
      value="<?php echo e(old('guests', request('guests', 2))); ?>"
      min="1"
      max="10"
      required title="Số khách" />
  </div>

  <button class="b-search-btn" type="submit">
    <svg viewBox="0 0 24 24" fill="none" width="22" height="22">
      <circle cx="11" cy="11" r="8.5" stroke="#fff" stroke-width="2"></circle>
      <path d="M21 21l-4.35-4.35" stroke="#fff" stroke-width="2" stroke-linecap="round" />
    </svg>

  </button>
</form>






<section class="offers-section">
  <h2>Ưu đãi</h2>
  <p class="offers-subtitle">Khuyến mãi, giảm giá và ưu đãi đặc biệt dành riêng cho bạn</p>
  <div class="offers-container">

    <!-- Ưu đãi trắng -->
    <div class="offer-card white-card">
      <div class="offer-info">
        <h3>Kỳ nghỉ ngắn ngày chất lượng</h3>
        <p>Tiết kiệm đến 20% với Ưu Đãi Mùa Du Lịch</p>
        <a href="<?php echo e(route('user.vouchers')); ?>" class="offer-button">Săn ưu đãi</a>
      </div>
      <img src="images/image2.jpeg" alt="Ưu đãi 1" />
    </div>

    <div class="offer-card blue-card">
      <img src="<?php echo e(asset('images/image1.jpg')); ?>" alt="Giới thiệu khách sạn" />
      <div class="offer-info white-text">
        <h3>Booking Hotel — sang trọng & ấm cúng</h3>
        <p>
          Tọa lạc tại trung tâm, phòng rộng rãi với tiện nghi hiện đại, bữa sáng phong phú
          và đội ngũ phục vụ tận tâm 24/7.
        </p>
        <a href="<?php echo e(route('about')); ?>" class="offer-button white">Tìm hiểu thêm</a>
      </div>
    </div>

  </div>
</section>

<!-- Rooms Section -->


<!-- Room Types Section - Booking.com Style -->
<section class="room-types-section">
  <div class="room-types-container">
    <h2>Tìm theo loại chỗ nghỉ</h2>
    <p class="room-types-subtitle">Khám phá các loại chỗ nghỉ khác nhau</p>

    <?php if(isset($error)): ?>
    <div class="alert alert-error">
      <?php echo e($error); ?>

    </div>
    <?php endif; ?>

    <div class="room-types-grid">
      <?php if($roomTypes && $roomTypes->count() > 0): ?>
      <?php $__currentLoopData = $roomTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roomType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <a href="<?php echo e(route('room_types.show', ['id' => $roomType->rt_id])); ?>" class="room-type-card">
        <div class="room-type-image">
          <img src="<?php echo e(isset($roomType->main_image) ? asset($roomType->main_image) : asset('assets/images/default-room-type.jpg')); ?>"
            alt="<?php echo e($roomType->type_name); ?>"
            loading="lazy">

          <div class="room-type-overlay"></div>




        </div>

        <div class="room-type-content">
          <div class="room-type-header">
            <h3 class="room-type-title"><?php echo e($roomType->type_name); ?></h3>

            <div class="room-type-location">
              <i class="fas fa-map-marker-alt"></i>
              Việt Nam
            </div>

            <div class="room-type-rating">
              <span class="rating-badge"><?php echo e(number_format(rand(80, 95)/10, 1)); ?></span>
              <span class="rating-text">
                <?php
                $rating = rand(80, 95)/10;
                ?>
                <?php if($rating >= 9.0): ?>
                Tuyệt hảo
                <?php elseif($rating >= 8.5): ?>
                Rất tốt
                <?php elseif($rating >= 8.0): ?>
                Tốt
                <?php else: ?>
                Khá tốt
                <?php endif; ?>
              </span>
              <span class="rating-count">(<?php echo e(rand(150, 800)); ?> đánh giá)</span>
            </div>
          </div>



          <div class="room-type-features">
            <div class="room-type-specs">
              <?php if($roomType->max_guests): ?>
              <span class="room-type-spec-item">
                <i class="fas fa-user-friends"></i>
                <?php echo e($roomType->max_guests); ?> khách
              </span>
              <?php endif; ?>

              <?php if($roomType->number_beds): ?>
              <span class="room-type-spec-item">
                <i class="fas fa-bed"></i>
                <?php echo e($roomType->number_beds); ?> giường
              </span>
              <?php endif; ?>

              <?php if($roomType->room_size): ?>
              <span class="room-type-spec-item">
                <i class="fas fa-expand-arrows-alt"></i>
                <?php echo e($roomType->room_size); ?>

              </span>
              <?php endif; ?>

              <span class="room-type-spec-item">
                <i class="fas fa-wifi"></i>
                WiFi miễn phí
              </span>
            </div>

            <?php if(isset($roomType->amenity_list) && count($roomType->amenity_list) > 0): ?>
            <div class="room-type-amenities">
              <div class="room-type-amenities-list">
                <?php $__currentLoopData = array_slice($roomType->amenity_list, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="room-type-amenity-tag <?php echo e($index == 0 ? 'featured' : ''); ?>">
                  <?php echo e($amenity); ?>

                </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if(count($roomType->amenity_list) > 3): ?>
                <span class="room-type-amenity-tag">
                  +<?php echo e(count($roomType->amenity_list) - 3); ?> tiện nghi
                </span>
                <?php endif; ?>
              </div>
            </div>
            <?php endif; ?>
          </div>

          <div class="room-type-footer">


            <div class="room-type-price">
              <span class="room-type-price-label">Giá mỗi đêm từ</span>
              <div class="room-type-price-amount">
                <span class="room-type-current-price">
                  <?php echo e(number_format($roomType->base_price, 0, ',', '.')); ?>

                </span>
                <span class="room-type-currency">VND</span>
              </div>
            </div>
          </div>
        </div>
      </a>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php else: ?>
      <div class="no-room-types">
        <div class="no-room-types-content">
          <i class="fas fa-building"></i>
          <h3>Không tìm thấy loại phòng nào</h3>
          <p>Hiện tại không có loại phòng nào phù hợp với tiêu chí tìm kiếm.</p>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <?php if($roomTypes && $roomTypes->count() > 0): ?>
    <div class="room-types-view-more">
      <a href="<?php echo e(route('all_rooms')); ?>" class="room-types-view-more-btn">
        <i class="fas fa-th-large"></i>
        Hiển thị tất cả loại chỗ nghỉ
        <?php if(isset($stats['total_room_types'])): ?>
        (<?php echo e($stats['total_room_types']); ?>)
        <?php endif; ?>
      </a>
    </div>
    <?php endif; ?>

  </div>
</section>

<!-- Hero Section -->
<section id="home" class="gradient-bg text-white pt-20">
  <div class="container mx-auto px-6 py-20">
    <div class="text-center animate-fade-in">
      <h1 class="text-5xl md:text-6xl font-bold mb-6">BookingVN</h1>
      <p class="text-xl md:text-2xl mb-8 text-blue-100">Nền tảng đặt phòng khách sạn trực tuyến hàng đầu Việt
        Nam</p>
      <p class="text-lg mb-8 max-w-4xl mx-auto text-blue-50">
        Khám phá hàng triệu khách sạn, resort và homestay trên toàn thế giới với giá tốt nhất.
        Từ những khách sạn boutique sang trọng đến các homestay ấm cúng, chúng tôi mang đến cho bạn
        sự lựa chọn đa dạng phù hợp với mọi ngân sách và sở thích.
      </p>
      <p class="text-base mb-12 max-w-4xl mx-auto text-blue-100">
        Với công nghệ AI tiên tiến, hệ thống bảo mật cao cấp và đội ngũ chăm sóc khách hàng chuyên nghiệp,
        BookingVN cam kết mang đến trải nghiệm đặt phòng hoàn hảo nhất. Hơn 2.5 triệu lượt đặt phòng thành
        công
        và 1.8 triệu khách hàng tin tưởng đã chứng minh chất lượng dịch vụ của chúng tôi.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="<?php echo e(route('all_rooms')); ?>"
          class="bg-yellow-400 text-blue-900 px-8 py-4 rounded-lg font-semibold hover:bg-yellow-300 transition-colors">
          Bắt đầu đặt phòng
        </a>
        <a href="<?php echo e(route('about')); ?>"
          class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors">
          Tìm hiểu thêm
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Features Section -->
<section id="features" class="py-20 bg-gray-50">
  <div class="container mx-auto px-6">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-800 mb-4">Tính năng nổi bật</h2>
      <p class="text-xl text-gray-600 mb-4">Trải nghiệm đặt phòng hoàn hảo với các tính năng hiện đại</p>
      <p class="text-base text-gray-500 max-w-3xl mx-auto">
        BookingVN được phát triển với công nghệ tiên tiến nhất, tích hợp AI và machine learning để mang đến
        trải nghiệm cá nhân hóa tốt nhất. Chúng tôi không ngừng cải tiến để đáp ứng mọi nhu cầu của khách
        hàng
        từ việc tìm kiếm, so sánh đến đặt phòng và thanh toán.
      </p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div class="feature-card bg-white p-8 rounded-xl shadow-lg">
        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </div>
        <h3 class="text-xl font-semibold mb-4">Tìm kiếm thông minh</h3>
        <p class="text-gray-600">Hệ thống tìm kiếm được trang bị AI có thể hiểu ngôn ngữ tự nhiên và đưa ra
          gợi ý phù hợp.
          Bộ lọc đa chiều giúp bạn tìm kiếm theo vị trí, giá cả, tiện ích, đánh giá và nhiều tiêu chí
          khác.
          Tính năng "Tìm kiếm giống như tôi" sẽ học từ lịch sử đặt phòng để đưa ra những gợi ý cá nhân hóa
          tốt nhất.</p>
      </div>

      <div class="feature-card bg-white p-8 rounded-xl shadow-lg">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <h3 class="text-xl font-semibold mb-4">Đặt phòng tức thì</h3>
        <p class="text-gray-600">Công nghệ đặt phòng real-time kết nối trực tiếp với hệ thống quản lý của
          khách sạn,
          đảm bảo thông tin phòng trống được cập nhật liên tục. Chỉ cần 3 phút để hoàn tất đặt phòng với
          xác nhận
          tự động qua email và SMS. Hỗ trợ đặt phòng khẩn cấp trong vòng 2 giờ trước khi check-in.</p>
      </div>

      <div class="feature-card bg-white p-8 rounded-xl shadow-lg">
        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
            </path>
          </svg>
        </div>
        <h3 class="text-xl font-semibold mb-4">Giá tốt nhất</h3>
        <p class="text-gray-600">Hệ thống so sánh giá tự động quét hơn 200 trang web đặt phòng khác để đảm
          bảo bạn
          luôn có giá tốt nhất. Chương trình "Đảm bảo giá tốt nhất" hoàn tiền 120% chênh lệch nếu bạn tìm
          thấy
          giá rẻ hơn ở nơi khác. Ưu đãi độc quyền và giảm giá sớm chỉ có tại BookingVN.</p>
      </div>

      <div class="feature-card bg-white p-8 rounded-xl shadow-lg">
        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z">
            </path>
          </svg>
        </div>
        <h3 class="text-xl font-semibold mb-4">Hỗ trợ 24/7</h3>
        <p class="text-gray-600">Đội ngũ chăm sóc khách hàng chuyên nghiệp hỗ trợ bạn mọi lúc, mọi nơi bằng
          tiếng Việt.</p>
      </div>

      <div class="feature-card bg-white p-8 rounded-xl shadow-lg">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
            </path>
          </svg>
        </div>
        <h3 class="text-xl font-semibold mb-4">Đánh giá thật</h3>
        <p class="text-gray-600">Hệ thống đánh giá minh bạch từ khách hàng thực tế giúp bạn chọn lựa đúng
          đắn.</p>
      </div>

      <div class="feature-card bg-white p-8 rounded-xl shadow-lg">
        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
            </path>
          </svg>
        </div>
        <h3 class="text-xl font-semibold mb-4">Thanh toán an toàn</h3>
        <p class="text-gray-600">Bảo mật thông tin thanh toán với công nghệ mã hóa SSL và đa dạng phương
          thức thanh toán.</p>
      </div>
    </div>
  </div>
</section>

<!-- About Section -->
<section class="py-20 bg-white">
  <div class="container mx-auto px-6">
    <div class="grid lg:grid-cols-2 gap-12 items-center">
      <div>
        <h2 class="text-4xl font-bold text-gray-800 mb-6">Về BookingVN</h2>
        <p class="text-lg text-gray-600 mb-6">
          BookingVN ra đời từ ý tưởng tạo ra một nền tảng đặt phòng thực sự phục vụ người Việt Nam.
          Chúng tôi hiểu rằng mỗi chuyến đi đều mang ý nghĩa đặc biệt, và việc tìm kiếm chỗ ở phù hợp
          là bước đầu tiên quan trọng cho một hành trình hoàn hảo.
        </p>
        <p class="text-base text-gray-600 mb-6">
          Với kinh nghiệm sâu rộng trong lĩnh vực công nghệ và du lịch, đội ngũ BookingVN đã phát triển
          một hệ thống không chỉ mạnh mẽ về mặt kỹ thuật mà còn thân thiện, dễ sử dụng. Chúng tôi tự hào
          là cầu nối giữa du khách và hàng nghìn khách sạn, resort trên khắp thế giới.
        </p>
        <div class="grid grid-cols-2 gap-6">
          <div class="text-center p-4 bg-blue-50 rounded-lg">
            <div class="text-2xl font-bold text-blue-600 mb-2">2024</div>
            <p class="text-sm text-gray-600">Năm thành lập</p>
          </div>
          <div class="text-center p-4 bg-green-50 rounded-lg">
            <div class="text-2xl font-bold text-green-600 mb-2">50K+</div>
            <p class="text-sm text-gray-600">Khách sạn đối tác</p>
          </div>
        </div>
      </div>
      <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-8 rounded-2xl">
        <h3 class="text-2xl font-bold text-gray-800 mb-4">Sứ mệnh của chúng tôi</h3>
        <p class="text-gray-600 mb-6">
          "Làm cho việc đặt phòng trở nên đơn giản, minh bạch và đáng tin cậy. Mỗi khách hàng của
          BookingVN
          đều xứng đáng có được trải nghiệm du lịch tuyệt vời nhất với mức giá hợp lý nhất."
        </p>
        <div class="space-y-3">
          <div class="flex items-center space-x-3">
            <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
            <span class="text-sm text-gray-700">Minh bạch trong giá cả và dịch vụ</span>
          </div>
          <div class="flex items-center space-x-3">
            <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
            <span class="text-sm text-gray-700">Hỗ trợ khách hàng tận tâm 24/7</span>
          </div>
          <div class="flex items-center space-x-3">
            <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
            <span class="text-sm text-gray-700">Công nghệ tiên tiến, trải nghiệm tối ưu</span>
          </div>
          <div class="flex items-center space-x-3">
            <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
            <span class="text-sm text-gray-700">Cam kết chất lượng và uy tín</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Team Section -->
<section id="team" class="py-20 bg-gray-50">
  <div class="container mx-auto px-6">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-800 mb-4">Đội ngũ phát triển</h2>
      <p class="text-xl text-gray-600 mb-4">Những con người tài năng đằng sau BookingVN</p>
      <p class="text-base text-gray-500 max-w-3xl mx-auto">
        Đội ngũ BookingVN bao gồm 5 thành viên với chuyên môn sâu trong các lĩnh vực công nghệ khác nhau.
        Chúng tôi có kinh nghiệm phát triển các ứng dụng web quy mô lớn và hiểu sâu về nhu cầu của người
        dùng Việt Nam.
      </p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-8">
      <div class="text-center">
        <div
          class="w-32 h-32 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-2xl font-bold">
          H
        </div>
        <h3 class="text-lg font-semibold text-gray-800">Nguyễn Phạm Đình Huy</h3>
        <p class="text-blue-600 font-medium">Team Leader</p>
        <p class="text-gray-600 text-sm mt-2">Quản lý dự án & Phát triển Backend</p>
      </div>

      <div class="text-center">
        <div
          class="w-32 h-32 bg-gradient-to-br from-green-400 to-green-600 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-2xl font-bold">
          Đ
        </div>
        <h3 class="text-lg font-semibold text-gray-800">Lê Quang Đạt</h3>
        <p class="text-green-600 font-medium">Frontend Developer</p>
        <p class="text-gray-600 text-sm mt-2">Giao diện người dùng & UX/UI</p>
      </div>

      <div class="text-center">
        <div
          class="w-32 h-32 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-2xl font-bold">
          T
        </div>
        <h3 class="text-lg font-semibold text-gray-800">Trần Ngọc Thiện</h3>
        <p class="text-purple-600 font-medium">Backend Developer</p>
        <p class="text-gray-600 text-sm mt-2">Cơ sở dữ liệu & API</p>
      </div>

      <div class="text-center">
        <div
          class="w-32 h-32 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-2xl font-bold">
          V
        </div>
        <h3 class="text-lg font-semibold text-gray-800">Khổng Văn Vũ</h3>
        <p class="text-orange-600 font-medium">Full-stack Developer</p>
        <p class="text-gray-600 text-sm mt-2">Tích hợp hệ thống & Testing</p>
      </div>

      <div class="text-center">
        <div
          class="w-32 h-32 bg-gradient-to-br from-red-400 to-red-600 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-2xl font-bold">
          K
        </div>
        <h3 class="text-lg font-semibold text-gray-800">Võ Quang Lâm Khang</h3>
        <p class="text-red-600 font-medium">DevOps Engineer</p>
        <p class="text-gray-600 text-sm mt-2">Triển khai & Bảo mật hệ thống</p>
      </div>
    </div>
  </div>
</section>

<!-- Guide Section -->
<section id="guide" class="py-20 bg-gray-50">
  <div class="container mx-auto px-6">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-800 mb-4">Hướng dẫn sử dụng</h2>
      <p class="text-xl text-gray-600 mb-4">Đặt phòng chỉ với 4 bước đơn giản</p>
      <p class="text-base text-gray-500 max-w-4xl mx-auto">
        Chúng tôi đã thiết kế quy trình đặt phòng thật đơn giản và trực quan. Dù bạn là người mới sử dụng
        hay đã có kinh nghiệm đặt phòng online, BookingVN sẽ giúp bạn hoàn tất việc đặt phòng một cách nhanh
        chóng
        và thuận tiện nhất. Hệ thống sẽ hướng dẫn bạn từng bước một cách chi tiết.
      </p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
      <div class="text-center">
        <div
          class="w-20 h-20 bg-blue-600 rounded-full mx-auto mb-6 flex items-center justify-center text-white text-2xl font-bold">
          1
        </div>
        <h3 class="text-xl font-semibold mb-4">Tìm kiếm</h3>
        <p class="text-gray-600">Bắt đầu bằng cách nhập điểm đến (thành phố, quận/huyện hoặc tên khách sạn
          cụ thể),
          chọn ngày check-in và check-out, số lượng phòng và số khách. Hệ thống sẽ tự động gợi ý các địa
          điểm
          phổ biến và hiển thị bản đồ để bạn dễ dàng chọn lựa vị trí mong muốn.</p>
      </div>

      <div class="text-center">
        <div
          class="w-20 h-20 bg-blue-600 rounded-full mx-auto mb-6 flex items-center justify-center text-white text-2xl font-bold">
          2
        </div>
        <h3 class="text-xl font-semibold mb-4">So sánh</h3>
        <p class="text-gray-600">Duyệt qua danh sách khách sạn với thông tin đầy đủ: hình ảnh chất lượng
          cao,
          mô tả chi tiết tiện ích, đánh giá thực tế từ khách hàng và vị trí trên bản đồ. Sử dụng bộ lọc
          để thu hẹp lựa chọn theo giá, sao, tiện ích như WiFi, bể bơi, gym. Tính năng so sánh trực tiếp
          giúp bạn đưa ra quyết định tốt nhất.</p>
      </div>

      <div class="text-center">
        <div
          class="w-20 h-20 bg-blue-600 rounded-full mx-auto mb-6 flex items-center justify-center text-white text-2xl font-bold">
          3
        </div>
        <h3 class="text-xl font-semibold mb-4">Đặt phòng</h3>
        <p class="text-gray-600">Chọn phòng ưng ý, điền thông tin khách hàng và xác nhận đặt phòng.</p>
      </div>

      <div class="text-center">
        <div
          class="w-20 h-20 bg-blue-600 rounded-full mx-auto mb-6 flex items-center justify-center text-white text-2xl font-bold">
          4
        </div>
        <h3 class="text-xl font-semibold mb-4">Thanh toán</h3>
        <p class="text-gray-600">Thanh toán an toàn và nhận xác nhận đặt phòng qua email ngay lập tức.</p>
      </div>
    </div>
  </div>
</section>

<!-- Statistics Section -->
<section class="py-20 gradient-bg text-white">
  <div class="container mx-auto px-6">
    <div class="text-center mb-12">
      <h2 class="text-3xl font-bold mb-4">BookingVN trong con số</h2>
      <p class="text-blue-100 max-w-2xl mx-auto">
        Những con số ấn tượng chứng minh sự tin tưởng và hài lòng của khách hàng dành cho BookingVN
      </p>
    </div>
    <div class="grid md:grid-cols-4 gap-8 text-center">
      <div>
        <div class="text-4xl font-bold mb-2" id="hotels-count">0</div>
        <p class="text-blue-200 font-medium">Khách sạn đối tác</p>
        <p class="text-blue-300 text-sm mt-1">Trên toàn thế giới</p>
      </div>
      <div>
        <div class="text-4xl font-bold mb-2" id="bookings-count">0</div>
        <p class="text-blue-200 font-medium">Lượt đặt phòng</p>
        <p class="text-blue-300 text-sm mt-1">Thành công</p>
      </div>
      <div>
        <div class="text-4xl font-bold mb-2" id="customers-count">0</div>
        <p class="text-blue-200 font-medium">Khách hàng hài lòng</p>
        <p class="text-blue-300 text-sm mt-1">Đánh giá 4.5+ sao</p>
      </div>
      <div>
        <div class="text-4xl font-bold mb-2" id="countries-count">0</div>
        <p class="text-blue-200 font-medium">Quốc gia & vùng lãnh thổ</p>
        <p class="text-blue-300 text-sm mt-1">Phủ sóng toàn cầu</p>
      </div>
    </div>
  </div>
</section>

<!-- Commitment Section -->
<section class="py-20 bg-white">
  <div class="container mx-auto px-6">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-800 mb-4">Cam kết của BookingVN</h2>
      <p class="text-xl text-gray-600">Những lời hứa chúng tôi luôn giữ với khách hàng</p>
    </div>

    <div class="grid md:grid-cols-3 gap-8">
      <div class="text-center p-8 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
        <div class="w-16 h-16 bg-blue-600 rounded-full mx-auto mb-6 flex items-center justify-center">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
            </path>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-4">Bảo mật tuyệt đối</h3>
        <p class="text-gray-600">
          Thông tin cá nhân và thanh toán của bạn được bảo vệ bằng công nghệ mã hóa SSL 256-bit
          chuẩn ngân hàng. Chúng tôi cam kết không chia sẻ thông tin với bên thứ ba.
        </p>
      </div>

      <div class="text-center p-8 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
        <div class="w-16 h-16 bg-green-600 rounded-full mx-auto mb-6 flex items-center justify-center">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
            </path>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-4">Không phí ẩn</h3>
        <p class="text-gray-600">
          Giá hiển thị là giá cuối cùng bạn phải trả, đã bao gồm thuế và phí. Không có chi phí
          bất ngờ nào phát sinh trong quá trình đặt phòng hay khi check-in.
        </p>
      </div>

      <div class="text-center p-8 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl">
        <div class="w-16 h-16 bg-purple-600 rounded-full mx-auto mb-6 flex items-center justify-center">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
            </path>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-4">Hỗ trợ tận tâm</h3>
        <p class="text-gray-600">
          Đội ngũ chăm sóc khách hàng chuyên nghiệp sẵn sàng hỗ trợ bạn 24/7 qua điện thoại,
          email và chat trực tuyến. Mọi vấn đề sẽ được giải quyết nhanh chóng và hiệu quả.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-20 bg-white">
  <div class="container mx-auto px-6">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-gray-800 mb-4">Liên hệ với chúng tôi</h2>
      <p class="text-xl text-gray-600">Có câu hỏi? Chúng tôi luôn sẵn sàng hỗ trợ bạn</p>
    </div>

    <div class="grid md:grid-cols-3 gap-8 max-w-4xl mx-auto">
      <div class="text-center p-6">
        <div class="w-16 h-16 bg-blue-100 rounded-full mx-auto mb-4 flex items-center justify-center">
          <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
            </path>
          </svg>
        </div>
        <h3 class="text-lg font-semibold mb-2">Email</h3>
        <p class="text-gray-600">support@bookingvn.com</p>
      </div>

      <div class="text-center p-6">
        <div class="w-16 h-16 bg-blue-100 rounded-full mx-auto mb-4 flex items-center justify-center">
          <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
            </path>
          </svg>
        </div>
        <h3 class="text-lg font-semibold mb-2">Hotline</h3>
        <p class="text-gray-600">0905838679 (24/7)</p>
      </div>

      <div class="text-center p-6">
        <div class="w-16 h-16 bg-blue-100 rounded-full mx-auto mb-4 flex items-center justify-center">
          <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
            </path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
          </svg>
        </div>
        <h3 class="text-lg font-semibold mb-2">Địa chỉ</h3>
        <p class="text-gray-600">Đà Nẵng, Việt Nam</p>
      </div>
    </div>
  </div>
</section>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/user/home.blade.php ENDPATH**/ ?>