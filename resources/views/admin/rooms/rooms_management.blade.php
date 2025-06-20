@extends('admin.layouts.master')
@section("content")

{{-- Hiển thị thông báo --}}
@if(session('success'))
<div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 20px;">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 20px;">
    {{ session('error') }}
</div>
@endif

<h1 class="page-title">
    <i class="fas fa-bed"></i>
    Quản lý Phòng
</h1>

<!-- Room Management Table -->
<div class="table-container">
    <!-- Table Header with Actions -->
    <div class="table-header">
        <h2 class="table-title">Danh sách phòng (<span id="roomCount">{{ $rooms->count() }}</span> phòng)</h2>
        <div class="table-actions">
            <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Thêm phòng mới
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <form id="filterForm" method="GET" action="{{ route('admin.rooms.management') }}">
        <div class="filter-bar">
            <div class="filter-group">
                <label class="filter-label">Trạng thái</label>
                <select name="status" class="filter-select" onchange="applyFilters()">
                    <option value="">Tất cả</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Số khách tối đa</label>
                <select name="max_guests" class="filter-select" onchange="applyFilters()">
                    <option value="">Tất cả</option>
                    <option value="1" {{ request('max_guests') == '1' ? 'selected' : '' }}>1 khách</option>
                    <option value="2" {{ request('max_guests') == '2' ? 'selected' : '' }}>2 khách</option>
                    <option value="3" {{ request('max_guests') == '3' ? 'selected' : '' }}>3 khách</option>
                    <option value="4" {{ request('max_guests') == '4' ? 'selected' : '' }}>4+ khách</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Giá phòng</label>
                <select name="price_range" class="filter-select" onchange="applyFilters()">
                    <option value="">Tất cả</option>
                    <option value="0-500000" {{ request('price_range') == '0-500000' ? 'selected' : '' }}>Dưới 500,000 VND</option>
                    <option value="500000-1000000" {{ request('price_range') == '500000-1000000' ? 'selected' : '' }}>500,000 - 1,000,000 VND</option>
                    <option value="1000000-2000000" {{ request('price_range') == '1000000-2000000' ? 'selected' : '' }}>1,000,000 - 2,000,000 VND</option>
                    <option value="2000000+" {{ request('price_range') == '2000000+' ? 'selected' : '' }}>Trên 2,000,000 VND</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Tìm kiếm</label>
                <input type="text" name="search" class="filter-input" placeholder="Tên phòng, mô tả..."
                    value="{{ request('search') }}" onkeyup="searchRooms(this.value)">
            </div>
            <div class="filter-group" style="align-items: end;">
                <button type="button" class="btn btn-secondary" onclick="clearFilters()">
                    <i class="fas fa-times"></i> Xóa bộ lọc
                </button>
            </div>
        </div>
    </form>

    <!-- Loading indicator -->
    <div id="loadingIndicator" style="display: none; text-align: center; padding: 20px;">
        <i class="fas fa-spinner fa-spin"></i> Đang tìm kiếm...
    </div>

    <!-- Data Table -->
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên phòng</th>
                    <th>Hình ảnh</th>
                    <th>Giá/đêm</th>
                    <th>Số khách</th>
                    <th>Số giường</th>
                    <th>Trạng thái</th>
                    <th>Mô tả</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="roomsTableBody">
                @forelse($rooms as $room)
                <tr>
                    <td>{{ $room->r_id }}</td>
                    <td><strong>{{ $room->name }}</strong></td>
                    <td>
                        <div class="room-images">
                            @if($room->images)
                            <img src="{{ asset($room->images) }}" alt="{{ $room->name }}"
                                class="room-image" onclick="showImageModal('{{ asset($room->images) }}')"
                                style="width: 60px; height: 45px; object-fit: cover; border-radius: 4px; margin-right: 5px;">
                            @else
                            <span style="color: #999; font-style: italic;">Không có ảnh</span>
                            @endif
                        </div>
                    </td>
                    <td><strong>{{ $room->formatted_price }}</strong></td>
                    <td>{{ $room->max_guests ?? 'N/A' }}</td>
                    <td>{{ $room->number_beds ?? 'N/A' }}</td>
                    <td>
                        @if($room->status == 1)
                        <span class="status-badge status-active">Hoạt động</span>
                        @else
                        <span class="status-badge status-inactive">Không hoạt động</span>
                        @endif
                    </td>
                    <td>
                        @if($room->description)
                        {{ Str::limit($room->description, 50) }}
                        @else
                        <span style="color: #999; font-style: italic;">Chưa có mô tả</span>
                        @endif
                    </td>
                    <td>{{ $room->formatted_created_at }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.rooms.view', $room->r_id) }}" class="btn btn-primary btn-sm" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.rooms.edit', $room->r_id) }}" class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.rooms.delete', $room->r_id) }}" class="btn btn-danger btn-sm" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa phòng này?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="noDataRow">
                    <td colspan="10" style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-bed" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                        <span id="noDataMessage">
                            @if(request()->hasAny(['status', 'max_guests', 'price_range', 'search']))
                            Không tìm thấy phòng nào phù hợp với tiêu chí tìm kiếm.
                            @else
                            Chưa có phòng nào được tạo.
                            @endif
                        </span>
                        <br><br>
                        @if(!request()->hasAny(['status', 'max_guests', 'price_range', 'search']))
                        <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm phòng đầu tiên
                        </a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal xem ảnh --}}
<div id="imageModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.8);" onclick="closeImageModal()">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); max-width: 90%; max-height: 90%;">
        <img id="modalImage" style="width: 100%; height: auto; border-radius: 8px;">
        <button onclick="closeImageModal()" style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.8); border: none; border-radius: 50%; width: 40px; height: 40px; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
<div id="routeContainer" data-url="{{ route('admin.rooms.management') }}"></div>
@endsection