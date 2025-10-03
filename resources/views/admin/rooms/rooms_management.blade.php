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
            <a href="{{ route('admin.rooms.trash') }}" class="btn btn-secondary">
                <i class="fas fa-trash"></i>
                Thùng rác
                @php
                $trashCount = DB::table('rooms')->where('is_delete', 1)->count();
                @endphp
                @if($trashCount > 0)
                <span class="badge badge-danger" style="background: #dc3545; color: white; border-radius: 50%; padding: 2px 6px; font-size: 10px; margin-left: 5px;">
                    {{ $trashCount }}
                </span>
                @endif
            </a>
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
                <label class="filter-label">Loại phòng</label>
                <select name="room_type" class="filter-select" onchange="applyFilters()">
                    <option value="">Tất cả loại phòng</option>
                    @foreach($roomTypes as $roomType)
                    <option value="{{ $roomType->rt_id }}" {{ request('room_type') == $roomType->rt_id ? 'selected' : '' }}>
                        {{ $roomType->type_name }}
                    </option>
                    @endforeach
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
                <input type="text" name="search" class="filter-input" placeholder="Tên phòng, mô tả, loại phòng..."
                    value="{{ request('search') }}" onkeyup="searchRooms(this.value)">
            </div>
            <div class="filter-group" style="align-items: end;">
                <a href="{{ route('admin.rooms.management') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Xóa bộ lọc
                </a>
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
                    <th>Loại phòng</th>
                    <th>Hình ảnh</th>
                    <th>Giá/đêm</th>
                    <th>Số khách</th>
                    <th>Số giường</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="roomsTableBody">
                @forelse($rooms as $room)
                <tr>
                    <td>{{ $room->r_id }}</td>
                    <td>
                        <strong>{{ $room->name }}</strong>
                        @if(isset($room->discount_percent) && $room->discount_percent > 0)
                        <span class="discount-badge" style="background: #ff4757; color: white; padding: 2px 6px; border-radius: 3px; font-size: 10px; margin-left: 5px;">
                            -{{ $room->discount_percent }}%
                        </span>
                        @endif
                    </td>
                    <td>
                        <div class="room-type-info">
                            <strong>{{ $room->room_type_display }}</strong>
                        </div>
                    </td>
                    <td>
                        <div class="room-images">
                            @if($room->images)
                            <img src="{{ asset($room->images) }}" alt="{{ $room->name }}"
                                class="room-image" onclick="showImageModal('{{ asset($room->images) }}')"
                                style="width: 60px; height: 45px; object-fit: cover; border-radius: 4px; margin-right: 5px; cursor: pointer;">
                            @elseif(isset($room->image_list) && count($room->image_list) > 0)
                            <img src="{{ asset($room->image_list[0]) }}" alt="{{ $room->name }}"
                                class="room-image" onclick="showImageModal('{{ asset($room->image_list[0]) }}')"
                                style="width: 60px; height: 45px; object-fit: cover; border-radius: 4px; margin-right: 5px; cursor: pointer;">
                            @if(isset($room->image_count) && $room->image_count > 1)
                            <span class="image-count" style="font-size: 10px; color: #666;">+{{ $room->image_count - 1 }}</span>
                            @endif
                            @else
                            <span style="color: #999; font-style: italic;">Không có ảnh</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="price-info">
                            <strong style="color: #27ae60;">{{ $room->formatted_price }}</strong>
                            @if(isset($room->formatted_old_price) && $room->discount_percent > 0)
                            <br>
                            <small style="text-decoration: line-through; color: #999;">{{ $room->formatted_old_price }}</small>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="guest-info">
                            <i class="fas fa-user" style="color: #666; margin-right: 3px;"></i>
                            {{ $room->max_guests ?? 'N/A' }}
                        </span>
                    </td>
                    <td>
                        <span class="bed-info">
                            <i class="fas fa-bed" style="color: #666; margin-right: 3px;"></i>
                            {{ $room->number_beds ?? 'N/A' }}
                        </span>
                    </td>
                    <td>
                        @if($room->status == 1)
                        <span style="margin-bottom: 10px;" class="status-badge status-active">Hoạt động</span>
                        @else
                        <span class="status-badge status-inactive">Không hoạt động</span>
                        @endif
                        @if(isset($room->available))
                        <br>
                        @if($room->available == 1)
                        <small style="background: green; color:#fff;" class="status-badge status-active">Có sẵn</small>
                        @else
                        <small style="background: red; color:#fff;" class="status-badge status-inactive">Đã đặt</small>
                        @endif
                        @endif
                    </td>

                    <td>{{ $room->formatted_created_at }}</td>
                    <td>
                        <div class="action-buttons">

                            <a href="{{ route('admin.rooms.edit', $room->r_id) }}" class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.rooms.delete', $room->r_id) }}" class="btn btn-danger btn-sm" title="Chuyển vào thùng rác" onclick="return confirm('Bạn có chắc chắn muốn chuyển phòng này vào thùng rác?')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="noDataRow">
                    <td colspan="11" style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-bed" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                        <span id="noDataMessage">
                            @if(request()->hasAny(['status', 'room_type', 'max_guests', 'price_range', 'search']))
                            Không tìm thấy phòng nào phù hợp với tiêu chí tìm kiếm.
                            @else
                            Chưa có phòng nào được tạo.
                            @endif
                        </span>
                        <br><br>
                        @if(!request()->hasAny(['status', 'room_type', 'max_guests', 'price_range', 'search']))
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
    <div class="d-flex justify-content-center mt-3">
        {{ $rooms->links('pagination::bootstrap-4') }}
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

