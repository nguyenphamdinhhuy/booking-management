<header class="bk-header">
  <div class="bk-header__bar">
    <div class="bk-header__left">
      <a class="bk-header__logo" href="index.html">Error <span>404</span></a>
    </div>
    <nav class="bk-header__nav">
      <ul class="bk-header__nav-list">
        <li><a href="<?php echo e(route('user.services.index')); ?>" class="bk-header__nav-link">Dịch vụ</a></li>
        <li><a href="#" class="bk-header__nav-link">Ưu đãi</a></li>
        <?php if(auth()->guard()->check()): ?>
        <li><a href="<?php echo e(route('booking.history', ['userId' => auth()->id()])); ?>" class="bk-header__nav-link">Hóa đơn của
            tôi</a>
        </li>
        <?php endif; ?>
        <li><a href="#" class="bk-header__nav-link">Hỗ trợ</a></li>
        <li><a href="#" class="bk-header__nav-link">Chỗ nghỉ của Quý vị</a></li>
      </ul>
    </nav>
    <div class="bk-header__actions">
      <?php if(auth()->guard()->guest()): ?>
      <a href="<?php echo e(route('register')); ?>" class="bk-header__action-btn bk-header__action-signup">Đăng ký</a>
      <a href="<?php echo e(route('login')); ?>" class="bk-header__action-btn bk-header__action-login">Đăng nhập</a>
    <?php else: ?>
      <span>Chào, <?php echo e(Auth::user()->name); ?></span>
      <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;">
      <?php echo csrf_field(); ?>
      <button type="submit" class="bk-header__action-btn">Đăng xuất</button>
      </form>
    <?php endif; ?>
    </div>

    <!-- Mobile menu button -->
    <button class="bk-header__menu-toggle" aria-label="Mở menu">
      <i class="burger fa-solid fa-bars"></i>
    </button>
  </div>
  <nav class="bk-header__mobile-nav" aria-label="Mobile menu">
    <button class="bk-header__menu-close" aria-label="Đóng menu">&times;</button>
    <ul>
      <li><a href="<?php echo e(route('user.services.index')); ?>"><i class="fa-solid fa-concierge-bell"></i> Dịch vụ</a></li>
      <li><a href="#"><i class="fa-solid fa-gift"></i> Ưu đãi</a></li>
      <li><a href="#"><i class="fa-solid fa-suitcase-rolling"></i> Hóa đơn của tôi</a></li>
      <li><a href="#"><i class="fa-solid fa-headset"></i> Hỗ trợ</a></li>
      <li><a href="#"><i class="fa-solid fa-hotel"></i> Chỗ nghỉ của Tôi</a></li>
      <?php if(auth()->guard()->guest()): ?>
      <li><a href="<?php echo e(route('register')); ?>" class="bk-header__action-btn bk-header__action-signup"><i
        class="fa-solid fa-user-plus"></i> Đăng ký</a></li>
      <li><a href="<?php echo e(route('login')); ?>" class="bk-header__action-btn bk-header__action-login"><i
        class="fa-solid fa-right-to-bracket"></i> Đăng nhập</a></li>
    <?php else: ?>
      <li><a href="<?php echo e(route('profile.edit')); ?>"><i class="fa-solid fa-user"></i> Thông tin cá nhân</a></li>
      <li><a href="<?php echo e(route('profile.password')); ?>"><i class="fa-solid fa-lock"></i> Đổi mật khẩu</a></li>
      <li><a href="<?php echo e(route('profile.orders')); ?>"><i class="fa-solid fa-shopping-bag"></i> Đơn đặt phòng</a></li>
      <li><a href="<?php echo e(route('profile.favorites')); ?>"><i class="fa-solid fa-heart"></i> Yêu thích</a></li>
      <li><a href="<?php echo e(route('profile.settings')); ?>"><i class="fa-solid fa-cog"></i> Cài đặt</a></li>
      <li>
      <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;">
        <?php echo csrf_field(); ?>
        <button type="submit" class="mobile-logout-btn"><i class="fa-solid fa-sign-out-alt"></i> Đăng xuất</button>
      </form>
      </li>
    <?php endif; ?>
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
</script><?php /**PATH D:\booking-management\resources\views/layout/navigation.blade.php ENDPATH**/ ?>