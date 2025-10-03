<nav class="sidebar">

    <ul class="nav-menu">
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.bookings.management') }}" class="nav-link">
                <i class="fas fa-calendar-check"></i>
                <span>Quản lý đặt phòng</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.roomType.index') }}" class="nav-link">
                <i class="fas fa-hotel"></i>
                <span>Quản lí loại phòng</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.rooms.management') }}" class="nav-link">
                <i class="fas fa-bed"></i>
                <span>Quản lý phòng</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('services.index') }}" class="nav-link">
                <i class="fas fa-box"></i>
                <span>Quản lý dịch vụ</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('service-categories.index') }}" class="nav-link">
                <i class="fa-solid fa-clipboard-list"></i>
                <span>Quản lý loại dịch vụ</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('statistical.index') }}" class="nav-link">
                <i class="fas fa-chart-line"></i>
                <span>Báo cáo & Thống kê</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('vouchers.management') }}" class="nav-link">
                <i class="fas fa-ticket-alt"></i>
                <span>Quản lý mã giảm giá</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-star"></i>
                <span>Đánh giá & Phản hồi</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.contacts.index') }}" class="nav-link">
                <i class="fas fa-bell"></i>
                <span>Liên hệ</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('staff.show') }}" class="nav-link">
                <i class="fas fa-user-tie"></i>
                <span>Quản lý nhân viên</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.post.index') }}" class="nav-link">
                <i class="fas fa-newspaper"></i>
                <span>Quản lý bài viết</span>
            </a>
        </li>


        <li class="nav-item">
            <a href="{{ route('admin.banner.index') }}" class="nav-link">
                <i class="fas fa-chart-line"></i>
                <span>Quản Lý Banner</span>
            </a>
        </li>

    </ul>
</nav>