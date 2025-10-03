<style>
  .user-dropdown {
    position: relative;
  }

  .profile-menu li:hover {
    background-color: #f0f0f0;
  }

  .profile-menu li a:hover,
  .profile-menu li form button:hover {
    color: #007bff;
  }

  .profile-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border-radius: 18px;
    display: none;
    z-index: 1000;
    min-width: 220px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    overflow: hidden;
  }

  .profile-dropdown.show {
    display: block;
  }

  .user-avatar,
  .user-avatar-large {
    border-radius: 50%;
    width: 32px;
    height: 32px;
    object-fit: cover;
  }

  .user-avatar-large {
    width: 50px;
    height: 50px;
  }

  .profile-trigger {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
  }

  .profile-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    border-bottom: 1px solid #eee;
  }

  .profile-menu {
    list-style: none;
    margin: 0;
    padding: 0;
  }

  .profile-menu li {
    padding: 10px;
    border-bottom: 1px solid #eee;
  }

  .profile-menu li a,
  .profile-menu li form button {
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    color: #333;
    background: none;
    border: none;
    cursor: pointer;
    width: 100%;
    text-align: left;
  }

  .btn-primary {
    color: white;
  }
</style>
<header class="bk-header">
  <div class="bk-header__bar">
    <div class="bk-header__left">
      <a class="bk-header__logo" href="{{ route('index') }}">Hotel <span>Booking</span></a>
    </div>

    <nav class="bk-header__nav">
      <ul class="bk-header__nav-list">
        <!-- <li><a href="{{ route('user.services.index') }}" class="bk-header__nav-link">Dịch vụ</a></li> -->
        <li><a href="{{ route('getAllPost') }}" class="bk-header__nav-link">Bài Viết</a></li>
        <li><a href="{{ route('user.vouchers') }}" class="bk-header__nav-link">Ưu đãi</a></li>
        <li><a href="{{ route('about') }}" class="bk-header__nav-link">Giới thiệu</a></li>
        @auth
        <li><a href="{{ route('booking.history', ['userId' => auth()->id()]) }}" class="bk-header__nav-link">Hóa đơn</a></li>
        <li><a href="{{ route('contact.create') }}" class="bk-header__nav-link">Liên hệ</a></li>
        @endauth
        <!-- <li><a href="#" class="bk-header__nav-link">Hỗ trợ</a></li> -->
        <li><a href="{{ route('all_rooms') }}" class="bk-header__nav-link">Chỗ nghỉ</a></li>
      </ul>
    </nav>

    <div class="bk-header__actions">
      @guest
      <a href="{{ route('register') }}" class="bk-header__action-btn bk-header__action-signup">Đăng ký</a>
      <a href="{{ route('login') }}" class="bk-header__action-btn bk-header__action-login">Đăng nhập</a>
      @else
      <div class="user-dropdown">
        <div class="profile-trigger" onclick="toggleProfileDropdown()">
          <img
            src="{{ Auth::user()->avatar ? Auth::user()->avatar : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
            class="user-avatar" alt="Avatar">
          <span>{{ Auth::user()->name }}</span>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="profile-dropdown" id="profileDropdown">
          <div class="profile-header">
            <div class="profile-info">
              <small>{{ Auth::user()->email }}</small>
            </div>
            @if (Auth::user()->role == 'admin')
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
              <i class="fas fa-user-shield"></i>
            </a>


            @endif
          </div>
          <ul class="profile-menu">

            <li><a href="{{ route('profile.edit') }}"><i class="fa-solid fa-user"></i> Thông tin cá nhân</a></li>
            <li><a href="{{ route('profile.password') }}"><i class="fa-solid fa-lock"></i> Đổi mật khẩu</a></li>
            <li><a href="{{ route('profile.orders') }}"><i class="fa-solid fa-shopping-bag"></i> Đơn đặt phòng</a></li>
            <li><a href="{{ route('profile.favorites') }}"><i class="fa-solid fa-heart"></i> Yêu thích</a></li>
            <li><a href="{{ route('profile.settings') }}"><i class="fa-solid fa-cog"></i> Cài đặt</a></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"><i class="fa-solid fa-sign-out-alt"></i> Đăng xuất</button>
              </form>
            </li>
          </ul>
        </div>
      </div>
      @endguest
    </div>

    <!-- Mobile menu button -->
    <button class="bk-header__menu-toggle" aria-label="Mở menu">
      <i class="burger fa-solid fa-bars"></i>
    </button>
  </div>

  <nav class="bk-header__mobile-nav" aria-label="Mobile menu">
    <button class="bk-header__menu-close" aria-label="Đóng menu">&times;</button>
    <ul>
      <li><a href="{{ route('user.services.index') }}"><i class="fa-solid fa-concierge-bell"></i> Dịch vụ</a></li>
      <li><a href="#"><i class="fa-solid fa-gift"></i> Ưu đãi</a></li>
      <li><a href="#"><i class="fa-solid fa-suitcase-rolling"></i> Hóa đơn của tôi</a></li>
      <li><a href="#"><i class="fa-solid fa-headset"></i> Liên hệ</a></li>
      <li><a href="#"><i class="fa-solid fa-hotel"></i> Chỗ nghỉ của Tôi</a></li>
      @guest
      <li><a href="{{ route('register') }}"><i class="fa-solid fa-user-plus"></i> Đăng ký</a></li>
      <li><a href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket"></i> Đăng nhập</a></li>
      @else
      <li><a href="{{ route('profile.edit') }}"><i class="fa-solid fa-user"></i> Thông tin cá nhân</a></li>
      <li><a href="{{ route('profile.password') }}"><i class="fa-solid fa-lock"></i> Đổi mật khẩu</a></li>
      <li><a href="{{ route('profile.orders') }}"><i class="fa-solid fa-shopping-bag"></i> Đơn đặt phòng</a></li>
      <li><a href="{{ route('profile.favorites') }}"><i class="fa-solid fa-heart"></i> Yêu thích</a></li>
      <li><a href="{{ route('profile.settings') }}"><i class="fa-solid fa-cog"></i> Cài đặt</a></li>
      <li>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit"><i class="fa-solid fa-sign-out-alt"></i> Đăng xuất</button>
        </form>
      </li>
      @endguest
    </ul>
  </nav>

  <div class="bk-header__mobile-backdrop"></div>
</header>
<script>
  function toggleProfileDropdown() {
    const dropdown = document.getElementById('profileDropdown');
    dropdown.classList.toggle('show');
  }

  document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('profileDropdown');
    const trigger = document.querySelector('.profile-trigger');

    if (trigger && dropdown && !trigger.contains(event.target) && !dropdown.contains(event.target)) {
      dropdown.classList.remove('show');
    }
  });
</script>