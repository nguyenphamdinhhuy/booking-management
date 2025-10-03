@extends('layouts.app')

@section('title', 'Đánh giá phòng')

@section('content')
    <div class="reviews-container">
        <div class="reviews-header">
            <h1 class="reviews-title">Đánh giá & Bình luận</h1>

            <!-- Rating tổng quan -->
            <div class="overall-rating">
                <div class="rating-summary">
                    <div class="rating-score">
                        <span class="score">{{ number_format($avgRating, 1) }}</span>
                        <span class="max-score">/5</span>
                    </div>
                    <div class="rating-stars">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($avgRating))
                                <span class="star filled">★</span>
                            @elseif ($i - $avgRating < 1 && $i - $avgRating > 0)
                                <span class="star half">★</span>
                            @else
                                <span class="star empty">★</span>
                            @endif
                        @endfor
                    </div>
                    <div class="total-reviews">
                        {{ $totalReviews }} đánh giá
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách đánh giá -->
        <div class="reviews-list">
            @if($reviews->count() > 0)
                @foreach($reviews as $review)
                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">
                                    <img src="{{ asset('images/default-avatar.png') }}" alt="Avatar" class="avatar-img">
                                </div>
                                <div class="reviewer-details">
                                    <div class="reviewer-name">{{ $review->customer->name ?? 'Khách hàng' }}</div>
                                    <div class="review-date">{{ \Carbon\Carbon::parse($review->created_at)->format('d/m/Y') }}</div>
                                </div>
                            </div>
                            <div class="review-rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="star {{ $i <= $review->rating ? 'filled' : 'empty' }}">★</span>
                                @endfor
                                <span class="rating-value">{{ $review->rating }}/5</span>
                            </div>
                        </div>

                        <div class="review-content">
                            <p class="review-comment">{{ $review->comments }}</p>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="no-reviews">
                    <div class="no-reviews-icon">💬</div>
                    <h3 class="no-reviews-title">Chưa có đánh giá nào</h3>
                    <p class="no-reviews-text">Hãy là người đầu tiên đánh giá phòng này!</p>
                </div>
            @endif
        </div>

        <!-- Nút quay lại -->
        <div class="back-button">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>

    <style>
        .reviews-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .reviews-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .reviews-title {
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
        }

        .overall-rating {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 25px;
            color: white;
            margin-bottom: 30px;
        }

        .rating-summary {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .rating-score {
            font-size: 3rem;
            font-weight: bold;
        }

        .max-score {
            font-size: 1.5rem;
            opacity: 0.8;
        }

        .rating-stars {
            font-size: 2rem;
        }

        .star {
            margin: 0 2px;
            transition: color 0.3s;
        }

        .star.filled {
            color: #ffc107;
        }

        .star.half {
            color: #ffc107;
            opacity: 0.7;
        }

        .star.empty {
            color: rgba(255, 255, 255, 0.3);
        }

        .total-reviews {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .reviews-list {
            margin-bottom: 30px;
        }

        .review-item {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #007bff;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .reviewer-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .reviewer-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #e9ecef;
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .reviewer-name {
            font-weight: 600;
            color: #333;
            font-size: 1.1rem;
        }

        .review-date {
            color: #666;
            font-size: 0.9rem;
        }

        .review-rating {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .rating-value {
            font-weight: 600;
            color: #007bff;
            font-size: 1.1rem;
        }

        .review-content {
            border-top: 1px solid #e9ecef;
            padding-top: 15px;
        }

        .review-comment {
            color: #333;
            line-height: 1.6;
            margin: 0;
            font-size: 1rem;
        }

        .no-reviews {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .no-reviews-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .no-reviews-title {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 10px;
        }

        .no-reviews-text {
            color: #666;
            font-size: 1.1rem;
        }

        .back-button {
            text-align: center;
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

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #545b62;
        }

        @media (max-width: 768px) {
            .reviews-container {
                padding: 15px;
            }

            .review-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .rating-score {
                font-size: 2.5rem;
            }

            .rating-stars {
                font-size: 1.5rem;
            }
        }
    </style>
@endsection