@extends('layout.app')

@section('content')
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
          <input type="number" placeholder="2 người lớn · 0 trẻ em · 1 phòng"/>
      </div>
      <button class="b-search-btn" type="submit">
          <svg viewBox="0 0 24 24" fill="none" width="22" height="22"><circle cx="11" cy="11" r="8.5" stroke="#fff" stroke-width="2"></circle><path d="M21 21l-4.35-4.35" stroke="#fff" stroke-width="2" stroke-linecap="round"/></svg>
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

  <section class="room-list">
    <h2>Ưu đãi cho cuối tuần</h2>
    <p class="room-subtitle">Tiết kiệm cho chỗ nghỉ từ ngày 23 tháng 5 - ngày 25 tháng 5</p>
    <div class="rooms">

      <!-- Một phòng -->
      <div class="room-card">
        <img src="images/image1.jpg" alt="Star Hotel">
        <div class="room-content">
          <span class="label-genius">Genius</span>
          <h3>Star Hotel and Spa Danang</h3>
          <p class="location">Đà Nẵng, Việt Nam</p>
          <div class="rating">
            <span class="score">8,8</span>
            <div>
              <strong>Tuyệt vời</strong>
              <p>1.399 đánh giá</p>
            </div>
          </div>
          <span class="promo-badge">Ưu Đãi Mùa Du Lịch</span>
          <p class="price">2 đêm <span class="old-price">VND 2.200.000</span> <span class="new-price">VND 836.000</span></p>
        </div>
      </div>

      <div class="room-card">
        <img src="images/image1.jpg" alt="Star Hotel">
        <div class="room-content">
          <span class="label-genius">Genius</span>
          <h3>Star Hotel and Spa Danang</h3>
          <p class="location">Đà Nẵng, Việt Nam</p>
          <div class="rating">
            <span class="score">8,8</span>
            <div>
              <strong>Tuyệt vời</strong>
              <p>1.399 đánh giá</p>
            </div>
          </div>
          <span class="promo-badge">Ưu Đãi Mùa Du Lịch</span>
          <p class="price">2 đêm <span class="old-price">VND 2.200.000</span> <span class="new-price">VND 836.000</span></p>
        </div>
      </div>

      <div class="room-card">
        <img src="images/image1.jpg" alt="Star Hotel">
        <div class="room-content">
          <span class="label-genius">Genius</span>
          <h3>Star Hotel and Spa Danang</h3>
          <p class="location">Đà Nẵng, Việt Nam</p>
          <div class="rating">
            <span class="score">8,8</span>
            <div>
              <strong>Tuyệt vời</strong>
              <p>1.399 đánh giá</p>
            </div>
          </div>
          <span class="promo-badge">Ưu Đãi Mùa Du Lịch</span>
          <p class="price">2 đêm <span class="old-price">VND 2.200.000</span> <span class="new-price">VND 836.000</span></p>
        </div>
      </div>

      <div class="room-card">
        <img src="images/image1.jpg" alt="Star Hotel">
        <div class="room-content">
          <span class="label-genius">Genius</span>
          <h3>Star Hotel and Spa Danang</h3>
          <p class="location">Đà Nẵng, Việt Nam</p>
          <div class="rating">
            <span class="score">8,8</span>
            <div>
              <strong>Tuyệt vời</strong>
              <p>1.399 đánh giá</p>
            </div>
          </div>
          <span class="promo-badge">Ưu Đãi Mùa Du Lịch</span>
          <p class="price">2 đêm <span class="old-price">VND 2.200.000</span> <span class="new-price">VND 836.000</span></p>
        </div>
      </div>

      <div class="room-card">
        <img src="images/image1.jpg" alt="Star Hotel">
        <div class="room-content">
          <span class="label-genius">Genius</span>
          <h3>Star Hotel and Spa Danang</h3>
          <p class="location">Đà Nẵng, Việt Nam</p>
          <div class="rating">
            <span class="score">8,8</span>
            <div>
              <strong>Tuyệt vời</strong>
              <p>1.399 đánh giá</p>
            </div>
          </div>
          <span class="promo-badge">Ưu Đãi Mùa Du Lịch</span>
          <p class="price">2 đêm <span class="old-price">VND 2.200.000</span> <span class="new-price">VND 836.000</span></p>
        </div>
      </div>

      <div class="room-card">
        <img src="images/image1.jpg" alt="Star Hotel">
        <div class="room-content">
          <span class="label-genius">Genius</span>
          <h3>Star Hotel and Spa Danang</h3>
          <p class="location">Đà Nẵng, Việt Nam</p>
          <div class="rating">
            <span class="score">8,8</span>
            <div>
              <strong>Tuyệt vời</strong>
              <p>1.399 đánh giá</p>
            </div>
          </div>
          <span class="promo-badge">Ưu Đãi Mùa Du Lịch</span>
          <p class="price">2 đêm <span class="old-price">VND 2.200.000</span> <span class="new-price">VND 836.000</span></p>
        </div>
      </div>

      <div class="room-card">
        <img src="images/image1.jpg" alt="Star Hotel">
        <div class="room-content">
          <span class="label-genius">Genius</span>
          <h3>Star Hotel and Spa Danang</h3>
          <p class="location">Đà Nẵng, Việt Nam</p>
          <div class="rating">
            <span class="score">8,8</span>
            <div>
              <strong>Tuyệt vời</strong>
              <p>1.399 đánh giá</p>
            </div>
          </div>
          <span class="promo-badge">Ưu Đãi Mùa Du Lịch</span>
          <p class="price">2 đêm <span class="old-price">VND 2.200.000</span> <span class="new-price">VND 836.000</span></p>
        </div>
      </div>

      <div class="room-card">
        <img src="images/image1.jpg" alt="Star Hotel">
        <div class="room-content">
          <span class="label-genius">Genius</span>
          <h3>Star Hotel and Spa Danang</h3>
          <p class="location">Đà Nẵng, Việt Nam</p>
          <div class="rating">
            <span class="score">8,8</span>
            <div>
              <strong>Tuyệt vời</strong>
              <p>1.399 đánh giá</p>
            </div>
          </div>
          <span class="promo-badge">Ưu Đãi Mùa Du Lịch</span>
          <p class="price">2 đêm <span class="old-price">VND 2.200.000</span> <span class="new-price">VND 836.000</span></p>
        </div>
      </div>

      <!-- Bạn có thể lặp lại các .room-card với dữ liệu khác -->
      <!-- ... -->

    </div>
  </section>

@endsection