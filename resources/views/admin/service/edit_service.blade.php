@extends('admin.layouts.master')
@section("content")

    <h1 class="page-title">
        <i class="fas fa-edit"></i>
        Chỉnh sửa dịch vụ
    </h1>

    <div class="content-section">
        <form action="{{ route('service.update', $service->s_id) }}" method="POST" enctype="multipart/form-data"
            class="room-form">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <!-- Tên dịch vụ -->
                <div class="form-group">
                    <label for="name" class="form-label">
                        <i class="fas fa-tag"></i> Tên dịch vụ
                    </label>
                    <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $service->name) }}"
                        required>
                </div>

                <!-- Giá -->
                <div class="form-group">
                    <label for="price" class="form-label">
                        <i class="fas fa-dollar-sign"></i> Giá (VNĐ)
                    </label>
                    <input type="number" name="price" id="price" class="form-input"
                        value="{{ old('price', $service->price) }}" min="0" step="1000" required>
                </div>

                <!-- Đơn vị -->
                <div class="form-group">
                    <label for="unit" class="form-label">
                        <i class="fas fa-balance-scale"></i> Đơn vị tính
                    </label>
                    <input type="text" name="unit" id="unit" class="form-input" value="{{ old('unit', $service->unit) }}"
                        required>
                </div>

                <!-- Danh mục -->
                <div class="form-group">
                    <label for="category_id" class="form-label">
                        <i class="fas fa-list"></i> Danh mục
                    </label>
                    <select id="category_id" name="category_id" class="form-select" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $service->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Trạng thái -->
                <div class="form-group">
                    <label for="is_available" class="form-label">
                        <i class="fas fa-toggle-on"></i> Trạng thái
                    </label>
                    <select id="is_available" name="is_available" class="form-select">
                        <option value="1" {{ $service->is_available ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ !$service->is_available ? 'selected' : '' }}>Không hoạt động</option>
                    </select>
                </div>

                <!-- Hình ảnh -->
                <div class="form-group full-width">
                    <label for="image" class="form-label">
                        <i class="fas fa-image"></i> Hình ảnh hiện tại
                    </label>
                    @if($service->image)
                        <div><img src="{{ asset($service->image) }}" width="120" height="80"></div>
                    @else
                        <div>Không có ảnh</div>
                    @endif
                    <input type="file" name="image" id="image" class="form-input mt-2">
                </div>

                <!-- Mô tả -->
                <div class="form-group full-width">
                    <label for="description" class="form-label">
                        <i class="fas fa-align-left"></i> Mô tả
                    </label>
                    <textarea name="description" id="description" class="form-textarea"
                        rows="5">{{ old('description', $service->description) }}</textarea>
                </div>
            </div>

            <!-- Hành động -->
            <div class="form-actions">
                <a href="{{ route('services.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <button type="reset" class="btn btn-outline">
                    <i class="fas fa-redo"></i> Làm mới
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
            </div>
        </form>
    </div>

@endsection