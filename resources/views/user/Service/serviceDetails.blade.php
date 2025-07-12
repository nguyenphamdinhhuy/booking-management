@extends('layouts.app')

@section('content')
    <div class="container py-4">

        {{-- Thanh danh mục dịch vụ --}}
        <div class="mb-4">
            <h4 class="mb-3">Các loại dịch vụ</h4>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($categories as $i)
                    <a href="" class="explore-item">
                        {{ $i->name }}
                    </a>
                @endforeach
            </div>
        </div>  

        {{-- Tên danh mục đang xem --}}
        <h4 class="mb-4">
            Dịch vụ thuộc danh mục:
            <span class="text-primary fw-semibold">
                {{ $categories->firstWhere('id', $currentCategoryId)?->name }}
            </span>
        </h4>

        {{-- Danh sách dịch vụ --}}
        <div class="row">
            @forelse ($services as $service)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0 rounded-3">
                        <img src="{{ asset($service->image) }}" class="card-img-top rounded-top-3"
                            style="height: 200px; object-fit: cover;" alt="{{ $service->name }}">
                        <div class="card-body">
                            <h5 class="card-title text-truncate">{{ $service->name }}</h5>
                            <p class="text-muted small mb-2">
                                {{ Str::limit($service->description, 80) }}
                            </p>
                            <p class="fw-bold mb-3">
                                {{ number_format($service->price, 0, ',', '.') }} VNĐ / {{ $service->unit }}
                            </p>
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted fst-italic">Không có dịch vụ nào trong danh mục này.</p>
                </div>
            @endforelse
        </div>

    </div>
@endsection