{{-- Modal xem chi tiết loại phòng --}}
<div id="roomTypeModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);" onclick="closeRoomTypeModal()">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 8px; max-width: 500px; width: 90%;" onclick="event.stopPropagation()">
        <div id="roomTypeContent">
            <!-- Content will be loaded here -->
        </div>
        <button onclick="closeRoomTypeModal()" style="position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 20px; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<div id="routeContainer" data-url="{{ route('admin.rooms.management') }}"></div>

<script>
    // Function to show image modal
    function showImageModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModal').style.display = 'block';
    }

    // Function to close image modal
    function closeImageModal() {
        document.getElementById('imageModal').style.display = 'none';
    }

    // Function to show room type details
    function showRoomTypeModal(roomTypeData) {
        const modal = document.getElementById('roomTypeModal');
        const content = document.getElementById('roomTypeContent');

        content.innerHTML = `
        <h3 style="margin-bottom: 15px; color: #333;">${roomTypeData.type_name}</h3>
        <div style="margin-bottom: 10px;">
            <strong>Diện tích:</strong> ${roomTypeData.room_size || 'N/A'} m²
        </div>
        <div style="margin-bottom: 10px;">
            <strong>Giá cơ bản:</strong> ${roomTypeData.formatted_base_price || 'N/A'}
        </div>
        <div style="margin-bottom: 15px;">
            <strong>Tiện nghi:</strong>
            <div style="margin-top: 5px;">
                ${roomTypeData.amenities_list ? roomTypeData.amenities_list.map(amenity => 
                    `<span style="background: #f0f0f0; color: #333; padding: 3px 8px; border-radius: 15px; font-size: 12px; margin-right: 5px; margin-bottom: 5px; display: inline-block;">${amenity}</span>`
                ).join('') : 'Không có thông tin'}
            </div>
        </div>
    `;

        modal.style.display = 'block';
    }

    // Function to close room type modal
    function closeRoomTypeModal() {
        document.getElementById('roomTypeModal').style.display = 'none';
    }

    // Add click event to room type cells
    document.addEventListener('DOMContentLoaded', function() {
        // Make room type cells clickable
        document.querySelectorAll('.room-type-info').forEach(function(cell) {
            cell.style.cursor = 'pointer';
            cell.addEventListener('click', function() {
                // Extract room type data from the row
                const row = cell.closest('tr');
                // You would need to pass the room type data to the frontend
                // For now, this is a placeholder
            });
        });

        // Auto hide alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 300);
            }, 5000);
        });
    });

    // Other existing functions...
    function applyFilters() {
        document.getElementById('filterForm').submit();
    }

    function searchRooms(query) {
        // Implement AJAX search if needed
        // For now, using form submission
        if (query.length >= 2 || query.length === 0) {
            setTimeout(function() {
                document.getElementById('filterForm').submit();
            }, 500);
        }
    }
</script>

<style>
    .room-type-info {
        min-width: 150px;
    }

    .amenities-preview {
        max-width: 150px;
    }

    .discount-badge {
        font-weight: bold;
        text-transform: uppercase;
    }

    .price-info {
        min-width: 100px;
    }

    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .status-active {
        background-color: #d4edda;
        color: #155724;
    }

    .status-inactive {
        background-color: #f8d7da;
        color: #721c24;
    }

    .guest-info,
    .bed-info {
        white-space: nowrap;
    }

    .action-buttons {
        white-space: nowrap;
    }

    .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
        margin-right: 2px;
    }

    .table-responsive {
        overflow-x: hidden;
    }

    .data-table {
        min-width: 1200px;
    }

    .amenity-tag {
        white-space: nowrap;
    }

    /* Badge for trash count */
    .badge {
        position: relative;
        top: -2px;
    }

    /* Alert animation */
    .alert {
        transition: opacity 0.3s ease;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .table-actions {
            flex-direction: column;
            gap: 10px;
        }

        .filter-bar {
            flex-direction: column;
        }

        .filter-group {
            margin-bottom: 10px;
        }
    }
</style>

@endsection