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

<!-- Filter Status Bar -->
<div class="filter-container" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
    <div class="filter-header" style="display: flex; align-items: center; margin-bottom: 15px;">
        <i class="fas fa-filter" style="margin-right: 8px; color: #666;"></i>
        <span style="font-weight: 500; color: #333;">Lọc theo trạng thái:</span>
    </div>

    <div class="status-filters" style="display: flex; gap: 12px; flex-wrap: wrap;">
        <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}"
            class="filter-btn {{ request('status') === null ? 'active' : '' }}"
            style="padding: 8px 16px; border: 2px solid #e9ecef; border-radius: 20px; text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.3s; background: {{ request('status') === null ? '#007bff' : 'white' }}; color: {{ request('status') === null ? 'white' : '#666' }};">
            <i class="fas fa-list"></i> Tất cả ({{ $bookings->count() }})
        </a>

        <a href="{{ request()->fullUrlWithQuery(['status' => '0']) }}"
            class="filter-btn {{ request('status') === '0' ? 'active' : '' }}"
            style="padding: 8px 16px; border: 2px solid #ffc107; border-radius: 20px; text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.3s; background: {{ request('status') === '0' ? '#ffc107' : 'white' }}; color: {{ request('status') === '0' ? 'white' : '#856404' }};">
            <i class="fas fa-credit-card"></i> Chờ thanh toán ({{ $statusCounts[0] ?? 0 }})
        </a>

        <a href="{{ request()->fullUrlWithQuery(['status' => '1']) }}"
            class="filter-btn {{ request('status') === '1' ? 'active' : '' }}"
            style="padding: 8px 16px; border: 2px solid #17a2b8; border-radius: 20px; text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.3s; background: {{ request('status') === '1' ? '#17a2b8' : 'white' }}; color: {{ request('status') === '1' ? 'white' : '#0c5460' }};">
            <i class="fas fa-hourglass-half"></i> Chờ xử lý ({{ $statusCounts[1] ?? 0 }})
        </a>

        <a href="{{ request()->fullUrlWithQuery(['status' => '2']) }}"
            class="filter-btn {{ request('status') === '2' ? 'active' : '' }}"
            style="padding: 8px 16px; border: 2px solid #fd7e14; border-radius: 20px; text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.3s; background: {{ request('status') === '2' ? '#fd7e14' : 'white' }}; color: {{ request('status') === '2' ? 'white' : '#d63384' }};">
            <i class="fas fa-key"></i> Chờ nhận phòng ({{ $statusCounts[2] ?? 0 }})
        </a>

        <a href="{{ request()->fullUrlWithQuery(['status' => '3']) }}"
            class="filter-btn {{ request('status') === '3' ? 'active' : '' }}"
            style="padding: 8px 16px; border: 2px solid #e83e8c; border-radius: 20px; text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.3s; background: {{ request('status') === '3' ? '#e83e8c' : 'white' }}; color: {{ request('status') === '3' ? 'white' : '#c2185b' }};">
            <i class="fas fa-clock"></i> Chờ trả phòng ({{ $statusCounts[3] ?? 0 }})
        </a>

        <a href="{{ request()->fullUrlWithQuery(['status' => '4']) }}"
            class="filter-btn {{ request('status') === '4' ? 'active' : '' }}"
            style="padding: 8px 16px; border: 2px solid #28a745; border-radius: 20px; text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.3s; background: {{ request('status') === '4' ? '#28a745' : 'white' }}; color: {{ request('status') === '4' ? 'white' : '#155724' }};">
            <i class="fas fa-check-circle"></i> Hoàn tất ({{ $statusCounts[4] ?? 0 }})
        </a>

        <a href="{{ request()->fullUrlWithQuery(['status' => 'cancelled']) }}"
            class="filter-btn {{ request('status') === 'cancelled' ? 'active' : '' }}"
            style="padding: 8px 16px; border: 2px solid #dc3545; border-radius: 20px; text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.3s; background: {{ request('status') === 'cancelled' ? '#dc3545' : 'white' }}; color: {{ request('status') === 'cancelled' ? 'white' : '#721c24' }};">
            <i class="fas fa-times-circle"></i> Đã hủy ({{ $statusCounts['cancelled'] ?? 0 }})
        </a>
    </div>
</div>

