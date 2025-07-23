<header class="bk-header">
  <div class="bk-header__bar">
    <div class="bk-header__left">
      <a class="bk-header__logo" href="{{ route('index') }}">Error <span>404</span></a>
    </div>
    <nav class="bk-header__nav">
      <ul class="bk-header__nav-list">
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
      <li><a href="#"><i class="fa-solid fa-gift"></i> Ưu đãi</a></li>
      <li><a href="#"><i class="fa-solid fa-suitcase-rolling"></i> Hóa đơn của tôi</a></li>
      <li><a href="#"><i class="fa-solid fa-headset"></i> Hỗ trợ</a></li>
      <li><a href="#"><i class="fa-solid fa-hotel"></i> Chỗ nghỉ của Tôi</a></li>
      <li><a href="#" class="bk-header__action-btn bk-header__action-signup"><i class="fa-solid fa-user-plus"></i> Đăng
          ký</a></li>
      <li><a href="#" class="bk-header__action-btn bk-header__action-login"><i class="fa-solid fa-right-to-bracket"></i>
          Đăng nhập</a></li>
    </ul>
  </nav>
  <div class="bk-header__mobile-backdrop"></div>
</header>