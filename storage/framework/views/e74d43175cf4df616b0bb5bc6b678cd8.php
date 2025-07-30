

<?php $__env->startSection('content'); ?>
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-header-content">
                <h1>Phòng yêu thích</h1>
                <p>Quản lý danh sách các phòng khách sạn bạn đã yêu thích</p>
            </div>
        </div>

        <div class="profile-content">
            <div class="profile-card">
                <div class="card-header">
                    <h2><i class="fas fa-heart"></i> Danh sách yêu thích</h2>
                </div>

                <div class="favorites-content">
                    <?php if(empty($favorites)): ?>
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h3>Chưa có phòng yêu thích nào</h3>
                            <p>Bạn chưa thêm phòng nào vào danh sách yêu thích. Hãy khám phá và lưu lại những phòng bạn thích!
                            </p>
                            <a href="<?php echo e(route('home')); ?>" class="btn btn-primary">
                                <i class="fas fa-search"></i> Khám phá phòng
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="favorites-grid">
                            <?php $__currentLoopData = $favorites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $favorite): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="favorite-item">
                                    <div class="favorite-image">
                                        <img src="<?php echo e($favorite->room->image); ?>" alt="<?php echo e($favorite->room->name); ?>">
                                        <div class="favorite-overlay">
                                            <button class="remove-favorite" data-id="<?php echo e($favorite->id); ?>">
                                                <i class="fas fa-heart-broken"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="favorite-info">
                                        <h4><?php echo e($favorite->room->name); ?></h4>
                                        <p class="room-description"><?php echo e(Str::limit($favorite->room->description, 100)); ?></p>

                                        <div class="room-features">
                                            <span><i class="fas fa-bed"></i> <?php echo e($favorite->room->bed_type); ?></span>
                                            <span><i class="fas fa-users"></i> <?php echo e($favorite->room->capacity); ?> người</span>
                                            <span><i class="fas fa-ruler-combined"></i> <?php echo e($favorite->room->size); ?>m²</span>
                                        </div>

                                        <div class="room-rating">
                                            <div class="stars">
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star <?php echo e($i <= $favorite->room->rating ? 'filled' : ''); ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                            <span class="rating-text"><?php echo e($favorite->room->rating); ?>/5</span>
                                        </div>

                                        <div class="favorite-actions">
                                            <div class="price">
                                                <span class="currency">₫</span>
                                                <span class="amount"><?php echo e(number_format($favorite->room->price)); ?></span>
                                                <span class="per-night">/đêm</span>
                                            </div>
                                            <a href="<?php echo e(route('rooms_detail', $favorite->room->id)); ?>" class="btn btn-primary">
                                                <i class="fas fa-eye"></i> Xem chi tiết
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <style>
        .profile-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .profile-header {
            background: linear-gradient(135deg, #003580 0%, #004a9e 100%);
            color: white;
            padding: 40px 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            text-align: center;
        }

        .profile-header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .profile-header p {
            font-size: 1rem;
            opacity: 0.9;
        }

        .profile-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-header {
            background: #f8f9fa;
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .card-header h2 {
            margin: 0;
            font-size: 1.25rem;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header i {
            color: #003580;
        }

        .favorites-content {
            padding: 30px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #666;
            margin-bottom: 30px;
        }

        .favorites-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }

        .favorite-item {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            background: white;
        }

        .favorite-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .favorite-image {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .favorite-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .favorite-item:hover .favorite-image img {
            transform: scale(1.05);
        }

        .favorite-overlay {
            position: absolute;
            top: 10px;
            right: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .favorite-item:hover .favorite-overlay {
            opacity: 1;
        }

        .remove-favorite {
            background: rgba(220, 53, 69, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .remove-favorite:hover {
            background: #dc3545;
            transform: scale(1.1);
        }

        .favorite-info {
            padding: 20px;
        }

        .favorite-info h4 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .room-description {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .room-features {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .room-features span {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.8rem;
            color: #888;
        }

        .room-features i {
            color: #003580;
        }

        .room-rating {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .stars {
            display: flex;
            gap: 2px;
        }

        .stars i {
            font-size: 0.9rem;
            color: #ddd;
        }

        .stars i.filled {
            color: #ffc107;
        }

        .rating-text {
            font-size: 0.9rem;
            color: #666;
            font-weight: 500;
        }

        .favorite-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price {
            display: flex;
            align-items: baseline;
            gap: 2px;
        }

        .currency {
            font-size: 0.9rem;
            color: #666;
        }

        .amount {
            font-size: 1.3rem;
            font-weight: 600;
            color: #003580;
        }

        .per-night {
            font-size: 0.8rem;
            color: #666;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #003580;
            color: white;
        }

        .btn-primary:hover {
            background: #002855;
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .profile-container {
                padding: 15px;
            }

            .profile-header {
                padding: 30px 15px;
            }

            .profile-header h1 {
                font-size: 1.5rem;
            }

            .favorites-content {
                padding: 20px;
            }

            .favorites-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .favorite-actions {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .room-features {
                gap: 10px;
            }
        }

        @media (max-width: 480px) {
            .favorite-info {
                padding: 15px;
            }

            .favorite-image {
                height: 150px;
            }

            .room-features {
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Xử lý xóa khỏi yêu thích
            const removeButtons = document.querySelectorAll('.remove-favorite');

            removeButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const favoriteId = this.getAttribute('data-id');

                    if (confirm('Bạn có chắc muốn xóa phòng này khỏi danh sách yêu thích?')) {
                        // TODO: Gửi request xóa favorite
                        console.log('Remove favorite:', favoriteId);

                        // Tạm thời xóa khỏi UI
                        this.closest('.favorite-item').remove();
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\booking-management\resources\views/profile/favorites.blade.php ENDPATH**/ ?>