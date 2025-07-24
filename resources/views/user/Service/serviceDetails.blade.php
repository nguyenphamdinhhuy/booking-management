@extends('layouts.app')

@section('content')
    <div class="container py-4">

        {{-- Thanh danh mục --}}
        <div class="mb-4 d-flex flex-wrap gap-2">
            @foreach ($categories as $category)
                <a href="{{ route('Service.byCategory', ['id' => $category->id]) }}"
                    class="btn btn-outline-primary @if($category->id == $currentCategoryId) active @endif">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        {{-- Hình ảnh banner danh mục (nếu có) --}}
        <div class="mb-4">
            <img src="{{ asset($categories->firstWhere('id', $currentCategoryId)?->image ?? 'images/default-banner.jpg') }}"
                class="img-fluid rounded shadow" style="max-height: 300px; object-fit: cover;" alt="Banner danh mục">
        </div>

        {{-- Danh sách dịch vụ --}}
        <div class="row">
            @foreach ($services as $service)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0 rounded-4">
                        <div class="position-relative">
                            <img src="{{ asset($service->image ?? 'images/default.jpg') }}" class="card-img-top rounded-top-4"
                                style="height: 200px; object-fit: cover;" alt="{{ $service->name }}">
                            @if(!$service->is_available)
                                <span class="badge bg-danger position-absolute top-0 end-0 m-2">Hết</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $service->name }}</h5>
                            <p class="mb-1 text-muted"><strong>Giá:</strong> {{ number_format($service->price, 0, ',', '.') }}
                                VNĐ / {{ $service->unit }}</p>
                            <p class="small">{{ Str::limit($service->description, 100, '...') }}</p>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="#" class="btn btn-outline-primary w-100 rounded-pill">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($services->isEmpty())
            <div class="alert alert-warning">Không có dịch vụ nào trong danh mục này.</div>
        @endif

    </div>
@endsection