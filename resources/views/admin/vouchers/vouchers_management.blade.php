@extends('admin.layouts.master')

@section("content")

{{-- Thông báo --}}
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
    <i class="fas fa-ticket-alt"></i> Quản lý Voucher
</h1>

<!-- Quản lý voucher -->
<div class="table-container">

    <!-- Header -->
    <div class="table-header">
        <h2 class="table-title">Danh sách voucher (<span id="voucherCount">{{ $vouchers->count() }}</span> mã)</h2>
        <div class="table-actions">
            <a href="{{ route('vouchers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm voucher mới
            </a>
        </div>
    </div>

    <!-- Bộ lọc -->
    <form id="filterForm" method="GET" action="{{ route('vouchers.management') }}">
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
                <label class="filter-label">Tìm kiếm</label>
                <input type="text" name="search" class="filter-input" placeholder="Mã voucher, mô tả..."
                    value="{{ request('search') }}" onkeyup="searchVouchers(this.value)">
            </div>
            <div class="filter-group" style="align-items: end;">
                <button type="button" class="btn btn-secondary" onclick="clearFilters()">
                    <i class="fas fa-times"></i> Xóa bộ lọc
                </button>
            </div>
        </div>
    </form>

    <!-- Loading -->
    <div id="loadingIndicator" style="display: none; text-align: center; padding: 20px;">
        <i class="fas fa-spinner fa-spin"></i> Đang tìm kiếm...
    </div>

    <!-- Bảng dữ liệu -->
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Mã voucher</th>
                    <th>Phần trăm giảm</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Trạng thái</th>
                    <th>Mô tả</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="voucherTableBody">
                @forelse($vouchers as $voucher)
                <tr>
                    <td>{{ $voucher->v_id }}</td>
                    <td><strong>{{ $voucher->v_code }}</strong></td>
                    <td>{{ $voucher->discount_percent }}%</td>
                    <td>{{ \Carbon\Carbon::parse($voucher->start_date)->format('d/m/Y H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($voucher->end_date)->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($voucher->status == 1)
                        <span class="status-badge status-active">Hoạt động</span>
                        @else
                        <span class="status-badge status-inactive">Không hoạt động</span>
                        @endif
                    </td>
                    <td>
                        {{ $voucher->description ? Str::limit($voucher->description, 50) : 'Không có mô tả' }}
                    </td>
                    <td>
                        <div class="action-buttons">

                            <a href="{{ route('vouchers.edit', $voucher->v_id) }}" class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('vouchers.delete', $voucher->v_id) }}" class="btn btn-danger btn-sm" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa voucher này?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="noDataRow">
                    <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-ticket-alt" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                        <span id="noDataMessage">
                            @if(request()->hasAny(['status', 'search']))
                            Không tìm thấy voucher phù hợp với tiêu chí lọc.
                            @else
                            Chưa có voucher nào được tạo.
                            @endif
                        </span>
                        <br><br>
                        @if(!request()->hasAny(['status', 'search']))
                        <a href="{{ route('vouchers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm voucher đầu tiên
                        </a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="routeContainer" data-url="{{ route('vouchers.management') }}"></div>
@endsection