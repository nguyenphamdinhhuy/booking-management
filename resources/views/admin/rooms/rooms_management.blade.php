@extends('admin.layouts.master')
@section("content")

<h1 class="page-title">
    <i class="fas fa-bed"></i>
    Quản lý Phòng
</h1>

<!-- Room Management Table -->
<div class="table-container">
    <!-- Table Header with Actions -->
    <div class="table-header">
        <h2 class="table-title">Danh sách phòng</h2>
        <div class="table-actions">
            <button class="btn btn-primary" onclick="openAddRoomModal()">
                <i class="fas fa-plus"></i>
                Thêm phòng mới
            </button>
            
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
            <label class="filter-label">Số khách tối đa</label>
            <select class="filter-select" onchange="filterRooms()">
                <option value="">Tất cả</option>
                <option value="1">1 khách</option>
                <option value="2">2 khách</option>
                <option value="3">3 khách</option>
                <option value="4">4+ khách</option>
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label">Giá phòng</label>
            <select class="filter-select" onchange="filterRooms()">
                <option value="">Tất cả</option>
                <option value="0-500000">Dưới 500,000 VND</option>
                <option value="500000-1000000">500,000 - 1,000,000 VND</option>
                <option value="1000000-2000000">1,000,000 - 2,000,000 VND</option>
                <option value="2000000+">Trên 2,000,000 VND</option>
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label">Tìm kiếm</label>
            <input type="text" class="filter-input" placeholder="Tên phòng..." onkeyup="searchRooms(this.value)">
        </div>
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
                <tr>
                    <td>1</td>
                    <td>Deluxe Suite</td>
                    <td>
                        <div class="room-images">
                            <img src="https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=100&h=80&fit=crop"
                                alt="Room 1" class="room-image" onclick="showImageModal(this.src)">
                            <img src="https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=100&h=80&fit=crop"
                                alt="Room 1" class="room-image" onclick="showImageModal(this.src)">
                            <div class="more-images">+2</div>
                        </div>
                    </td>
                    <td><strong>1,500,000 VND</strong></td>
                    <td>4</td>
                    <td>2</td>
                    <td><span class="status-badge status-inactive">Inactive</span></td>
                    <td>Phòng suite cao cấp với view biển...</td>
                    <td>15/01/2024</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-primary btn-sm" onclick="viewRoom(1)" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-warning btn-sm" onclick="editRoom(1)" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteRoom(1)" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Standard Room</td>
                    <td>
                        <div class="room-images">
                            <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?w=100&h=80&fit=crop"
                                alt="Room 2" class="room-image" onclick="showImageModal(this.src)">
                            <img src="https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=100&h=80&fit=crop"
                                alt="Room 2" class="room-image" onclick="showImageModal(this.src)">
                        </div>
                    </td>
                    <td><strong>800,000 VND</strong></td>
                    <td>2</td>
                    <td>1</td>
                    <td><span class="status-badge status-active">Active</span></td>
                    <td>Phòng tiêu chuẩn tiện nghi đầy đủ...</td>
                    <td>10/01/2024</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-primary btn-sm" onclick="viewRoom(2)" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-warning btn-sm" onclick="editRoom(2)" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteRoom(2)" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection