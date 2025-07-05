@extends('admin.layouts.master')
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

    <h1 class="page-title">
        <i class="fas fa-bed"></i>
        Quản lý Phòng
    </h1>

    <!-- Room Management Table -->
    <div class="table-container">
        <!-- Table Header with Actions -->
        <div class="table-header">
            <h2 class="table-title">Danh sách phòng </h2>
            <div class="table-actions">
                <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Thêm phòng mới
                </a>
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
                            <td colspan="10" style="text-align: center; padding: 40px; color: #999;">
                                <i class="fas fa-bed" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                                Chưa có phòng nào được tạo.
                                <br><br>
                                <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Thêm phòng đầu tiên
                                </a>
                            </td>
                        </tr>
       
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal xem ảnh --}}
    <div id="imageModal"
        style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.8);"
        onclick="closeImageModal()">
        <div
            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); max-width: 90%; max-height: 90%;">
            <img id="modalImage" style="width: 100%; height: auto; border-radius: 8px;">
            <button onclick="closeImageModal()"
                style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.8); border: none; border-radius: 50%; width: 40px; height: 40px; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>



@endsection