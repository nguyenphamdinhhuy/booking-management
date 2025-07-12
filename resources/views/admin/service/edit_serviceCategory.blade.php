@extends('admin.layouts.master')
@section("content")

    <h1 class="page-title">
        <i class="fas fa-bed"></i>
        Chỉnh sửa danh mục dịch vụ
    </h1>

    <!-- Edit Service Category Form -->
    <div class="content-section">
        <form action="{{ route('service-categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <!-- Tên danh mục -->
                <div class="form-group">
                    <label for="name" class="form-label">
                        <i class="fas fa-tag"></i>
                        Loại dịch vụ<span class="required">*</span>
                    </label>
                    <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $category->name) }}"
                        placeholder="VD: Dịch vụ đưa đón" maxlength="50" required>
                </div>

                <!-- Mô tả -->
                <div class="form-group full-width">
                    <label for="description" class="form-label">
                        <i class="fas fa-align-left"></i>
                        Mô tả
                    </label>
                    <textarea id="description" name="description" class="form-textarea"
                        placeholder="Mô tả chi tiết về dịch vụ, tiện nghi..."
                        rows="5">{{ old('description', $category->description) }}</textarea>
                </div>

                <!-- hinh anh -->
                <div class="form-group full-width">
                    <label for="image" class="form-label">
                        <i class="fas fa-image"></i> Hình ảnh hiện tại
                    </label>
                    @if($category->image)
                        <div><img src="{{ asset($category->image) }}" width="120" height="80"></div>
                    @else
                        <div>Không có ảnh</div>
                    @endif
                    <input type="file" name="image" id="image" class="form-input mt-2">
                </div>

            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('service-categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Quay lại
                </a>
                <button type="reset" class="btn btn-outline">
                    <i class="fas fa-redo"></i>
                    Làm mới
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Cập nhật
                </button>
            </div>
        </form>
    </div>

@endsection