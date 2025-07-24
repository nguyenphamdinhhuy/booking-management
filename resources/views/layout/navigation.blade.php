<header class="bk-header">
  <div class="bk-header__bar">
    <div class="bk-header__left">
      <a class="bk-header__logo" href="index.html">Error <span>404</span></a>
    </div>
    <nav class="bk-header__nav">
      <ul class="bk-header__nav-list">
        <li><a href="{{ route('user.services.index') }}" class="bk-header__nav-link">Dịch vụ</a></li>
        <li><a href="#" class="bk-header__nav-link">Ưu đãi</a></li>
        @auth
        <li><a href="{{ route('booking.history', ['userId' => auth()->id()]) }}" class="bk-header__nav-link">Hóa đơn của
            tôi</a>
        </li>
        @endauth
        <li><a href="#" class="bk-header__nav-link">Hỗ trợ</a></li>
        <li><a href="#" class="bk-header__nav-link">Chỗ nghỉ của Quý vị</a></li>
      </ul>
    </nav>
    <div class="bk-header__actions">
      @guest
      <a href="{{ route('register') }}" class="bk-header__action-btn bk-header__action-signup">Đăng ký</a>
      <a href="{{ route('login') }}" class="bk-header__action-btn bk-header__action-login">Đăng nhập</a>
    @else
      <span>Chào, {{ Auth::user()->name }}</span>
      <form method="POST" action="{{ route('logout') }}" style="display: inline;">
      @csrf
      <button type="submit" class="bk-header__action-btn">Đăng xuất</button>
      </form>
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
      <li><a href="#"><i class="fa-solid fa-headset"></i> Hỗ trợ</a></li>
      <li><a href="#"><i class="fa-solid fa-hotel"></i> Chỗ nghỉ của Tôi</a></li>
      @guest
      <li><a href="{{ route('register') }}" class="bk-header__action-btn bk-header__action-signup"><i
        class="fa-solid fa-user-plus"></i> Đăng ký</a></li>
      <li><a href="{{ route('login') }}" class="bk-header__action-btn bk-header__action-login"><i
        class="fa-solid fa-right-to-bracket"></i> Đăng nhập</a></li>
    @else
      <li><a href="{{ route('profile.edit') }}"><i class="fa-solid fa-user"></i> Thông tin cá nhân</a></li>
      <li><a href="{{ route('profile.password') }}"><i class="fa-solid fa-lock"></i> Đổi mật khẩu</a></li>
      <li><a href="{{ route('profile.orders') }}"><i class="fa-solid fa-shopping-bag"></i> Đơn đặt phòng</a></li>
      <li><a href="{{ route('profile.favorites') }}"><i class="fa-solid fa-heart"></i> Yêu thích</a></li>
      <li><a href="{{ route('profile.settings') }}"><i class="fa-solid fa-cog"></i> Cài đặt</a></li>
      <li>
      <form method="POST" action="{{ route('logout') }}" style="display: inline;">
        @csrf
        <button type="submit" class="mobile-logout-btn"><i class="fa-solid fa-sign-out-alt"></i> Đăng xuất</button>
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

  // Đóng dropdown khi click bên ngoài
  document.addEventListener('click', function (event) {
    const dropdown = document.getElementById('profileDropdown');
    const trigger = document.querySelector('.profile-trigger');

    if (!trigger.contains(event.target) && !dropdown.contains(event.target)) {
      dropdown.classList.remove('show');
    }
  });
</script>