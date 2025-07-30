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
    /* Màu chữ khi hover */
  }

  .profile-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border-radius: 18px;
    /* border: 1px solid #ccc; */
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
  .btn-primary{
    color: white;
  }
</style>
<header class="bk-header">
  <div class="bk-header__bar">
    <div class="bk-header__left">
      <a class="bk-header__logo" href="<?php echo e(route('home')); ?>">Hotel <span>Booking</span></a>
    </div>

    <nav class="bk-header__nav">
      <ul class="bk-header__nav-list">
        <li><a href="<?php echo e(route('user.services.index')); ?>" class="bk-header__nav-link">Dịch vụ</a></li>
        <li><a href="#" class="bk-header__nav-link">Ưu đãi</a></li>
        <?php if(auth()->guard()->check()): ?>
      <li><a href="<?php echo e(route('booking.history', ['userId' => auth()->id()])); ?>" class="bk-header__nav-link">Hóa đơn của
        tôi</a></li>
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
      <div class="user-dropdown">
      <div class="profile-trigger" onclick="toggleProfileDropdown()">
      <img
      src="<?php echo e(Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name)); ?>"
      class="user-avatar" alt="Avatar">
      <span><?php echo e(Auth::user()->name); ?></span>
      <i class="fas fa-chevron-down"></i>
      </div>

      <div class="profile-dropdown" id="profileDropdown">
      <div class="profile-header">
      <img
        src="<?php echo e(Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name)); ?>"
        class="user-avatar-large" alt="Avatar">
      <div class="profile-info">
        <strong><?php echo e(Auth::user()->name); ?></strong>
        <small><?php echo e(Auth::user()->email); ?></small>
      </div>
      <?php if(Auth::user()->role == 'admin'): ?>
      <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-primary">
      <i class="fas fa-user-shield"></i> 
      </a>


      <?php endif; ?>
      </div>
      <ul class="profile-menu">
      <li><a href="<?php echo e(route('profile.edit')); ?>"><i class="fa-solid fa-user"></i> Thông tin cá nhân</a></li>
      <li><a href="<?php echo e(route('profile.password')); ?>"><i class="fa-solid fa-lock"></i> Đổi mật khẩu</a></li>
      <li><a href="<?php echo e(route('profile.orders')); ?>"><i class="fa-solid fa-shopping-bag"></i> Đơn đặt phòng</a></li>
      <li><a href="<?php echo e(route('profile.favorites')); ?>"><i class="fa-solid fa-heart"></i> Yêu thích</a></li>
      <li><a href="<?php echo e(route('profile.settings')); ?>"><i class="fa-solid fa-cog"></i> Cài đặt</a></li>
      <li>
        <form method="POST" action="<?php echo e(route('logout')); ?>">
        <?php echo csrf_field(); ?>
        <button type="submit"><i class="fa-solid fa-sign-out-alt"></i> Đăng xuất</button>
        </form>
      </li>
      </ul>
      </div>
      </div>
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
      <li><a href="<?php echo e(route('register')); ?>"><i class="fa-solid fa-user-plus"></i> Đăng ký</a></li>
      <li><a href="<?php echo e(route('login')); ?>"><i class="fa-solid fa-right-to-bracket"></i> Đăng nhập</a></li>
    <?php else: ?>
      <li><a href="<?php echo e(route('profile.edit')); ?>"><i class="fa-solid fa-user"></i> Thông tin cá nhân</a></li>
      <li><a href="<?php echo e(route('profile.password')); ?>"><i class="fa-solid fa-lock"></i> Đổi mật khẩu</a></li>
      <li><a href="<?php echo e(route('profile.orders')); ?>"><i class="fa-solid fa-shopping-bag"></i> Đơn đặt phòng</a></li>
      <li><a href="<?php echo e(route('profile.favorites')); ?>"><i class="fa-solid fa-heart"></i> Yêu thích</a></li>
      <li><a href="<?php echo e(route('profile.settings')); ?>"><i class="fa-solid fa-cog"></i> Cài đặt</a></li>
      <li>
      <form method="POST" action="<?php echo e(route('logout')); ?>">
        <?php echo csrf_field(); ?>
        <button type="submit"><i class="fa-solid fa-sign-out-alt"></i> Đăng xuất</button>
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

  document.addEventListener('click', function (event) {
    const dropdown = document.getElementById('profileDropdown');
    const trigger = document.querySelector('.profile-trigger');

    if (trigger && dropdown && !trigger.contains(event.target) && !dropdown.contains(event.target)) {
      dropdown.classList.remove('show');
    }
  });
</script><?php /**PATH D:\booking-management\resources\views/layout/navigation.blade.php ENDPATH**/ ?>