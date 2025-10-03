@extends('layout.app')

@section('content')

{{-- Alerts --}}
@if(session('success'))
<div class="custom-alert custom-alert-success" id="alert-success">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="custom-alert custom-alert-error" id="alert-error">
    <i class="fas fa-times-circle"></i> {{ session('error') }}
</div>
@endif

@if($errors->any())
<div class="custom-alert custom-alert-error" id="alert-validate">
    <i class="fas fa-exclamation-triangle"></i>
    <ul style="margin: 0; padding-left: 20px;">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- Hero --}}
<section class="page-hero">
    <div class="page-hero-content">
        <h1>Tất cả phòng nghỉ</h1>
        <p>Khám phá toàn bộ các lựa chọn phòng nghỉ tuyệt vời của chúng tôi</p>
    </div>
</section>

{{-- Filters --}}
<section class="filter-section">
    <div class="filter-container">
        <h3>Bộ lọc</h3>

        <form method="GET" action="{{ route('all_rooms') }}" class="filter-options" style="gap: 16px; align-items: flex-end;">
            {{-- Trạng thái --}}
            <div class="filter-group">
                <label>Trạng thái phòng:</label>
                <select name="availability" class="sort-select" id="availabilitySelect">
                    @php $availability = request('availability', 'all'); @endphp
                    <option value="all" {{ $availability==='all' ? 'selected' : '' }}>Tất cả</option>
                    <option value="available" {{ $availability==='available' ? 'selected' : '' }}>Còn trống</option>
                    <option value="unavailable" {{ $availability==='unavailable' ? 'selected' : '' }}>Hết phòng</option>
                </select>
            </div>

            {{-- Ngày --}}
            <div class="filter-group">
                <label>Ngày nhận phòng</label>
                <input type="date" name="check_in_date" class="sort-select" value="{{ request('check_in_date') }}">
            </div>
            <div class="filter-group">
                <label>Ngày trả phòng</label>
                <input type="date" name="check_out_date" class="sort-select" value="{{ request('check_out_date') }}">
            </div>

            {{-- Số khách --}}
            <div class="filter-group">
                <label>Số khách</label>
                <input type="number" name="guests" min="1" class="sort-select" value="{{ request('guests') }}" placeholder="VD: 2">
            </div>

            {{-- Giá --}}
            <div class="filter-group">
                <label>Giá từ</label>
                <input type="number" name="min_price" min="0" class="sort-select" value="{{ request('min_price') }}" placeholder="0">
            </div>
            <div class="filter-group">
                <label>Đến</label>
                <input type="number" name="max_price" min="0" class="sort-select" value="{{ request('max_price') }}" placeholder="5000000">
            </div>

            {{-- Từ khóa --}}
            <div class="filter-group" style="min-width:260px;">
                <label>Từ khóa</label>
                <input type="text" name="search" class="sort-select" value="{{ request('search') }}" placeholder="Tên loại phòng, mô tả...">
            </div>

            {{-- Sắp xếp --}}
            <div class="filter-group">
                <label>Sắp xếp theo:</label>
                @php $sortBy = request('sort_by','price-asc'); @endphp
                <select name="sort_by" class="sort-select">
                    <option value="price-asc" {{ $sortBy==='price-asc'  ? 'selected' : '' }}>Giá thấp đến cao</option>
                    <option value="price-desc" {{ $sortBy==='price-desc' ? 'selected' : '' }}>Giá cao đến thấp</option>
                    <option value="rating-desc" {{ $sortBy==='rating-desc'? 'selected' : '' }}>Đánh giá cao nhất</option>
                    <option value="name-asc" {{ $sortBy==='name-asc'   ? 'selected' : '' }}>Tên A-Z</option>
                    <option value="popular" {{ $sortBy==='popular'    ? 'selected' : '' }}>Phổ biến</option>
                </select>
            </div>

            {{-- Per page --}}
            <div class="filter-group">
                <label>Hiển thị</label>
                @php $perPage = (int)request('per_page', 20); @endphp
                <select name="per_page" class="sort-select">
                    @foreach([12,20,24,36,48] as $pp)
                    <option value="{{ $pp }}" {{ $perPage===$pp ? 'selected' : '' }}>{{ $pp }}/trang</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group" style="gap:8px;">
                <button type="submit" class="filter-btn">Áp dụng</button>
                <a href="{{ route('all_rooms') }}" class="filter-btn" style="background:#e9ecef;color:#333;border-color:#e9ecef;">Xóa lọc</a>
            </div>
        </form>
    </div>
