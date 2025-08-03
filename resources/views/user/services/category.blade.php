@extends('layouts.app')

@section('title', 'Dịch vụ - ' . $category->name)



@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flexitems-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <i class="fas fa-home mr-2"></i>
                    Trang chủ
                </a>
            </li>
            <li>
                <div class="flex-items-center">
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
        <div class="flex items-center">
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

                <div class="flex-items-center">
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
    /* === CATEGORY SERVICES PAGE === */
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f5f7fa;
        color: #2c3e50;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    /* === BREADCRUMB === */
    nav[aria-label="Breadcrumb"] ol {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: #7f8c8d;
    }

    nav[aria-label="Breadcrumb"] a {
        color: #3498db;
        transition: color 0.3s;
    }

    nav[aria-label="Breadcrumb"] a:hover {
        color: #1abc9c;
    }

    /* === HEADER === */
    .text-center {
        text-align: center;
    }

    .text-4xl {
        font-size: 2.25rem;
        font-weight: 700;
        color: #2c3e50;
    }

    .text-lg {
        font-size: 1.125rem;
        color: #7f8c8d;
    }

    /* === SEARCH FILTER === */
    .search-filter-section {
        margin-bottom: 20px;
        padding: 15px;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 600;
        transition: 0.3s;
        text-decoration: none;
    }

    .action-btn-primary {
        background-color: #3498db;
        color: white;
        margin-bottom: 20px;
    }

    .action-btn-primary:hover {
        background-color: #2980b9;
    }

    /* === SERVICES GRID === */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }

    .service-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        transition: transform 0.3s;
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .service-card:hover {
        transform: translateY(-5px);
    }

    .service-image {
        position: relative;
        height: 190px;
        background-color: #ecf0f1;
        overflow: hidden;
    }

    .service-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .service-status {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: #2ecc71;
        color: white;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
    }

    .category-pill {
        background-color: #dff9fb;
        color: #2980b9;
        font-size: 12px;
        padding: 3px 8px;
        border-radius: 15px;
    }

    .service-price {
        color: #e74c3c;
        font-weight: bold;
        font-size: 14px;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* === EMPTY STATE === */
    .text-center.py-12 {
        padding: 60px 20px;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }

    .text-gray-300 {
        color: #bdc3c7;
    }

    .flex-items-center {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .text-gray-500 {
        color: #95a5a6;
    }

    .text-gray-600 {
        color: #7f8c8d;
    }

    .bg-blue-600 {
        background-color: #3498db;
        color: white;
    }

    .bg-blue-600:hover {
        background-color: #2980b9;
    }

    /* === PAGINATION (Laravel default) === */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 30px;
    }

    .pagination .page-link {
        padding: 8px 12px;
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 5px;
        color: #3498db;
        transition: 0.3s;
    }

    .pagination .page-link:hover {
        background-color: #3498db;
        color: white;
    }

    .pagination .active .page-link {
        background-color: #2980b9;
        color: white;
        border-color: #2980b9;
    }

    .p-4 {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 0 20px;
    }
</style>
@endsection