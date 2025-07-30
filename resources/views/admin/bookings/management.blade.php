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
    <i class="fas fa-calendar-check"></i>
    Quản lý Đơn đặt phòng
</h1>

<!-- Booking Management Table -->
<div class="table-container">
    <!-- Table Header -->
    <div class="table-header">
        <h2 class="table-title">Danh sách đơn đặt phòng (<span>{{ $bookings->count() }}</span> đơn)</h2>
    </div>

    <!-- Data Table -->
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID Đơn</th>
                    <th>Khách hàng</th>
                    <th>Phòng</th>
                    <th>Ngày nhận/trả</th>
                    <th>Số đêm</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr>
                    <td><strong>{{ $booking->b_id }}</strong></td>
                    <td>
                        <div>
                            <strong>{{ $booking->user_name }}</strong><br>
                            <small style="color: #666;">{{ $booking->user_email }}</small>
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center;">
                            <div>
                                <strong>{{ $booking->room_name }}</strong><br>
                                <small style="color: #666;">{{ $booking->formatted_price_per_night }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <strong>{{ $booking->formatted_check_in }}</strong><br>
                            <small style="color: #666;">đến {{ $booking->formatted_check_out }}</small>
                        </div>
                    </td>
                    <td><strong>{{ $booking->nights }} đêm</strong></td>
                    <td><strong style="color: #e74c3c;">{{ $booking->formatted_total_price }}</strong></td>
                    <td>
                        @if($booking->status_text == 'pending')
                        <span class="status-badge" style="background: #e3f2fd; color: #1565c0; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                            <i class="fas fa-hourglass-half"></i> Chờ xác nhận
                        </span>
                        @elseif($booking->status_text == 'confirmed')
                        <span class="status-badge" style="background: #fff3cd; color: #856404; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                            <i class="fas fa-clock"></i> Chờ trả phòng
                        </span>
                        @elseif($booking->status_text == 'completed')
                        <span class="status-badge" style="background: #d4edda; color: #155724; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                            <i class="fas fa-check-circle"></i> Hoàn tất
                        </span>
                        @elseif($booking->status_text == 'cancelled')
                        <span class="status-badge" style="background: #f8d7da; color: #721c24; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                            <i class="fas fa-times-circle"></i> Đã hủy
                        </span>
                        @endif
                    </td>
                    <td>{{ $booking->formatted_created_at }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.bookings.view', $booking->b_id) }}"
                                class="btn btn-primary btn-sm" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>

                            @if($booking->status_text == 'pending')
                            <form method="POST" action="{{ route('admin.bookings.confirm', $booking->b_id) }}"
                                style="display: inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-info btn-sm"
                                    title="Xác nhận đơn đặt phòng"
                                    onclick="return confirm('Xác nhận đơn đặt phòng này?')">
                                    <i class="fas fa-check"></i> Xác nhận đơn
                                </button>
                            </form>
                            @elseif($booking->status_text == 'confirmed')
                            <form method="POST" action="{{ route('admin.bookings.confirm.checkout', $booking->b_id) }}"
                                style="display: inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm"
                                    title="Xác nhận trả phòng thành công"
                                    onclick="return confirm('Xác nhận khách hàng đã trả phòng thành công?')">
                                    <i class="fas fa-check"></i> Xác nhận trả phòng
                                </button>
                            </form>
                            @elseif($booking->status_text == 'completed')
                            <button class="btn btn-secondary btn-sm" disabled title="Đã hoàn tất">
                                <i class="fas fa-check-double"></i> Đã hoàn tất
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-calendar-times" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                        <span>Chưa có đơn đặt phòng nào.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Thêm styles cho status badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-weight: 500;
        white-space: nowrap;
    }

    .action-buttons {
        display: flex;
        gap: 5px;
        align-items: center;
        flex-wrap: wrap;
    }

    .action-buttons .btn {
        white-space: nowrap;
    }

    /* Responsive cho mobile */
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
            gap: 2px;
        }

        .action-buttons .btn {
            width: 100%;
            font-size: 11px;
            padding: 4px 6px;
        }
    }
</style>

@endsection