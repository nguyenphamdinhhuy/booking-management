@extends('layout.app')

@section('content')
<div class="detail-container">
    <!-- Room Header -->
    <div class="detail-room-header">
        <h1 class="detail-room-title" id="room-name">{{ $room->name }}</h1>
        <div class="detail-room-meta">
            <div class="detail-rating">
                <div class="detail-stars">
                    @for ($i = 0; $i < floor($room->rating); $i++)
                        <i class="fas fa-star"></i>
                        @endfor
                        @if ($room->rating - floor($room->rating) >= 0.5)
                        <i class="fas fa-star-half-alt"></i>
                        @endif
                </div>
                <span>({{ $room->rating }}/5)</span>
            </div>
            <div class="detail-availability-badge" id="availability-status">
                @if ($room->available)
                <i class="fas fa-check-circle"></i> Còn trống
                @else
                <i class="fas fa-times-circle"></i> Hết phòng
                @endif
            </div>
        </div>
        <div class="detail-room-features">
            <div class="detail-feature">
                <i class="fas fa-users"></i>
                <span>Tối đa {{ $room->max_guests ?? '4' }} khách</span>
            </div>
            <div class="detail-feature">
                <i class="fas fa-bed"></i>
                <span>{{ $room->number_of_beds ?? '2' }} giường</span>
            </div>
            <div class="detail-feature">
                <i class="fas fa-wifi"></i>
                <span>WiFi miễn phí</span>
            </div>
            <div class="detail-feature">
                <i class="fas fa-car"></i>
                <span>Đỗ xe miễn phí</span>
            </div>
        </div>
    </div>

    <!-- Image Gallery -->
    <div class="detail-image-gallery">
        <div class="detail-single-image" id="room-image">
            <img src="{{ asset($room->images) }}" alt="{{ $room->name }}" style="width:100%; border-radius:12px;">
        </div>
    </div>

    <!-- Main Content -->
    <div class="detail-main-content">
        <!-- Room Details -->
        <div class="detail-room-details">
            <h2 class="detail-section-title">Mô tả phòng</h2>
            <div class="detail-description" id="room-description">
                {{ $room->description ?? 'Không có mô tả cho phòng này.' }}
            </div>

            <div class="detail-amenities">
                <h3 class="detail-section-title">Tiện nghi phòng</h3>
                <div class="detail-amenities-grid">
                    <div class="detail-amenity-item">
                        <i class="fas fa-wifi"></i><span>WiFi miễn phí</span>
                    </div>
                    <div class="detail-amenity-item">
                        <i class="fas fa-snowflake"></i><span>Điều hòa không khí</span>
                    </div>
                    <div class="detail-amenity-item">
                        <i class="fas fa-tv"></i><span>TV màn hình phẳng</span>
                    </div>
                    <div class="detail-amenity-item">
                        <i class="fas fa-bath"></i><span>Phòng tắm riêng</span>
                    </div>
                    <div class="detail-amenity-item">
                        <i class="fas fa-coffee"></i><span>Minibar</span>
                    </div>
                    <div class="detail-amenity-item">
                        <i class="fas fa-door-open"></i><span>Ban công</span>
                    </div>
                    <div class="detail-amenity-item">
                        <i class="fas fa-phone"></i><span>Điện thoại</span>
                    </div>
                    <div class="detail-amenity-item">
                        <i class="fas fa-car"></i><span>Đỗ xe miễn phí</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Card -->
        <div class="detail-booking-card">
            <div class="detail-price-section">
                <span class="detail-price" id="price-per-night">{{ $room->formatted_price }}</span>
                <span class="detail-price-unit">VNĐ/đêm</span>
            </div>

            <form class="detail-booking-form" method="GET" action="{{ route('payment') }}" onsubmit="return validateVoucher();">
                <div class="detail-form-group">
                    <label class="detail-form-label">Ngày nhận - trả phòng</label>
                    <div class="detail-date-inputs">
                        <input type="date" class="detail-form-input" id="checkin-date" name="checkin" required>
                        <input type="date" class="detail-form-input" id="checkout-date" name="checkout" required>
                    </div>
                </div>
                <div class="detail-form-group">
                    <label class="detail-form-label">Mã giảm giá</label>
                    <input type="text" class="detail-form-input" name="discount_code" id="discount-code" placeholder="Nhập mã giảm giá nếu có">
                    <span id="voucher-error" style="color: #e53935; font-size: 13px; display: none;"></span>
                </div>
                <input type="hidden" name="guests" id="guests-hidden" value="2">
                <input type="hidden" name="r_id" value="{{ $room->r_id }}">
                <input type="hidden" name="total_price" id="total-price-hidden" value="">
                <button type="submit" class="detail-book-btn">Đặt phòng ngay</button>
            </form>

            <p class="detail-booking-note">Không tính phí hủy trong 24h</p>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="detail-info-cards">
        <div class="detail-info-card">
            <h3><i class="fas fa-map-marker-alt"></i> Vị trí</h3>
            <p>{{ $room->location ?? 'Nằm ngay trung tâm thành phố, gần các điểm tham quan nổi tiếng.' }}</p>
        </div>
        <div class="detail-info-card">
            <h3><i class="fas fa-utensils"></i> Ăn uống</h3>
            <p>Nhà hàng phục vụ các món ăn địa phương và quốc tế. Room service 24/7.</p>
        </div>
        <div class="detail-info-card">
            <h3><i class="fas fa-swimming-pool"></i> Tiện ích</h3>
            <p>Hồ bơi ngoài trời, spa, phòng gym và các dịch vụ giải trí khác.</p>
        </div>
    </div>

    <!-- Related Rooms -->
    <div class="related-rooms-section">
        <h2 class="related-title">Phòng liên quan</h2>
        <div class="related-rooms-grid">
            @forelse ($relatedRooms as $item)
            <div class="related-room-card">
                <img src="{{ asset($item->images) }}" alt="{{ $item->name }}" class="related-room-image">
                <div class="related-room-info">
                    <h4 class="related-room-name">{{ $item->name }}</h4>
                    <p class="related-room-price">{{ $item->formatted_price }}</p>
                    <p class="related-room-rating">Đánh giá: {{ $item->rating }}/5</p>
                    <a href="{{ route('rooms_detail', $item->r_id) }}" class="related-room-button">Xem chi tiết</a>
                </div>
            </div>
            @empty
            <p>Không có phòng liên quan.</p>
            @endforelse
        </div>
    </div>

</div>
<script>
    // Tính tổng giá trị đơn hàng khi submit (giả sử đơn giá * số đêm)
    document.querySelector('.detail-booking-form').onsubmit = function(e) {
        const checkin = document.getElementById('checkin-date').value;
        const checkout = document.getElementById('checkout-date').value;
        const pricePerNight = {
            {
                $room - > price_per_night
            }
        };
        if (checkin && checkout) {
            const nights = (new Date(checkout) - new Date(checkin)) / (1000 * 60 * 60 * 24);
            document.getElementById('total-price-hidden').value = nights > 0 ? nights * pricePerNight : pricePerNight;
        }
    };

    function validateVoucher() {
        var code = document.getElementById('discount-code').value.trim();
        if (code.length > 0) {
            // Có thể kiểm tra định dạng mã ở đây nếu muốn
            // Nếu muốn kiểm tra mã hợp lệ ngay tại client, cần AJAX, còn không thì để backend xử lý
            // Ở đây chỉ reset lỗi
            document.getElementById('voucher-error').style.display = 'none';
        }
        return true;
    }
</script>
@endsection