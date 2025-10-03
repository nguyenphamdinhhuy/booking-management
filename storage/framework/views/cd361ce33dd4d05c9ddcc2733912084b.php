<header class="header">
    <div class="header-left">
        <div class="logo">
            <i class="fas fa-hotel"></i>
            <span>Hotel Admin</span>
        </div>
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-box" placeholder="Tìm kiếm đặt phòng, khách hàng...">
        </div>
    </div>
    <div class="header-right">
        <div class="admin-dropdown">
            <div class="admin-trigger" onclick="toggleAdminDropdown()">
                    <img src="<?php echo e(Str::startsWith(Auth::user()->avatar, 'http')
    ? Auth::user()->avatar
    : asset('storage/' . Auth::user()->avatar)); ?>" alt="Admin Avatar" class="admin-avatar-large" width="40px"
                        height="40px">
                <div class="admin-details">
                    <h4><?php echo e(Auth::user()->name); ?></h4>
                    <p>Quản trị viên</p>
                </div>
                <i class="fas fa-chevron-down dropdown-arrow"></i>
            </div>
            <div class="admin-dropdown-menu" id="adminDropdown">
                <div class="admin-header">
                    <img src="<?php echo e(Str::startsWith(Auth::user()->avatar, 'http')
    ? Auth::user()->avatar
    : asset('storage/' . Auth::user()->avatar)); ?>" alt="Admin Avatar" class="admin-avatar-large" width="40px"
                        height="40px">
                    <div class="admin-info">
                        <span><?php echo e(Auth::user()->name); ?></span>
                        <span><?php echo e(Auth::user()->email); ?></span>

                        <!-- <span class="admin-role">Quản trị viên</span> -->
                    </div>
                </div>
                <ul class="admin-menu-list">
                    <li><a href="<?php echo e(route('admin.profile')); ?>" class="admin-menu-item">
                            <i class="fas fa-user"></i>
                            <span>Thông tin cá nhân</span>
                        </a></li>
                    <li><a href="<?php echo e(route('admin.password')); ?>" class="admin-menu-item">
                            <i class="fas fa-lock"></i>
                            <span>Đổi mật khẩu</span>
                        </a></li>

                    <li><a href="<?php echo e(route('admin.statistics')); ?>" class="admin-menu-item">
                            <i class="fas fa-database"></i>
                            <span>Thống kê người dùng</span>
                        </a></li>
                    <li><a href="<?php echo e(route('admin.users')); ?>" class="admin-menu-item">
                            <i class="fas fa-users"></i>
                            <span>Quản lý người dùng</span>
                        </a></li>
                    <li><a href="<?php echo e(route('staff.register')); ?>" class="admin-menu-item">
                            <i class="fas fa-users"></i>
                            <span>Thêm nhân viên</span>
                        </a></li>
                    <li class="admin-menu-divider"></li>
                    <li><a href="<?php echo e(route('index')); ?>" class="admin-menu-item" target="_blank">
                            <i class="fas fa-external-link-alt"></i>
                            <span>Xem website</span>
                        </a></li>
                    <li>
                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="admin-menu-item">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="admin-logout-btn">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Đăng xuất</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

<script>
    function toggleAdminDropdown() {
        const dropdown = document.getElementById('adminDropdown');
        dropdown.classList.toggle('show');
    }

    // Đóng dropdown khi click bên ngoài
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('adminDropdown');
        const trigger = document.querySelector('.admin-trigger');

        if (!trigger.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    });
</script><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/admin/layouts/header.blade.php ENDPATH**/ ?>