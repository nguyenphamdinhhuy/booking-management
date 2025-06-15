@extends('admin.layouts.master')
@section("content")

    <h1 class="page-title d-flex align-items-center justify-between">
        <span>
            <i class="fas fa-concierge-bell"></i>
            Quản lý dịch vụ
        </span>

        <div class="ml-auto">
            <select class="form-select" onchange="window.location.href=this.value" style="width: 200px;">
                <option disabled selected>Chuyển đến...</option>
                <option value="{{ route('service-categories.index') }}">Quản lý loại dịch vụ</option>
                <option value="{{ route('services.index') }}">Quản lý dịch vụ</option>
            </select>
        </div>
    </h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <!-- Room Management Table -->
    <div class="table-container">
        <!-- Table Header with Actions -->
        <div class="table-header">
            <h2 class="table-title">Danh sách dịch vụ</h2>
            <div class="table-actions">

                <a class="btn btn-primary" onclick="openAddRoomModal()" href="{{ route('service.create') }}"
                    class="btn btn-primary">
                    <i class="fas fa-plus"></i>Thêm dịch vụ</a>
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
                <label class="filter-label">Danh mục</label>
                <form method="GET" action="{{ route('services.index') }}">
                    <select name="category_id" class="filter-select" onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="filter-group">
                <label class="filter-label">Tìm kiếm</label>
                <input type="text" class="filter-input" placeholder="Tên dịch vụ..." onkeyup="searchRooms(this.value)">
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
                        <th>Tên dịch vụ</th>
                        <th>Giá</th>
                        <th>Đơn vị</th>
                        <th>Danh mục</th>
                        <th>Trạng thái</th>
                        <th>Ảnh</th>
                        <th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody id="roomsTableBody">
                    @foreach($services as $sv)
                        <tr>
                            <td>{{ $sv->s_id }}</td>
                            <td>{{ $sv->name }}</td>
                            <td>{{ number_format($sv->price) }} VND</td>
                            <td>{{ $sv->unit }}</td>
                            <td>{{ $sv->category->name ?? 'Không có' }}</td>
                            <td>
                                <span class="status-badge {{ $sv->is_available ? 'status-active' : 'status-inactive' }}">
                                    {{ $sv->is_available ? 'Hoạt động' : 'Ngưng' }}
                                </span>
                            </td>
                            <td>
                                @if($sv->image)
                                    <img src="{{ asset($sv->image) }}" width="80" height="60">
                                @else
                                    Không có
                                @endif
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
    </div>

@endsection