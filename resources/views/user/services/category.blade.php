@extends('layouts.app')

@section('title', 'Dịch vụ - ' . $category->name)



@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <i class="fas fa-home mr-2"></i>
                        Trang chủ
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('user.services.index') }}"
                            class="text-sm font-medium text-gray-700 hover:text-blue-600">
                            Dịch vụ
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500">{{ $category->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Dịch vụ {{ $category->name }}</h1>
            @if($category->description)
                <p class="text-lg text-gray-600">{{ $category->description }}</p>
            @endif
        </div>

        <!-- Services Count -->
        <div class="search-filter-section">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-tag text-blue-600 text-xl mr-3"></i>
                    <span class="text-blue-800 font-medium">{{ $services->total() }} dịch vụ có sẵn</span>
                </div>
                <a href="{{ route('user.services.index') }}" class="action-btn action-btn-primary">
                    <i class="fas fa-arrow-left mr-2"></i>Xem tất cả dịch vụ
                </a>
            </div>
        </div>

        <!-- Services Grid -->
        @if($services->count() > 0)
            <div class="services-grid">
                @foreach($services as $service)
                    <div class="service-card">
                        <div class="service-image">
                            @if($service->image)
                                <img src="{{ asset($service->image) }}" alt="{{ $service->name }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-image text-4xl text-gray-400"></i>
                                </div>
                            @endif
                            <div class="service-status">
                                Có sẵn
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="category-pill">{{ $service->category->name }}</span>
                                <span class="service-price">{{ number_format($service->price) }} VNĐ</span>
                            </div>

                            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $service->name }}</h3>

                            @if($service->description)
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $service->description }}</p>
                            @endif

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Đơn vị: {{ $service->unit }}</span>
                                <a href="{{ route('user.services.show', $service->s_id) }}"
                                    class="action-btn action-btn-primary text-sm">
                                    Chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $services->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Không có dịch vụ nào</h3>
                <p class="text-gray-500 mb-6">Hiện tại không có dịch vụ nào trong danh mục này</p>
                <a href="{{ route('user.services.index') }}"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại danh sách dịch vụ
                </a>
            </div>
        @endif
    </div>

    <style>
        /* Category Page Styles */
        
        /* Service Card Hover Effects */
        .service-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .service-image {
            position: relative;
            overflow: hidden;
        }

        .service-image img {
            transition: transform 0.3s ease;
        }

        .service-card:hover .service-image img {
            transform: scale(1.05);
        }

        /* Service Status Badge */
        .service-status {
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
        .service-price {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 18px;
            display: inline-block;
        }

        /* Category Pills */
        .category-pill {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .category-pill:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
        }

        /* Search and Filter Section */
        .search-filter-section {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 32px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        /* Action Buttons */
        .action-btn {
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

        .action-btn-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .action-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(59, 130, 246, 0.3);
        }

        /* Service Grid */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        /* Line Clamp */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .services-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            
            .search-filter-section {
                padding: 16px;
            }
            
            .action-btn {
                width: 100%;
                margin-bottom: 12px;
            }
        }
    </style>
@endsection