</section>

{{-- All Rooms (Room Types) --}}
<section class="rooms-section">
    <div class="rooms-container">
        <div class="rooms-header">
            <h2>Danh sách tất cả phòng nghỉ</h2>
            <div class="rooms-stats">
                @if(isset($stats))
                <p class="rooms-subtitle">
                    Tìm thấy {{ $stats['total_rooms'] ?? 0 }} loại phòng
                    ({{ $stats['available_rooms'] ?? 0 }} còn trống,
                    {{ $stats['unavailable_rooms'] ?? 0 }} hết phòng)
                </p>
                @else
                <p class="rooms-subtitle">Hiển thị tất cả các loại phòng đang hoạt động</p>
                @endif
            </div>
        </div>

        @if(isset($error))
        <div class="alert alert-error">
            {{ $error }}
        </div>
        @endif

        @php
        // Tự chọn route chi tiết: ưu tiên room_types.detail, fallback rooms_detail
        $detailRoute = \Illuminate\Support\Facades\Route::has('room_types.detail')
        ? 'room_types.detail' : 'rooms_detail';
        @endphp

        <div class="rooms-list">
            @if(isset($rooms) && $rooms->count() > 0)
            @foreach($rooms as $room)
            @php
            // ƯU TIÊN dùng $room->image_url nếu controller đã set
            $imageUrl = $room->image_url ?? null;

            // Nếu chưa có, tự chuẩn hoá nhanh từ $room->images (JSON / CSV / chuỗi / URL)
            if (!$imageUrl) {
            $candidate = null;
            if (!empty($room->images)) {
            $decoded = json_decode($room->images, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && count($decoded)) {
            $candidate = trim((string)$decoded[0]);
            } else {
            $candidate = strpos($room->images, ',') !== false
            ? trim(explode(',', $room->images)[0])
            : trim((string)$room->images);
            }
            }

            if ($candidate) {
            if (\Illuminate\Support\Str::startsWith($candidate, ['http://', 'https://'])) {
            $imageUrl = $candidate; // URL tuyệt đối
            } else {
            $imageUrl = asset($candidate); // đường dẫn tương đối trong public/
            }
            }
            }

            // Fallback cuối cùng
            $imageUrl = $imageUrl ?: asset('assets/images/default-room.jpg');

            // Rating & text
            $rating = $room->rating ?? 4.5;
            $ratingText = $rating >= 4.5 ? 'Tuyệt vời' : ($rating >= 4.0 ? 'Rất tốt' : ($rating >= 3.5 ? 'Tốt' : 'Khá tốt'));
            @endphp

            <a href="{{ route('room_types.show', ['id' => $room->r_id]) }}"
                class="room-card {{ !empty($room->available) && !$room->available ? 'room-unavailable' : '' }}">
                <div class="room-image">
                    <img src="{{ $imageUrl }}" alt="{{ $room->name }}"
                        onerror="this.onerror=null;this.src='{{ asset('assets/images/default-room.jpg') }}';">

                    <div class="room-status {{ !empty($room->available) ? ($room->available ? 'available' : 'unavailable') : 'available' }}">
                        {{ !empty($room->available) ? ($room->available ? 'Còn trống' : 'Hết phòng') : 'Còn trống' }}
                    </div>
                </div>

                <div class="room-info">
                    <div class="room-header">
                        <h3 class="room-name">{{ $room->name }}</h3>
                        <div class="room-rating">
                            <span class="rating-score">{{ number_format($rating, 1) }}</span>
                            <span class="rating-text">{{ $ratingText }}</span>
                        </div>
                    </div>

                    <div class="room-details">
                        <div class="room-specs">
                            @if(!empty($room->max_guests))
                            <span class="spec-item">
                                <i class="fas fa-user-friends"></i>
                                Tối đa {{ $room->max_guests }} khách
                            </span>
                            @endif
                            @if(!empty($room->number_beds))
                            <span class="spec-item">
                                <i class="fas fa-bed"></i>
                                {{ $room->number_beds }} giường
                            </span>
                            @endif
                            <span class="spec-item">
                                <i class="fas fa-wifi"></i>
                                WiFi miễn phí
                            </span>
                            <span class="spec-item">
                                <i class="fas fa-map-marker-alt"></i>
                                Việt Nam
                            </span>
                        </div>

                        @if(!empty($room->description))
                        <div class="room-description">
                            <p>{{ \Illuminate\Support\Str::limit($room->description, 110) }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="room-pricing">
                        <div class="price-info">
                            <span class="price-label">Giá mỗi đêm từ</span>
                            <div class="price-amount">
                                <div class="current-price">
                                    <span class="price">{{ number_format((int)$room->price_per_night, 0, ',', '.') }}</span>
                                    <span class="currency">VND</span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
            @else
            <div class="no-rooms">
                <div class="no-rooms-content">
                    <i class="fas fa-bed"></i>
                    <h3>Không có loại phòng nào</h3>
                    <p>Hiện tại không có loại phòng nào phù hợp điều kiện lọc.</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Pagination --}}
        @if(isset($rooms) && $rooms->hasPages())
        <div class="pagination-wrapper">
            {{-- withQueryString() đã set ở controller --}}
            {{ $rooms->links() }}
        </div>
        @endif

        {{-- Back to Home --}}
        <div class="back-to-home">
            <a href="{{ route('index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Quay lại trang chủ
            </a>
        </div>
    </div>
