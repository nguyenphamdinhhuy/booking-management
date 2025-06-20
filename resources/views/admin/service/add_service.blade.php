@extends('admin.layouts.master')
@section("content")

<h1 class="page-title">
    <i class="fas fa-concierge-bell"></i>
    Thêm dịch vụ mới
</h1>

<div class="content-section">
    <form action="{{ url('/admin/add_service') }}" method="POST" enctype="multipart/form-data" class="room-form">
        @csrf

        <div class="form-grid">
            <!-- Tên dịch vụ -->
            <div class="form-group">
                <label for="name" class="form-label">
                    <i class="fas fa-tag"></i> Tên dịch vụ <span class="required">*</span>
                </label>
                <input type="text" id="name" name="name" class="form-input" placeholder="VD: Đưa đón sân bay"
                    maxlength="100" value="{{ old('name') }}">
            </div>

            <!-- Giá -->
            <div class="form-group">
                <label for="price" class="form-label">
                    <i class="fas fa-dollar-sign"></i> Giá (VNĐ) <span class="required">*</span>
                </label>
                <input type="number" id="price" name="price" class="form-input" placeholder="VD: 500000" min="0"
                    step="1000" value="{{ old('price') }}">
            </div>

            <!-- Đơn vị tính -->
            <div class="form-group">
                <label for="unit" class="form-label">
                    <i class="fas fa-balance-scale"></i> Đơn vị tính
                </label>
                <input type="text" id="unit" name="unit" class="form-input" placeholder="VD: lượt, giờ, ngày"
                    maxlength="50" value="{{ old('unit') }}">
            </div>

            <!-- Danh mục -->
            <div class="form-group">
                <label for="category_id" class="form-label">
                    <i class="fas fa-list"></i> Danh mục dịch vụ <span class="required">*</span>
                </label>
                <select id="category_id" name="category_id" class="form-select">
                    <option value="">-- Chọn danh mục --</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                    <option value="1" {{ old('is_available', 1) == 1 ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('is_available') == 0 ? 'selected' : '' }}>Không hoạt động</option>
                </select>
            </div>

            <!-- Hình ảnh -->
            <div class="form-group full-width">
                <label for="image" class="form-label">
                    <i class="fas fa-image"></i> Hình ảnh dịch vụ
                </label>
                <div class="file-upload-area">
                    <input type="file" id="image" name="image" class="file-input" accept="image/*">
                    <div class="file-upload-content">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Kéo thả hình ảnh vào đây hoặc <span class="upload-link">chọn file</span></p>
                        <p class="file-info">Hỗ trợ: JPG, PNG, GIF (Tối đa 2MB)</p>
                    </div>
                </div>
            </div>

            <!-- Mô tả -->
            <div class="form-group full-width">
                <label for="description" class="form-label">
                    <i class="fas fa-align-left"></i> Mô tả
                </label>
                <textarea id="description" name="description" class="form-textarea" rows="5"
                    placeholder="Mô tả chi tiết về dịch vụ...">{{ old('description') }}</textarea>
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
                <i class="fas fa-save"></i> Lưu dịch vụ
            </button>
        </div>
    </form>
</div>

@endsection