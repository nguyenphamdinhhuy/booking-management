@extends('layouts.app')

@section('title', 'Đánh giá của tôi')

@section('content')
    <div class="my-reviews-container">
        <div class="my-reviews-header">
            <h1 class="my-reviews-title">Đánh giá của tôi</h1>
            <p class="my-reviews-subtitle">Xem và quản lý tất cả đánh giá bạn đã viết</p>
        </div>

        <!-- Danh sách đánh giá -->
        <div class="my-reviews-list">
            @if($reviews->count() > 0)
                @foreach($reviews as $review)
                    <div class="review-item" data-review-id="{{ $review->rv_id }}">
                        <div class="review-header">
                            <div class="review-info">
                                <div class="review-date">
                                    <i class="fas fa-calendar"></i>
                                    {{ \Carbon\Carbon::parse($review->created_at)->format('d/m/Y H:i') }}
                                </div>
                                <div class="review-rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="star {{ $i <= $review->rating ? 'filled' : 'empty' }}">★</span>
                                    @endfor
                                    <span class="rating-value">{{ $review->rating }}/5</span>
                                </div>
                            </div>

                            <div class="review-actions">
                                <button class="btn btn-sm btn-outline-primary edit-review-btn"
                                    onclick="editReview({{ $review->rv_id }}, {{ $review->rating }}, '{{ addslashes($review->comments) }}')">
                                    <i class="fas fa-edit"></i> Sửa
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-review-btn"
                                    onclick="deleteReview({{ $review->rv_id }})">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </div>
                        </div>

                        <div class="review-content">
                            <p class="review-comment">{{ $review->comments }}</p>
                        </div>

                        <!-- Form chỉnh sửa (ẩn mặc định) -->
                        <div class="edit-form" id="edit-form-{{ $review->rv_id }}" style="display: none;">
                            <form action="{{ route('reviews.update', $review->rv_id) }}" method="POST" class="edit-review-form">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label class="form-label">Đánh giá sao</label>
                                    <div class="rating-input">
                                        <input type="radio" name="rating" value="5" id="edit-star5-{{ $review->rv_id }}" {{ $review->rating == 5 ? 'checked' : '' }}>
                                        <label for="edit-star5-{{ $review->rv_id }}" class="star-label">★</label>

                                        <input type="radio" name="rating" value="4" id="edit-star4-{{ $review->rv_id }}" {{ $review->rating == 4 ? 'checked' : '' }}>
                                        <label for="edit-star4-{{ $review->rv_id }}" class="star-label">★</label>

                                        <input type="radio" name="rating" value="3" id="edit-star3-{{ $review->rv_id }}" {{ $review->rating == 3 ? 'checked' : '' }}>
                                        <label for="edit-star3-{{ $review->rv_id }}" class="star-label">★</label>

                                        <input type="radio" name="rating" value="2" id="edit-star2-{{ $review->rv_id }}" {{ $review->rating == 2 ? 'checked' : '' }}>
                                        <label for="edit-star2-{{ $review->rv_id }}" class="star-label">★</label>

                                        <input type="radio" name="rating" value="1" id="edit-star1-{{ $review->rv_id }}" {{ $review->rating == 1 ? 'checked' : '' }}>
                                        <label for="edit-star1-{{ $review->rv_id }}" class="star-label">★</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="edit-comments-{{ $review->rv_id }}" class="form-label">Nội dung đánh giá</label>
                                    <textarea name="comments" id="edit-comments-{{ $review->rv_id }}" class="form-textarea" rows="4"
                                        required>{{ $review->comments }}</textarea>
                                </div>

                                <div class="edit-actions">
                                    <button type="button" class="btn btn-secondary cancel-edit-btn"
                                        onclick="cancelEdit({{ $review->rv_id }})">Hủy</button>
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="no-reviews">
                    <div class="no-reviews-icon">📝</div>
                    <h3 class="no-reviews-title">Bạn chưa có đánh giá nào</h3>
                    <p class="no-reviews-text">Hãy đặt phòng và trả phòng để có thể đánh giá!</p>
                    <a href="{{ route('all_rooms') }}" class="btn btn-primary">Đặt phòng ngay</a>
                </div>
            @endif
        </div>

        <!-- Nút quay lại -->
        <div class="back-button">
            <a href="{{ route('home') }}" class="btn btn-secondary">Quay lại trang chủ</a>
        </div>
    </div>

    <style>
        .my-reviews-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .my-reviews-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .my-reviews-title {
            font-size: 2rem;
            color: #333;
            margin-bottom: 10px;
        }

        .my-reviews-subtitle {
            color: #666;
            font-size: 1.1rem;
        }

        .my-reviews-list {
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
            flex-wrap: wrap;
            gap: 15px;
        }

        .review-info {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .review-date {
            color: #666;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .review-rating {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .star {
            margin: 0 2px;
            transition: color 0.3s;
        }

        .star.filled {
            color: #ffc107;
        }

        .star.empty {
            color: #ddd;
        }

        .rating-value {
            font-weight: 600;
            color: #007bff;
            font-size: 1.1rem;
        }

        .review-actions {
            display: flex;
            gap: 10px;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.9rem;
        }

        .btn-outline-primary {
            background: transparent;
            color: #007bff;
            border: 1px solid #007bff;
        }

        .btn-outline-primary:hover {
            background: #007bff;
            color: white;
        }

        .btn-outline-danger {
            background: transparent;
            color: #dc3545;
            border: 1px solid #dc3545;
        }

        .btn-outline-danger:hover {
            background: #dc3545;
            color: white;
        }

        .review-content {
            border-top: 1px solid #e9ecef;
            padding-top: 15px;
            margin-bottom: 20px;
        }

        .review-comment {
            color: #333;
            line-height: 1.6;
            margin: 0;
            font-size: 1rem;
        }

        .edit-form {
            border-top: 1px solid #e9ecef;
            padding-top: 20px;
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 20px;
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
            font-size: 1.5rem;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }

        .star-label:hover,
        .star-label:hover~.star-label,
        .rating-input input[type="radio"]:checked~.star-label {
            color: #ffc107;
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

        .edit-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
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
            margin-bottom: 20px;
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

        @media (max-width: 768px) {
            .my-reviews-container {
                padding: 15px;
            }

            .review-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .review-actions {
                width: 100%;
                justify-content: flex-end;
            }

            .edit-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>

    <script>
        function editReview(reviewId, rating, comments) {
            // Ẩn tất cả form edit khác
            document.querySelectorAll('.edit-form').forEach(form => {
                form.style.display = 'none';
            });

            // Hiển thị form edit của review này
            const editForm = document.getElementById(`edit-form-${reviewId}`);
            editForm.style.display = 'block';

            // Scroll đến form
            editForm.scrollIntoView({ behavior: 'smooth' });
        }

        function cancelEdit(reviewId) {
            const editForm = document.getElementById(`edit-form-${reviewId}`);
            editForm.style.display = 'none';
        }

        function deleteReview(reviewId) {
            if (confirm('Bạn có chắc chắn muốn xóa đánh giá này?')) {
                // Tạo form để gửi DELETE request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/reviews/${reviewId}`;

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = '{{ csrf_token() }}';

                form.appendChild(methodInput);
                form.appendChild(tokenInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Xử lý rating cho form edit
        document.addEventListener('DOMContentLoaded', function () {
            // Xử lý rating cho tất cả form edit
            document.querySelectorAll('.rating-input').forEach(ratingInput => {
                const starLabels = ratingInput.querySelectorAll('.star-label');

                starLabels.forEach((label, index) => {
                    label.addEventListener('click', function () {
                        // Reset tất cả stars
                        starLabels.forEach(star => star.style.color = '#ddd');

                        // Đánh dấu stars được chọn
                        for (let i = 0; i <= index; i++) {
                            starLabels[starLabels.length - 1 - i].style.color = '#ffc107';
                        }
                    });
                });
            });
        });
    </script>
@endsection