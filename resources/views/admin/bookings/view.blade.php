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

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
    <h1 class="page-title">
        <i class="fas fa-file-alt"></i>
        Chi tiết đơn đặt phòng #{{ $booking->b_id }}
    </h1>
    <a href="{{ route('admin.bookings.management') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="row">
    <!-- Thông tin đơn đặt phòng -->
    <div class="col-md-8">
        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">Thông tin đơn đặt phòng</h2>
                <div class="table-actions">
                    @if($booking->status_text == 'confirmed')
                    <span class="status-badge" style="background: #fff3cd; color: #856404; padding: 8px 12px; border-radius: 4px; margin-right: 10px;">
                        <i class="fas fa-clock"></i> Chờ trả phòng
                    </span>
                    @elseif($booking->status_text == 'completed')
                    <span class="status-badge" style="background: #d4edda; color: #155724; padding: 8px 12px; border-radius: 4px; margin-right: 10px;">
                        <i class="fas fa-check-circle"></i> Hoàn tất
                    </span>
                    @elseif($booking->status_text == 'cancelled')
                    <span class="status-badge" style="background: #f8d7da; color: #721c24; padding: 8px 12px; border-radius: 4px; margin-right: 10px;">
                        <i class="fas fa-times-circle"></i> Đã hủy
                    </span>
                    @endif
                </div>
            </div>

            <div style="padding: 20px;">
                <div class="row">
                    <div class="col-md-6">
                        <h4 style="color: #333; margin-bottom: 15px;">
                            <i class="fas fa-info-circle"></i> Thông tin cơ bản
                        </h4>
                        <table style="width: 100%; margin-bottom: 20px;">
                            <tr>
                                <td style="padding: 8px 0; font-weight: bold; width: 140px;">Mã đơn:</td>
                                <td style="padding: 8px 0;">{{ $booking->b_id }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: bold;">Ngày nhận phòng:</td>
                                <td style="padding: 8px 0;">{{ $booking->formatted_check_in }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: bold;">Ngày trả phòng:</td>
                                <td style="padding: 8px 0;">{{ $booking->formatted_check_out }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: bold;">Số đêm:</td>
                                <td style="padding: 8px 0;">{{ $booking->nights }} đêm</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: bold;">Ngày đặt:</td>
                                <td style="padding: 8px 0;">{{ $booking->formatted_created_at }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h4 style="color: #333; margin-bottom: 15px;">
                            <i class="fas fa-dollar-sign"></i> Thông tin thanh toán
                        </h4>
                        <table style="width: 100%;">
                            <tr>
                                <td style="padding: 8px 0; font-weight: bold; width: 140px;">Giá phòng/đêm:</td>
                                <td style="padding: 8px 0;">{{ $booking->formatted_price_per_night }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: bold;">Tổng tiền:</td>
                                <td style="padding: 8px 0; color: #e74c3c; font-size: 18px; font-weight: bold;">
                                    {{ $booking->formatted_total_price }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: bold;">Trạng thái thanh toán:</td>
                                <td style="padding: 8px 0;">
                                    @if($booking->payment_status == 1)
                                    <span style="color: #27ae60; font-weight: bold;">
                                        <i class="fas fa-check-circle"></i> Đã thanh toán
                                    </span>
                                    @else
                                    <span style="color: #e74c3c; font-weight: bold;">
                                        <i class="fas fa-times-circle"></i> Chưa thanh toán
                                    </span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($booking->booking_description)
                <div style="margin-top: 20px;">
                    <h4 style="color: #333; margin-bottom: 10px;">
                        <i class="fas fa-comment"></i> Ghi chú từ khách hàng
                    </h4>
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 4px; border-left: 4px solid #007bff;">
                        {{ $booking->booking_description }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Thông tin khách hàng và phòng -->
    <div class="col-md-4">
        <!-- Thông tin khách hàng -->
        <div class="table-container" style="margin-bottom: 20px;">
            <div class="table-header">
                <h2 class="table-title">Thông tin khách hàng</h2>
            </div>
            <div style="padding: 20px;">
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Tên:</td>
                        <td style="padding: 8px 0;">{{ $booking->user_name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Email:</td>
                        <td style="padding: 8px 0;">{{ $booking->user_email }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Thông tin phòng -->
        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">Thông tin phòng</h2>
            </div>
            <div style="padding: 20px;">
                @if(isset($booking->room_image_urls) && count($booking->room_image_urls) > 0)
                <div style="margin-bottom: 15px;">
                    <img src="{{ asset($booking->room_images) }}" alt="{{ $booking->room_name }}"
                        style="width: 100%; height: 400px; object-fit: cover; border-radius: 4px;">
                </div>
                @endif

                <h4 style="color: #333; margin-bottom: 10px;">{{ $booking->room_name }}</h4>

                <table style="width: 100%; margin-bottom: 15px;">
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold;">Số khách tối đa:</td>
                        <td style="padding: 6px 0;">{{ $booking->max_guests }} khách</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold;">Số giường:</td>
                        <td style="padding: 6px 0;">{{ $booking->number_beds }} giường</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold;">Giá/đêm:</td>
                        <td style="padding: 6px 0; color: #e74c3c; font-weight: bold;">
                            {{ $booking->formatted_price_per_night }}
                        </td>
                    </tr>
                </table>

                @if($booking->room_description)
                <div>
                    <strong>Mô tả phòng:</strong>
                    <p style="margin-top: 8px; color: #666; line-height: 1.5;">
                        {{ $booking->room_description }}
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Action buttons -->
<div style="margin-top: 30px; text-align: center;">
    @if($booking->status_text == 'confirmed')
    <form method="POST" action="{{ route('admin.bookings.confirm.checkout', $booking->b_id) }}" style="display: inline-block;">
        @csrf
        <button type="submit" class="btn btn-success" style="font-size: 16px; padding: 12px 24px;"
            onclick="return confirm('Xác nhận khách hàng đã trả phòng thành công? Hành động này không thể hoàn tác.')">
            <i class="fas fa-check"></i> Xác nhận trả phòng thành công
        </button>
    </form>
    @elseif($booking->status_text == 'completed')
    <div class="alert alert-success" style="display: inline-block; margin: 0;">
        <i class="fas fa-check-double"></i> Đơn đặt phòng đã hoàn tất
    </div>
    @elseif($booking->status_text == 'cancelled')
    <div class="alert alert-danger" style="display: inline-block; margin: 0;">
        <i class="fas fa-times-circle"></i> Đơn đặt phòng đã bị hủy
    </div>
    @endif
</div>
<style>

</style>

@endsection