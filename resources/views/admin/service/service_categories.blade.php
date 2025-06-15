@extends('admin.layouts.master')
@section("content")

    <h1 class="page-title d-flex align-items-center justify-between">
        <span>
            <i class="fas fa-concierge-bell"></i>
            Quản lý loại dịch vụ
        </span>

        <div class="ml-auto">
            <select class="form-select" onchange="window.location.href=this.value" style="width: 200px;">
                <option disabled selected>Chuyển đến...</option>
                <option value="{{ route('service-categories.index') }}">Quản lý loại dịch vụ</option>
                <option value="{{ route('services.index') }}">Quản lý dịch vụ</option>
            </select>
        </div>
    </h1>


    <!-- Room Management Table -->
    <div class="table-container">
        <!-- Table Header with Actions -->
        <div class="table-header">
            <h2 class="table-title">Danh sách dịch vụ</h2>
            <div class="table-actions">

                <a class="btn btn-primary" onclick="openAddRoomModal()" href="{{ route('service-categories.create') }}"
                    class="btn btn-primary">
                    <i class="fas fa-plus"></i>Thêm danh mục</a>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <div class="filter-group">
                <label class="filter-label">Trạng thái</label>
                <select class="filter-select" onchange="filterRooms()">
                    <option value="">Tất cả</option>
                    <option value="1">Hoạt động</option>
                    <option value="0">Không hoạt động</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Loại dịch vụ</label>
                <select class="filter-select" onchange="filterRooms()">
                    <option value="">Tất cả</option>
                    <option value="0-500000">Đưa đón</option>
                    <option value="500000-1000000">Đặt đồ ăn</option>
                    <option value="1000000-2000000">Tậm gym</option>
                    <option value="2000000+">Ca hát</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Tìm kiếm</label>
                <input type="text" class="filter-input" placeholder="Tên phòng..." onkeyup="searchRooms(this.value)">
            </div>
        </div>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <!-- Data Table -->
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Loại dịch vụ</th>
                        <th>Mô tả</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody id="roomsTableBody">
                    @foreach($categories as $cat)
                        <tr>
                            <td>{{ $cat->id }}</td>
                            <td>{{ $cat->name }}</td>
                            <td>{{ $cat->description }}</td>
                            <td>{{ $cat->created_at ? $cat->created_at->format('d/m/Y') : '' }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('service-categories.edit', $cat->id) }}" class="btn btn-warning btn-sm"
                                        title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('service-categories.destroy', $cat->id) }}" method="POST"
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
    </div>

@endsection