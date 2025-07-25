@extends('admin.layouts.master')
@section("content")

    <h1 class="page-title">
        <i class="fas fa-bed"></i>
        Thêm danh mục dịch vụ
    </h1>

    <!-- Add Room Form -->
    <div class="content-section">
        <form action="{{ url('/admin/add_service_category') }}" method="POST" enctype="multipart/form-data">

            @csrf
            <div class="form-grid">
                <!-- Room Name -->
                <div class="form-group">
                    <label for="name" class="form-label">
                        <i class="fas fa-tag"></i>
                        Loại dịch vụ<span class="required">*</span>
                    </label>
                    <input type="text" id="name" name="name" class="form-input" placeholder="VD: Dịch vụ đưa đón"
                        maxlength="50">
                </div>

                <!-- Price per Night -->
                <div class="form-group full-width">
                    <label for="description" class="form-label">
                        <i class="fas fa-align-left"></i>
                        Mô tả
                    </label>
                    <textarea id="description" name="description" class="form-textarea"
                        placeholder="Mô tả chi tiết về dịch vụ, tiện nghi..." rows="5"></textarea>
                </div>

                <!-- them hinh anh -->
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
                <div id="imagePreview" class="image-preview"></div>

            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Hủy bỏ
                </button>
                <button type="reset" class="btn btn-outline">
                    <i class="fas fa-redo"></i>
                    Làm mới
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Lưu dịch vụ
                </button>
            </div>
        </form>
    </div>

@endsection