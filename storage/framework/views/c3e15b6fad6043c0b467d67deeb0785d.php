<?php $__env->startSection('title', 'Dịch vụ'); ?>

<?php $__env->startSection('content'); ?>
<div class="sr-container mx-auto px-4 py-8">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Dịch vụ của chúng tôi</h1>
        <p class="text-lg text-gray-600">Khám phá các dịch vụ chất lượng cao tại khách sạn</p>
    </div>

    <div class="sr-search-filter-section">
        <form method="GET" action="<?php echo e(route('user.services.index')); ?>" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Tìm kiếm dịch vụ..."
                    class="sr-search-input w-full">
            </div>
            <div class="min-w-48">
                <select name="category_id" class="sr-filter-select w-full">
                    <option value="">Tất cả danh mục</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category->id); ?>" <?php echo e(request('category_id') == $category->id ? 'selected' : ''); ?>>
                        <?php echo e($category->name); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <button type="submit" class="sr-search-btn">
                <i class="fas fa-search mr-2"></i>Tìm kiếm
            </button>
        </form>
    </div>

    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Danh mục dịch vụ</h2>
        <div class="sr-category-grid">
            <div class="sr-category-item <?php echo e(!request('category_id') ? 'active' : ''); ?>" data-category-id="">
                <i class="fas fa-th-large"></i>
                <p class="text-sm font-medium">Tất cả</p>
            </div>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="sr-category-item <?php echo e(request('category_id') == $category->id ? 'active' : ''); ?>"
                data-category-id="<?php echo e($category->id); ?>">
                <i class="fas fa-tag"></i>
                <p class="text-sm font-medium"><?php echo e($category->name); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <?php if($services->count() > 0): ?>
    <div class="sr-services-grid">
        <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="sr-service-card">
            <div class="sr-service-image">
                <?php if($service->image): ?>
                <img src="<?php echo e(asset($service->image)); ?>" alt="<?php echo e($service->name); ?>" class="w-full h-48 object-cover">
                <?php else: ?>
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-image text-4xl text-gray-400"></i>
                </div>
                <?php endif; ?>
                <div class="sr-service-status">Có sẵn</div>
            </div>

            <div class="p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="sr-category-pill"><?php echo e($service->category->name); ?></span>
                    <span class="sr-service-price"><?php echo e(number_format($service->price)); ?> VNĐ</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2"><?php echo e($service->name); ?></h3>
                <?php if($service->description): ?>
                <p class="text-gray-600 text-sm mb-3 sr-line-clamp-2"><?php echo e($service->description); ?></p>
                <?php endif; ?>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Đơn vị: <?php echo e($service->unit); ?></span>
                    <a href="<?php echo e(route('user.services.show', $service->s_id)); ?>"
                        class="sr-action-btn sr-action-btn-primary text-sm">
                        Chi tiết
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="mt-8">
        <?php echo e($services->links()); ?>

    </div>
    <?php else: ?>
    <div class="sr-empty-state text-center py-12">
        <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">Không tìm thấy dịch vụ</h3>
        <p class="text-gray-500">Hãy thử tìm kiếm với từ khóa khác hoặc chọn danh mục khác</p>
    </div>
    <?php endif; ?>
</div>
<style>
    /*== GLOBAL CONTAINER & LAYOUT ==*/
    .sr-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 2rem 1rem;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /*== SEARCH & FILTER SECTION ==*/
    .sr-search-filter-section {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .sr-search-input,
    .sr-filter-select {
        padding: 0.75rem 1rem;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
        font-size: 1rem;
        background-color: #fff;
        transition: border 0.3s;
    }

    .sr-search-input:focus,
    .sr-filter-select:focus {
        border-color: #3498db;
        outline: none;
    }

    .sr-search-btn {
        background-color: #3498db;
        color: #fff;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.3s;
    }

    .sr-search-btn:hover {
        background-color: #2980b9;
    }

    /*== CATEGORY TABS ==*/
    .sr-category-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .sr-category-item {
        background-color: #f5f5f5;
        padding: 0.75rem 1.25rem;
        border-radius: 9999px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: background 0.3s, transform 0.3s;
    }

    .sr-category-item.active,
    .sr-category-item:hover {
        background-color: #3498db;
        color: #fff;
        transform: scale(1.05);
    }

    /*== SERVICE GRID & CARDS ==*/
    .sr-services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
    }

    .sr-service-card {
        background-color: #fff;
        border-radius: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .sr-service-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .sr-service-image {
        position: relative;
        height: 12rem;
        overflow: hidden;
    }

    .sr-service-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .sr-service-status {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background-color: #2ecc71;
        color: #fff;
        padding: 0.3rem 0.75rem;
        font-size: 0.75rem;
        border-radius: 0.5rem;
        font-weight: 600;
    }

    .sr-category-pill {
        background-color: #ecf0f1;
        color: #2c3e50;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .sr-service-price {
        font-weight: bold;
        color: #e67e22;
    }

    .sr-action-btn {
        padding: 0.4rem 0.8rem;
        border-radius: 0.5rem;
        font-weight: 600;
        text-decoration: none;
    }

    .sr-action-btn-primary {
        background-color: #3498db;
        color: #fff;
        transition: background 0.3s;
    }

    .sr-action-btn-primary:hover {
        background-color: #2980b9;
    }

    /*== EMPTY STATE ==*/
    .sr-empty-state i {
        color: #bdc3c7;
    }

    .sr-empty-state h3 {
        color: #7f8c8d;
        font-size: 1.25rem;
    }

    /*== LOADING SPINNER ==*/
    .loading-spinner {
        width: 1rem;
        height: 1rem;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
        display: inline-block;
        vertical-align: middle;
        margin-right: 0.5rem;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /*== TEXT CLAMP ==*/
    .sr-line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoryItems = document.querySelectorAll('.sr-category-item');

        categoryItems.forEach(item => {
            item.addEventListener('click', function() {
                const categoryId = this.dataset.categoryId;

                if (categoryId) {
                    window.location.href = `/services/category/${categoryId}`;
                } else {
                    window.location.href = '/services';
                }
            });
        });

        const searchForm = document.querySelector('.sr-search-filter-section form');
        const searchInput = document.querySelector('input[name="search"]');
        const categorySelect = document.querySelector('select[name="category_id"]');

        if (searchForm) {
            categorySelect?.addEventListener('change', function() {
                searchForm.submit();
            });

            let searchTimeout;
            searchInput?.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    searchForm.submit();
                }, 500);
            });

            searchForm.addEventListener('submit', function() {
                const submitBtn = searchForm.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<span class="loading-spinner"></span> Đang tìm...';
                    submitBtn.disabled = true;
                }
            });
        }

        const serviceCards = document.querySelectorAll('.sr-service-card');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1
        });

        serviceCards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app\resources\views/user/services/index.blade.php ENDPATH**/ ?>