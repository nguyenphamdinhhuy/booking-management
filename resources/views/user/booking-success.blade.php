{{-- resources/views/user/booking-success.blade.php --}}
@extends('layouts.app')

@section('title', 'Đặt phòng thành công')

@section('content')

<div class="booking-wrapper">
    <div class="payment-header">
        <div class="header-top">
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="logo-text">BookingPay</div>
            </div>
            <div class="security-badge">
                <i class="fas fa-shield-alt"></i>
                <span>Bảo mật SSL</span>
            </div>
        </div>

        <div class="progress-bar">
            <div class="progress-fill" style="width: 100%"></div>
        </div>

        <div class="step-indicator">
            <div class="step completed">
                <i class="fas fa-check-circle"></i>
                <span>Chọn phòng</span>
            </div>
            <div class="step completed">
                <i class="fas fa-check-circle"></i>
                <span>Thông tin</span>
            </div>
            <div class="step active">
                <i class="fas fa-credit-card"></i>
                <span>Thanh toán</span>
            </div>
            <div class="step">
                <i class="fas fa-check-circle"></i>
                <span>Hoàn tất</span>
            </div>
        </div>
    </div>

    <div class="booking-container">
        <div class="booking-box">
            <div class="booking-header">
                <div class="booking-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 class="booking-title">Đặt phòng thành công!</h2>
                <p class="booking-message">
                    Cảm ơn bạn đã đặt phòng tại hệ thống của chúng tôi.<br>
                    Thông tin đặt phòng đã được gửi về email của bạn.
                </p>
            </div>

            <div class="booking-section">
                <h3 class="booking-subtitle">
                    <i class="fas fa-receipt"></i> Chi tiết đặt phòng
                </h3>

                <div class="booking-details">
                    <div class="booking-info">
                        <h4 class="booking-info-title">Thông tin đơn hàng</h4>
                        <div class="booking-info-list">
                            <div class="booking-info-item"><span>Mã đơn:</span> <strong>#{{ $booking->b_id }}</strong></div>
                            <div class="booking-info-item"><span>Khách hàng:</span> {{ $user->name ?? 'N/A' }}</div>
                            <div class="booking-info-item"><span>Email:</span> {{ $user->email ?? 'N/A' }}</div>
                            <div class="booking-info-item"><span>Trạng thái:</span> <span class="booking-status">Đã thanh toán</span></div>
                        </div>
                    </div>

                    <div class="booking-info">
                        <h4 class="booking-info-title">Thông tin phòng</h4>
                        <div class="booking-info-list">
                            <div class="booking-info-item"><span>Tên phòng:</span> {{ $room->name ?? 'N/A' }}</div>
                            <div class="booking-info-item"><span>Giá/đêm:</span> {{ $room->formatted_price ?? 'N/A' }}</div>
                            <div class="booking-info-item"><span>Số khách:</span> {{ $details->guests ?? 'N/A' }} người</div>
                            <div class="booking-info-item"><span>Số đêm:</span> {{ $booking->nights ?? 1 }} đêm</div>
                        </div>
                    </div>
                </div>

                <div class="booking-dates">
                    <div class="booking-date-box">
                        <i class="fas fa-calendar-check booking-date-icon"></i>
                        <div>
                            <div class="booking-date-label">Ngày nhận phòng</div>
                            <div class="booking-date-value">{{ $booking->formatted_check_in ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="booking-date-box">
                        <i class="fas fa-calendar-times booking-date-icon"></i>
                        <div>
                            <div class="booking-date-label">Ngày trả phòng</div>
                            <div class="booking-date-value">{{ $booking->formatted_check_out ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <div class="booking-summary">
                    <h4 class="booking-info-title">Tổng kết thanh toán</h4>
                    <div class="booking-info-list">
                        <div class="booking-info-item">
                            <span>Giá phòng ({{ $booking->nights ?? 1 }} đêm):</span>
                            {{ number_format(($room->price_per_night ?? 0) * ($booking->nights ?? 1), 0, ',', '.') }} VND
                        </div>
                        @if($voucher)
                        <div class="booking-info-item">
                            <span>Mã giảm giá ({{ $voucher->v_code ?? '' }}):</span>
                            -{{ number_format($details->discount_amount ?? 0, 0, ',', '.') }} VND
                        </div>
                        @endif
                        <div class="booking-info-item booking-total">
                            <span>Tổng thanh toán:</span>
                            <strong>{{ $booking->formatted_total_price }}</strong>
                        </div>
                    </div>
                </div>

                @if($room && $room->images)
                <div class="booking-room-preview">
                    <h4 class="booking-info-title">Hình ảnh phòng</h4>
                    <div class="booking-room-box">
                        <img src="{{ asset($room->images) }}" class="booking-room-image" alt="{{ $room->name }}">
                        <div class="booking-room-details">
                            <h5>{{ $room->name }}</h5>
                            <p>{{ $room->description ?? 'Không có mô tả' }}</p>
                            <div class="booking-room-tags">
                                <span><i class="fas fa-users"></i> {{ $room->max_guests ?? 'N/A' }} khách</span>
                                <span><i class="fas fa-bed"></i> {{ $room->number_beds ?? 'N/A' }} giường</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="booking-actions">
                    <a href="{{ route('index') }}" class="booking-btn booking-btn-primary">
                        <i class="fas fa-home"></i> Về trang chủ
                    </a>
                    <a href="{{ route('booking.history', ['userId' => Auth::id()]) }}" class="booking-btn booking-btn-secondary">
                        <i class="fas fa-history"></i> Lịch sử đặt phòng
                    </a>
                </div>

                <div class="booking-contact">
                    <h4 class="booking-info-title">Cần hỗ trợ?</h4>
                    <p><i class="fas fa-phone"></i> Hotline: <strong>1900 1234</strong></p>
                    <p><i class="fas fa-envelope"></i> Email: <strong>support@hotel.com</strong></p>
                    <p><i class="fas fa-info-circle"></i> Chúng tôi sẽ liên hệ trước ngày nhận phòng để xác nhận thông tin.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection