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
        <div class="admin-info">
            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face"
                alt="Admin Avatar" class="admin-avatar">
            <div class="admin-details">
                <h4>Chào, {{ Auth::user()->name }}</h4>
                <p>Quản trị viên</p>
            </div>
        </div>
      <form method="POST" action="{{ route('logout') }}" style="display: inline;">
        @csrf
        <button class="logout-btn" type="submit">
        <i class="fas fa-sign-out-alt"></i> Đăng xuất</button>
      </form>
    </div>
</header>