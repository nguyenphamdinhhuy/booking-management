@extends('layouts.app')

@section('title', 'Dịch vụ')

@section('content')
    <div class="sr-container mx-auto px-4 py-8">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Dịch vụ của chúng tôi</h1>
            <p class="text-lg text-gray-600">Khám phá các dịch vụ chất lượng cao tại khách sạn</p>
        </div>

        <div class="sr-search-filter-section">
            <form method="GET" action="{{ route('user.services.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm dịch vụ..."
                        class="sr-search-input w-full">
                </div>
                <div class="min-w-48">
                    <select name="category_id" class="sr-filter-select w-full">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
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
                <div class="sr-category-item {{ !request('category_id') ? 'active' : '' }}" data-category-id="">
                    <i class="fas fa-th-large"></i>
                    <p class="text-sm font-medium">Tất cả</p>
                </div>
                @foreach($categories as $category)
                    <div class="sr-category-item {{ request('category_id') == $category->id ? 'active' : '' }}"
                        data-category-id="{{ $category->id }}">
                        <i class="fas fa-tag"></i>
                        <p class="text-sm font-medium">{{ $category->name }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        @if($services->count() > 0)
            <div class="sr-services-grid">
                @foreach($services as $service)
                    <div class="sr-service-card">
                        <div class="sr-service-image">
                            @if($service->image)
                                <img src="{{ asset($service->image) }}" alt="{{ $service->name }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-image text-4xl text-gray-400"></i>
                                </div>
                            @endif
                            <div class="sr-service-status">Có sẵn</div>
                        </div>

                        <div class="p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="sr-category-pill">{{ $service->category->name }}</span>
                                <span class="sr-service-price">{{ number_format($service->price) }} VNĐ</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $service->name }}</h3>
                            @if($service->description)
                                <p class="text-gray-600 text-sm mb-3 sr-line-clamp-2">{{ $service->description }}</p>
                            @endif
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Đơn vị: {{ $service->unit }}</span>
                                <a href="{{ route('user.services.show', $service->s_id) }}"
                                    class="sr-action-btn sr-action-btn-primary text-sm">
                                    Chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $services->links() }}
            </div>
        @else
            <div class="sr-empty-state text-center py-12">
                <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Không tìm thấy dịch vụ</h3>
                <p class="text-gray-500">Hãy thử tìm kiếm với từ khóa khác hoặc chọn danh mục khác</p>
            </div>
        @endif
    </div>

    <style>
        /* User Services Styles */

        /* Service Card Hover Effects */
        .sr-service-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .sr-service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .sr-service-image {
            position: relative;
            overflow: hidden;
        }

        .sr-service-image img {
            transition: transform 0.3s ease;
        }

        .sr-service-card:hover .sr-service-image img {
            transform: scale(1.05);
        }

        /* Service Status Badge */
        .sr-service-status {
            position: absolute;
            top: 12px;
            right: 12px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Service Price */
        .sr-service-price {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 18px;
            display: inline-block;
        }

        /* Category Pills */
        .sr-category-pill {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .sr-category-pill:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
        }

        /* Search and Filter Section */
        .sr-search-filter-section {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 32px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .sr-search-input {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .sr-search-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .sr-filter-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s ease;
            font-size: 16px;
            background: white;
        }

        .sr-filter-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .sr-search-btn {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .sr-search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(59, 130, 246, 0.3);
        }

        /* Category Grid */
        .sr-category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }

        .sr-category-item {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .sr-category-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .sr-category-item.active {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
        }

        .sr-category-item i {
            font-size: 32px;
            color: #3b82f6;
            margin-bottom: 8px;
            display: block;
        }

        /* Service Grid */
        .sr-services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        /* Action Buttons */
        .sr-action-btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
        }

        .sr-action-btn-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .sr-action-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(59, 130, 246, 0.3);
        }

        /* Empty State */
        .sr-empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .sr-empty-state i {
            font-size: 80px;
            color: #d1d5db;
            margin-bottom: 24px;
        }

        .sr-empty-state h3 {
            color: #6b7280;
            font-size: 24px;
            margin-bottom: 12px;
        }

        .sr-empty-state p {
            color: #9ca3af;
            margin-bottom: 24px;
        }

        /* Line Clamp */
        .sr-line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

                /* Loading Animation */
        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sr-services-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            
            .sr-category-grid {
                grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
                gap: 12px;
            }
            
            .sr-search-filter-section {
                padding: 16px;
            }
            
            .sr-action-btn {
                width: 100%;
                margin-bottom: 12px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Category filter functionality
            const categoryItems = document.querySelectorAll('.sr-category-item');

            categoryItems.forEach(item => {
                item.addEventListener('click', function () {
                    const categoryId = this.dataset.categoryId;

                    if (categoryId) {
                        // Navigate to category page
                        window.location.href = `/services/category/${categoryId}`;
                    } else {
                        // Navigate to all services page
                        window.location.href = '/services';
                    }
                });
            });

            // Search and filter functionality
            const searchForm = document.querySelector('.sr-search-filter-section form');
            const searchInput = document.querySelector('input[name="search"]');
            const categorySelect = document.querySelector('select[name="category_id"]');

            if (searchForm) {
                // Auto-submit on category change
                categorySelect?.addEventListener('change', function () {
                    searchForm.submit();
                });

                // Debounced search
                let searchTimeout;
                searchInput?.addEventListener('input', function () {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        searchForm.submit();
                    }, 500);
                });

                // Show loading state
                searchForm.addEventListener('submit', function () {
                    const submitBtn = searchForm.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<span class="loading-spinner"></span> Đang tìm...';
                        submitBtn.disabled = true;
                    }
                });
            }

            // Service card animations
            const serviceCards = document.querySelectorAll('.sr-service-card');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            serviceCards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });
        });
    </script>

@endsection