@extends('layout.app')

@section('content')

{{-- HERO --}}
<section class="page-hero">
    <div class="page-hero-content">
        <h1>Kết quả tìm kiếm</h1>
        <p>
            Từ {{ $stats['check_in_date'] }} đến {{ $stats['check_out_date'] }},
            {{ $stats['guests'] }} khách, {{ $stats['nights'] }} đêm nghỉ
        </p>
    </div>
</section>

<section class="rooms-section">
    <div class="rooms-container">
        <h2>Loại phòng khả dụng</h2>
        <p class="rooms-subtitle">Tìm thấy {{ $stats['total_rooms'] }} loại phòng còn trống</p>

        {{-- DANH SÁCH LOẠI PHÒNG --}}
        <div class="rooms-list">
            @if($roomTypes->count() > 0)
            @foreach($roomTypes as $rt)
            @php
            // Ảnh: nếu cột images là JSON array thì parse, nếu là chuỗi 1 ảnh thì dùng trực tiếp
            $firstImage = null;
            if (!empty($rt->images)) {
            $decoded = json_decode($rt->images, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && count($decoded)) {
            $firstImage = $decoded[0];
            } else {
            $firstImage = $rt->images; // có thể là path đơn
            }
            }
            // Tiện nghi: nếu là JSON array sẽ join, nếu là chuỗi phân cách bởi dấu phẩy thì explode
            $amenitiesText = '';
            if (!empty($rt->amenities ?? null)) {
            $am = json_decode($rt->amenities, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($am)) {
            $amenitiesText = implode(' • ', array_slice($am, 0, 4));
            } else {
            $amenitiesText = implode(' • ', array_slice(array_map('trim', explode(',', $rt->amenities)), 0, 4));
            }
            }
            // Giá hiển thị
            $pricePerNight = (int)($rt->price_per_night ?? 0);
            $rating = $rt->rating ?? 4.5;
            @endphp

            <div class="room-card">
                <a href="{{ route('room_types.show', ['id' => $rt->r_id]) }}" class="room-image-link">
                    <div class="room-image">
                        <img
                            src="{{ $firstImage ? asset($firstImage) : asset('assets/images/default-room.jpg') }}"
                            alt="{{ $rt->name }}">
                        <div class="room-status available">Còn trống</div>
                    </div>
                </a>

                <div class="room-info">
                    <div class="room-header">
                        <h3 class="room-name">
                            <a href="{{ route('room_types.show', ['id' => $rt->r_id]) }}">{{ $rt->name }}</a>
                        </h3>
                        <div class="room-rating">
                            <span class="rating-score">{{ number_format($rating, 1) }}</span>
                            <span class="rating-text">
                                @if($rating >= 4.5) Tuyệt vời
                                @elseif($rating >= 4.0) Rất tốt
                                @elseif($rating >= 3.5) Tốt
                                @else Khá tốt
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="room-details">
                        <div class="room-specs">
                            <span class="spec-item">
                                <i class="fas fa-user-friends"></i>
                                Tối đa {{ $rt->max_guests }} khách
                            </span>
                            <span class="spec-item">
                                <i class="fas fa-bed"></i>
                                {{ $rt->number_beds }} giường
                            </span>
                            @if(!empty($amenitiesText))
                            <span class="spec-item">
                                <i class="fas fa-concierge-bell"></i>
                                {{ $amenitiesText }}
                            </span>
                            @endif
                        </div>

                        @if(!empty($rt->description))
                        <div class="room-description">
                            <p>{{ \Illuminate\Support\Str::limit($rt->description, 140) }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="room-pricing">
                        <div class="price-info">
                            <span class="price-label">Giá mỗi đêm</span>
                            <div class="current-price">
                                <span class="price">{{ number_format($pricePerNight, 0, ',', '.') }}</span>
                                <span class="currency">VND</span>
                            </div>
                            @if(isset($stats['nights']) && $stats['nights'] > 0)
                            <div class="total-price">
                                <span class="hint">Ước tính {{ $stats['nights'] }} đêm:</span>
                                <strong>{{ number_format($pricePerNight * $stats['nights'], 0, ',', '.') }} VND</strong>
                            </div>
                            @endif
                        </div>

                        <div class="room-actions">
                            <a href="{{ route('room_types.show', ['id' => $rt->r_id]) }}"
                                class="btn btn-primary">
                                Xem chi tiết
                            </a>
                            {{-- Bạn có thể đưa thẳng đến trang “Đặt ngay” của loại phòng, truyền kèm checkin/checkout/guests --}}
                            <a href="{{ route('room_types.show', ['id' => $rt->r_id]) }}?checkin={{ $stats['check_in_date'] }}&checkout={{ $stats['check_out_date'] }}&guests={{ $stats['guests'] }}"
                                class="btn btn-outline">
                                Đặt ngay
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @else
            <div class="no-rooms">
                <div class="no-rooms-content">
                    <i class="fas fa-bed"></i>
                    <h3>Không tìm thấy loại phòng phù hợp</h3>
                    <p>Vui lòng điều chỉnh tiêu chí tìm kiếm và thử lại.</p>
                </div>
            </div>
            @endif
        </div>

        {{-- PHÂN TRANG --}}
        @if($roomTypes->hasPages())
        <div class="pagination-wrapper">
            {{ $roomTypes->appends(request()->input())->links() }}
        </div>
        @endif

        {{-- Quay lại trang chủ --}}
        <div class="back-to-home">
            <a href="{{ route('index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Quay lại trang chủ
            </a>
        </div>
    </div>
</section>

{{-- STYLE NHẸ CHO THẺ --}}
<style>
    .page-hero {
        padding: 40px 0;
        background: #f8f9fa;
        text-align: center
    }

    .page-hero h1 {
        margin: 0 0 8px
    }

    .rooms-container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 24px
    }

    .rooms-subtitle {
        color: #6c757d;
        margin-bottom: 16px
    }

    .rooms-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 16px
    }

    .room-card {
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .05)
    }

    .room-image {
        position: relative;
        height: 200px;
        overflow: hidden
    }

    .room-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .3s ease
    }

    .room-card:hover .room-image img {
        transform: scale(1.03)
    }

    .room-status {
        position: absolute;
        top: 12px;
        left: 12px;
        background: #28a745;
        color: #fff;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 12px
    }

    .room-info {
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px
    }

    .room-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px
    }

    .room-name {
        font-size: 1.05rem;
        margin: 0
    }

    .room-name a {
        color: #212529;
        text-decoration: none
    }

    .room-name a:hover {
        text-decoration: underline
    }

    .room-rating {
        display: flex;
        gap: 8px;
        align-items: center;
        color: #6c757d
    }

    .rating-score {
        font-weight: 700
    }

    .room-specs {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        font-size: .92rem;
        color: #495057
    }

    .spec-item i {
        margin-right: 6px
    }

    .room-description p {
        color: #6c757d;
        margin: 0
    }

    .room-pricing {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        border-top: 1px solid #f1f3f5;
        padding-top: 12px;
        flex-wrap: wrap;
    }

    .price-info .price-label {
        font-size: .85rem;
        color: #6c757d
    }

    .current-price {
        display: flex;
        align-items: baseline;
        gap: 6px
    }

    .current-price .price {
        font-size: 1.25rem;
        font-weight: 700
    }

    .total-price {
        font-size: .9rem;
        color: #495057;
        margin-top: 6px
    }

    .total-price .hint {
        margin-right: 6px;
        color: #6c757d
    }

    .room-actions {
        display: flex;
        gap: 8px
    }

    .btn {
        padding: 8px 12px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid transparent
    }

    .btn-primary {
        background: #007bff;
        color: #fff
    }

    .btn-primary:hover {
        background: #0056b3
    }

    .btn-outline {
        background: transparent;
        color: #495057;
        border-color: #dee2e6
    }

    .btn-outline:hover {
        background: #f8f9fa
    }

    .no-rooms {
        background: #fff;
        border: 1px dashed #dee2e6;
        border-radius: 12px;
        padding: 40px;
        text-align: center
    }

    .no-rooms i {
        font-size: 48px;
        color: #adb5bd;
        margin-bottom: 10px;
        display: block
    }

    .pagination-wrapper {
        margin-top: 20px;
        display: flex;
        justify-content: center
    }

    .back-to-home {
        margin-top: 20px;
        text-align: center
    }

    .back-btn {
        display: inline-flex;
        gap: 8px;
        align-items: center;
        border: 1px solid #dee2e6;
        padding: 10px 16px;
        border-radius: 8px;
        color: #495057;
        text-decoration: none
    }

    .back-btn:hover {
        background: #f8f9fa
    }
</style>
@endsection