<!-- Booking Management Table -->
<div class="table-container">
    <!-- Table Header -->
    <div class="table-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h2 class="table-title">
            Danh sách đơn đặt phòng
            @if(request('status') !== null)
            - {{ $statusLabels[request('status')] ?? 'Không xác định' }}
            @endif
            (<span>{{ $filteredBookings->count() }}</span> đơn)
        </h2>

        <!-- Nút thêm đặt phòng -->
        <a href="{{ route('admin.bookings.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Đặt phòng cho khách
        </a>
    </div>


    <!-- Data Table -->
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <!-- <th>ID Đơn</th> -->
                    <th>Khách hàng</th>
                    <th>Loại phòng</th>
                    <th>Phòng được chọn</th>
                    <th>Ngày nhận/trả</th>
                    <th>Số đêm</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>TT Thanh toán</th>
                    <th>Ngày đặt</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($filteredBookings as $booking)
                <tr>
                    <td style="display: none;"><strong>{{ $booking->b_id }}</strong></td>
                    <td>
                        <div>
                            <strong>{{ $booking->user_name }}</strong><br>
                            <small style="color: #666;">{{ $booking->user_email }}</small>
                        </div>
                    </td>
                    <td>
                        <div>
                            <strong>{{ $booking->room_type_name }}</strong><br>
                            <small style="color: #666;">{{ $booking->formatted_base_price }}</small>
                        </div>
                    </td>
                    <td>
                        @if($booking->r_id_assigned)
                        <div style="display: flex; align-items: center;">
                            <i class="fas fa-door-open" style="color: #28a745; margin-right: 5px;"></i>
                            <div>
                                <strong>{{ $booking->assigned_room_name }}</strong><br>
                                <small style="color: #666;">Phòng {{ $booking->assigned_room_name }}</small>
                            </div>
                        </div>
                        @else
                        <div style="color: #ffc107; display: flex; align-items: center;">
                            <i class="fas fa-exclamation-triangle" style="margin-right: 5px;"></i>
                            <span><strong>Chưa chọn phòng</strong></span>
                        </div>
                        @endif
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
                        {{-- Status: 0=chờ thanh toán, 1=đã thanh toán chờ admin xử lý, 2=admin đã chọn phòng chờ admin xác nhận nhận phòng, 3=admin đã xác nhận nhận phòng chờ admin xác nhận trả phòng, 4=hoàn tất --}}
                        @if($booking->status == 0)
                        <span class="status-badge" style="background: #fff3cd; color: #856404; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                            <i class="fas fa-credit-card"></i> Chờ thanh toán
                        </span>
                        @elseif($booking->status == 1)
                        <span class="status-badge" style="background: #e3f2fd; color: #1565c0; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                            <i class="fas fa-hourglass-half"></i> Chờ xử lý
                        </span>
                        @elseif($booking->status == 2)
                        <span class="status-badge" style="background: #fff3e0; color: #ef6c00; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                            <i class="fas fa-key"></i> Chờ nhận phòng
                        </span>
                        @elseif($booking->status == 3)
                        <span class="status-badge" style="background: #fce4ec; color: #c2185b; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                            <i class="fas fa-clock"></i> Chờ trả phòng
                        </span>
                        @elseif($booking->status == 4)
                        <span class="status-badge" style="background: #d4edda; color: #155724; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                            <i class="fas fa-check-circle"></i> Hoàn tất
                        </span>
                        @else
                        <span class="status-badge" style="background: #f8d7da; color: #721c24; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                            <i class="fas fa-times-circle"></i> Đã hủy
                        </span>
                        @endif
                    </td>
                    <td>
                        <select class="payment-status-select" data-id="{{ $booking->b_id }}">
                            <option value="0" {{ $booking->payment_status == 0 ? 'selected' : '' }}>Chưa thanh toán</option>
                            <option value="1" {{ $booking->payment_status == 1 ? 'selected' : '' }}>Đã thanh toán</option>
                        </select>
                    </td>

                    <td>{{ $booking->formatted_created_at }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.bookings.view', $booking->b_id) }}"
                                class="btn btn-primary btn-sm" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>

                            {{-- Status = 1: Đã thanh toán, chờ admin xử lý (chọn phòng) --}}
                            @if($booking->status == 1)
                            <span class="btn btn-warning btn-sm" style="cursor: default;" title="Cần chọn phòng cho khách hàng">
                                <i class="fas fa-exclamation-triangle"></i> Chờ xử lý
                            </span>

                            <!-- Nút hủy đặt phòng -->


                            {{-- Status = 2: Admin đã chọn phòng, chờ admin xác nhận nhận phòng --}}
                            @elseif($booking->status == 2)
                            <form method="POST" action="{{ route('admin.bookings.confirm.checkin', $booking->b_id) }}"
                                style="display: inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm"
                                    title="Xác nhận khách hàng đã nhận phòng"
                                    onclick="return confirm('Xác nhận khách hàng đã nhận phòng {{ $booking->assigned_room_name }}?')">
                                    <i class="fas fa-door-open"></i> Xác nhận nhận phòng
                                </button>
                            </form>

                            <!-- Nút hủy đặt phòng -->
                            <a href="{{ route('admin.bookings.cancel.form', $booking->b_id) }}"
                                class="btn btn-danger btn-sm" title="Hủy đặt phòng">
                                <i class="fas fa-times"></i> Hủy
                            </a>

                            {{-- Status = 3: Admin đã xác nhận nhận phòng, chờ admin xác nhận trả phòng --}}
                            @elseif($booking->status == 3)
                            <form method="POST" action="{{ route('admin.bookings.confirm.checkout', $booking->b_id) }}"
                                style="display: inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-info btn-sm"
                                    title="Xác nhận khách đã trả phòng thành công"
                                    onclick="return confirm('Xác nhận khách hàng đã trả phòng thành công? Phòng sẽ được cập nhật trạng thái còn trống.')">
                                    <i class="fas fa-check-double"></i> Xác nhận trả phòng
                                </button>
                            </form>

                            {{-- Status = 4: Đã hoàn tất --}}
                            @elseif($booking->status == 4)
                            <button class="btn btn-secondary btn-sm" disabled title="Đã hoàn tất">
                                <i class="fas fa-check-circle"></i> Đã hoàn tất
                            </button>

                            {{-- Status = 0: Chờ thanh toán --}}
                            @elseif($booking->status == 0)
                            <button class="btn btn-outline-warning btn-sm" disabled title="Chờ khách thanh toán">
                                <i class="fas fa-credit-card"></i> Chờ thanh toán
                            </button>

                            {{-- Status khác: Đã hủy --}}
                            @else
                            <button class="btn btn-outline-secondary btn-sm" disabled title="Đã hủy">
                                <i class="fas fa-times-circle"></i> Đã hủy
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-calendar-times" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                        <span>Không có đơn đặt phòng nào {{ request('status') !== null ? 'với trạng thái này' : '' }}.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .filter-container .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

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

    .payment-status-select {
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #fff;
        font-size: 14px;
        color: #333;
        outline: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    /* Hover */
    .payment-status-select:hover {
        border-color: #007bff;
    }

    /* Khi focus */
    .payment-status-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.4);
    }

    /* Style riêng cho từng option */
    .payment-status-select option[value="0"] {
        color: #dc3545;
        /* đỏ: Chưa thanh toán */
        font-weight: bold;
    }

    .payment-status-select option[value="1"] {
        color: #28a745;
        /* xanh: Đã thanh toán */
        font-weight: bold;
    }

    .payment-status-select option[value="2"] {
        color: #ffc107;
        /* vàng: Hoàn tiền */
        font-weight: bold;
    }


    /* Responsive cho mobile */
    @media (max-width: 768px) {
        .status-filters {
            justify-content: center;
        }

        .filter-btn {
            font-size: 12px !important;
            padding: 6px 12px !important;
        }

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

    /* Highlight cho đơn chờ xử lý */
    tr:has(.status-badge:contains("Chờ xử lý")) {
        background-color: #fff8e1 !important;
    }

    /* Highlight cho đơn chờ nhận phòng */
    tr:has(.status-badge:contains("Chờ nhận phòng")) {
        background-color: #fff3e0 !important;
    }
</style>
<script>
    document.querySelectorAll('.payment-status-select').forEach(function(select) {
        select.addEventListener('change', function() {
            let b_id = this.dataset.id;
            let status = this.value;

            fetch("{{ route('admin.bookings.updatePaymentStatus') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        b_id: b_id,
                        payment_status: status
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert("✅ " + data.message);
                    } else {
                        alert("❌ " + data.message);
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Có lỗi xảy ra khi cập nhật!");
                });
        });
    });
</script>

@endsection