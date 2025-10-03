@extends('layouts.app')

@section('title', 'Đánh giá đơn đặt phòng')

@section('content')
        <div class="review-container">
            <div class="review-header">
                <h1 class="review-title">Đánh giá đơn đặt phòng</h1>
                <p class="review-subtitle">Chia sẻ trải nghiệm của bạn về chuyến đi</p>
            </div>

            <div class="review-content">
                <!-- Thông tin đơn đặt phòng -->
                <div class="booking-info">
                    <h3 class="booking-info-title">Thông tin đơn đặt phòng</h3>
                                        <div class="room-card-1">
                                            <div class="room-image-dp">
                                                <img src="{{ asset(  $booking->images) }}" alt="Phòng {{ $booking->b_id }}">
                                                <span class="room-id">{{ $booking->room_name }}</span>
                                            </div>
                                        </div>
                    <div class="booking-details">
                        <div class="booking-detail-item">
                            <span class="detail-label">Ngày nhận phòng:</span>
                            <span
                                class="detail-value">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d/m/Y') }}</span>
                        </div>
                        <div class="booking-detail-item">
                            <span class="detail-label">Ngày trả phòng:</span>
                            <span
                                class="detail-value">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d/m/Y') }}</span>
                        </div>
                        <div class="booking-detail-item">
                            <span class="detail-label">Tổng tiền:</span>
                            <span class="detail-value">{{ number_format($booking->total_price, 0, ',', '.') }} VNĐ</span>
                        </div>
                    </div>
                </div>

                <!-- Form đánh giá -->
                <div class="review-form-container">
                    <h3 class="review-form-title">Đánh giá của bạn</h3>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="error-list">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('reviews.store', $booking->b_id) }}" method="POST" class="review-form">
                        @csrf

                        <!-- Rating -->
                        <div class="form-group">
                            <label class="form-label">Đánh giá sao *</label>
                            <div class="rating-input">
                                <input type="radio" name="rating" value="5" id="star5" {{ old('rating') == 5 ? 'checked' : '' }}
                                    required>
                                <label for="star5" class="star-label">★</label>

                                <input type="radio" name="rating" value="4" id="star4" {{ old('rating') == 4 ? 'checked' : '' }}
                                    required>
                                <label for="star4" class="star-label">★</label>

                                <input type="radio" name="rating" value="3" id="star3" {{ old('rating') == 3 ? 'checked' : '' }}
                                    required>
                                <label for="star3" class="star-label">★</label>

                                <input type="radio" name="rating" value="2" id="star2" {{ old('rating') == 2 ? 'checked' : '' }}
                                    required>
                                <label for="star2" class="star-label">★</label>

                                <input type="radio" name="rating" value="1" id="star1" {{ old('rating') == 1 ? 'checked' : '' }}
                                    required>
                                <label for="star1" class="star-label">★</label>
                            </div>
                            <div class="rating-description">
                                <span class="rating-text">Chọn số sao để đánh giá</span>
                            </div>
                        </div>

                        <!-- Comments -->
                        <div class="form-group">
                            <label for="comments" class="form-label">Nội dung đánh giá *</label>
                            <textarea name="comments" id="comments" class="form-textarea" rows="6"
                                placeholder="Chia sẻ trải nghiệm của bạn về phòng, dịch vụ, nhân viên... (tối thiểu 10 ký tự)"
                                required minlength="10" maxlength="1000">{{ old('comments') }}</textarea>
                            <div class="char-count">
                                <span class="current-count">0</span>/1000 ký tự
                            </div>
                        </div>

                        <!-- Submit buttons -->
                        <div class="form-actions">
                            <a href="{{ route('booking.history', ['userId' => auth()->id()]) }}"
                                class="btn btn-secondary">Hủy</a>
                            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <style>
            .room-card-1 {
        position: relative;
        display: inline-block;
    }

    .room-image-dp img {
        width: 100%;
        height: auto;
        border-radius: 10px;
        display: block;
    }

    .room-id {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(0, 0, 0, 0.6);
        color: #fff;
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: bold;
    }

            .review-container {
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }

            .review-header {
                text-align: center;
                margin-bottom: 30px;
            }

            .review-title {
                font-size: 2rem;
                color: #333;
                margin-bottom: 10px;
            }

            .review-subtitle {
                color: #666;
                font-size: 1.1rem;
            }

            .booking-info {
                background: #f8f9fa;
                border-radius: 10px;
                padding: 20px;
                margin-bottom: 30px;
            }

            .booking-info-title {
                font-size: 1.3rem;
                color: #333;
                margin-bottom: 15px;
                border-bottom: 2px solid #007bff;
                padding-bottom: 10px;
            }

            .booking-details {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 15px;
            }

            .booking-detail-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px;
                background: white;
                border-radius: 5px;
                border: 1px solid #e9ecef;
            }

            .detail-label {
                font-weight: 600;
                color: #555;
            }

            .detail-value {
                color: #333;
                font-weight: 500;
            }

            .review-form-container {
                background: white;
                border-radius: 10px;
                padding: 25px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .review-form-title {
                font-size: 1.3rem;
                color: #333;
                margin-bottom: 20px;
                border-bottom: 2px solid #28a745;
                padding-bottom: 10px;
            }

            .form-group {
                margin-bottom: 25px;
            }

            .form-label {
                display: block;
                font-weight: 600;
                color: #333;
                margin-bottom: 8px;
            }

            .rating-input {
                display: flex;
                flex-direction: row-reverse;
                gap: 5px;
                margin-bottom: 10px;
            }

            .rating-input input[type="radio"] {
                display: none;
            }

            .star-label {
                font-size: 2rem;
                color: #ddd;
                cursor: pointer;
                transition: color 0.2s;
            }

            .star-label:hover,
            .star-label:hover~.star-label,
            .rating-input input[type="radio"]:checked~.star-label {
                color: #ffc107;
            }

            .rating-description {
                text-align: center;
                color: #666;
                font-style: italic;
            }

            .form-textarea {
                width: 100%;
                padding: 12px;
                border: 2px solid #e9ecef;
                border-radius: 5px;
                font-size: 1rem;
                resize: vertical;
                transition: border-color 0.3s;
            }

            .form-textarea:focus {
                outline: none;
                border-color: #007bff;
            }

            .char-count {
                text-align: right;
                color: #666;
                font-size: 0.9rem;
                margin-top: 5px;
            }

            .current-count {
                color: #007bff;
                font-weight: 600;
            }

            .form-actions {
                display: flex;
                gap: 15px;
                justify-content: flex-end;
                margin-top: 30px;
            }

            .btn {
                padding: 12px 24px;
                border: none;
                border-radius: 5px;
                font-size: 1rem;
                font-weight: 500;
                cursor: pointer;
                text-decoration: none;
                display: inline-block;
                text-align: center;
                transition: all 0.3s;
            }

            .btn-primary {
                background: #007bff;
                color: white;
            }

            .btn-primary:hover {
                background: #0056b3;
            }

            .btn-secondary {
                background: #6c757d;
                color: white;
            }

            .btn-secondary:hover {
                background: #545b62;
            }

            .alert {
                padding: 15px;
                border-radius: 5px;
                margin-bottom: 20px;
            }

            .alert-danger {
                background: #f8d7da;
                border: 1px solid #f5c6cb;
                color: #721c24;
            }

            .alert-success {
                background: #d4edda;
                border: 1px solid #c3e6cb;
                color: #155724;
            }

            .error-list {
                margin: 0;
                padding-left: 20px;
            }

            @media (max-width: 768px) {
                .review-container {
                    padding: 15px;
                }

                .booking-details {
                    grid-template-columns: 1fr;
                }

                .form-actions {
                    flex-direction: column;
                }

                .btn {
                    width: 100%;
                }
            }
</style>@endsection