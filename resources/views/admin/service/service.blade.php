@extends('admin.layouts.master')
<style>

</style>
@section("content")
    {{-- Hiển thị thông báo --}}
    @if(session('success'))
        <div class="alert alert-success"
            style="background: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger"
            style="background: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif
    <h1 class="page-title d-flex align-items-center justify-between">
        <span>
            <i class="fas fa-concierge-bell"></i>
            Quản lý dịch vụ
        </span>


    </h1>

    <!-- Room Management Table -->
    <div class="table-container">
        <!-- Table Header with Actions -->
        <div class="table-header">
            <h2 class="table-title">Danh sách dịch vụ</h2>
            <div class="table-actions">

                <a href="{{ route('service.trashCan') }}" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Thùng rác
                </a>
                <a class="btn btn-primary" href="{{ route('service.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>Thêm dịch vụ</a>
            </div>
        </div>

        <!-- Filter Bar -->
        <form method="GET" action="{{ route('services.index') }}">
            <div class="filter-bar">
                <div class="filter-group">
                    <label class="filter-label">Trạng thái</label>
                    <select name="is_available" class="filter-select" onchange="this.form.submit()">
                        <option value="" {{ request('is_available') === null || request('is_available') === '' ? 'selected' : '' }}>Tất cả</option>
                        <option value="1" {{ request('is_available') === '1' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ request('is_available') === '0' ? 'selected' : '' }}>Ngưng</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Danh mục</label>
                    <select name="category_id" class="filter-select" onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Giá phòng</label>
                    <select name="price_range" class="filter-select" onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        <option value="0-500000" {{ request('price_range') == '0-500000' ? 'selected' : '' }}>Dưới 500,000 VND
                        </option>
                        <option value="500000-1000000" {{ request('price_range') == '500000-1000000' ? 'selected' : '' }}>
                            500,000 - 1,000,000 VND</option>
                        <option value="1000000-2000000" {{ request('price_range') == '1000000-2000000' ? 'selected' : '' }}>
                            1,000,000 - 2,000,000 VND</option>
                        <option value="2000000+" {{ request('price_range') == '2000000+' ? 'selected' : '' }}>Trên 2,000,000
                            VND</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Tìm kiếm</label>
                    <input type="text" name="keyword" value="{{ request('keyword') }}" class="filter-input"
                        placeholder="Tên dịch vụ...">
                </div>
                <div class="filter-group" style="align-items: end;">
                    <button type="button" class="btn btn-secondary" onclick="clear_Filters()">
                        <i class="fas fa-times"></i> Xóa bộ lọc
                    </button>
                </div>
            </div>
        </form>



        <!-- Data Table -->
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên dịch vụ</th>
                        <th>Ảnh</th>
                        <th>Giá</th>
                        <th>Đơn vị</th>
                        <th>Danh mục</th>
                        <th>Trạng thái</th>

                        <th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody id="roomsTableBody">
                    @foreach($services as $sv)
                        <tr>
                            <td>{{ $sv->s_id }}</td>
                            <td>{{ $sv->name }}</td>
                            <td>
                                @if($sv->image)
                                    <img src="{{ asset($sv->image) }}" width="80" height="60">
                                @else
                                    Không có
                                @endif
                            </td>
                            <td>{{ number_format($sv->price) }} VND</td>
                            <td>{{ $sv->unit }}</td>
                            <td>{{ $sv->category->name ?? 'Không có' }}</td>
                            <td>
                                <span class="status-badge {{ $sv->is_available ? 'status-active' : 'status-inactive' }}">
                                    {{ $sv->is_available ? '✅' : '❌' }}
                                </span>
                            </td>

                            <td>{{ Str::limit($sv->description, 50) }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('service.edit', $sv->s_id) }}" class="btn btn-warning btn-sm" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('service.destroy', $sv->s_id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Bạn có chắc chắn muốn xoá?')" type="submit"
                                            class="btn btn-danger btn-sm" title="Xoá">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $services->links('pagination::bootstrap-4') }}
        </div>


    </div>
    <div id="routeContainer" data-url="{{ route('admin.rooms.management') }}"></div>
    <script>
        function clear_Filters() {
            // Điều hướng về trang index mặc định, không có query string
            window.location.href = "{{ route('services.index') }}";
        }
    </script>
@endsection