</section>

{{-- Styles --}}
<style>
    .custom-alert {
        padding: 12px 14px;
        border-radius: 8px;
        margin: 10px 0;
        display: flex;
        gap: 10px;
        align-items: center
    }

    .custom-alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb
    }

    .custom-alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb
    }

    .page-hero {
        background: linear-gradient(to right, #003580, #0071c2);
        color: #fff;
        padding: 60px 20px;
        text-align: center
    }

    .page-hero h1 {
        font-size: 42px;
        margin-bottom: 10px;
        font-weight: 700
    }

    .page-hero p {
        font-size: 18px;
        opacity: .9
    }

    .filter-section {
        background: #f5f5f5;
        padding: 30px 20px;
        border-bottom: 1px solid #ddd
    }

    .filter-container {
        max-width: 1200px;
        margin: 0 auto
    }

    .filter-container h3 {
        font-size: 20px;
        margin-bottom: 15px;
        color: #333
    }

    .filter-options {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        align-items: center
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 10px;
        min-width: 200px
    }

    .sort-select {
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        min-width: 180px;
        font-size: 15px;
        color: #333;
        background: #fff
    }

    .filter-btn {
        padding: 8px 14px;
        border: 1px solid #0071c2;
        background: #0071c2;
        color: #fff;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
        transition: .2s
    }

    .filter-btn:hover {
        opacity: .9
    }

    .rooms-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 24px
    }

    .rooms-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap
    }

    .rooms-subtitle {
        color: #6c757d;
        margin: 0
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
        box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
        text-decoration: none;
        color: inherit
    }

    .room-unavailable {
        pointer-events: none;
        opacity: .7
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

    .room-status.unavailable {
        background: #dc3545
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
        justify-content: flex-start;
        align-items: center;
        gap: 16px;
        border-top: 1px solid #f1f3f5;
        padding-top: 12px
    }

    .price-label {
        font-size: .85rem;
        color: #6c757d
    }

    .current-price .price {
        font-size: 1.25rem;
        font-weight: 700;
        margin-right: 6px
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

    @media (max-width: 768px) {
        .filter-options {
            flex-direction: column;
            align-items: flex-start
        }

        .page-hero h1 {
            font-size: 32px
        }

        .page-hero p {
            font-size: 16px
        }
    }
</style>

{{-- Scripts --}}
<script>
    // Auto-hide alerts
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.custom-alert').forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    });
</script>

@endsection