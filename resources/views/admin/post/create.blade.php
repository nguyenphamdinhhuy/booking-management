@extends('admin.layouts.master')
@section("content")

{{-- Hiển thị thông báo --}}
@if(session('success'))
<div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 20px;">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 20px;">
    {{ session('error') }}
</div>
@endif

{{-- Hiển thị lỗi validation --}}
@if($errors->any())
<div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 20px;">
    <ul style="margin: 0; padding-left: 20px;">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<h1 class="page-title">
    <i class="fas fa-newspaper"></i>
    Thêm bài viết mới
</h1>

<!-- Add Post Form -->
<div class="content-section">
    <form action="{{ route('admin.post.store') }}" enctype="multipart/form-data" method="POST" class="room-form" id="addPostForm">
        @csrf
        <div class="form-grid">
            <!-- Tiêu đề -->
            <div class="form-group full-width">
                <label for="title" class="form-label">
                    <i class="fas fa-heading"></i>
                    Tiêu đề bài viết <span class="required">*</span>
                </label>
                <input type="text" id="title" name="title" class="form-input" value="{{ old('title') }}"
                       placeholder="Nhập tiêu đề bài viết" maxlength="255">
            </div>

            <!-- Danh mục -->
            <div class="form-group">
                <label for="category" class="form-label">
                    <i class="fas fa-folder-open"></i>
                    Danh mục
                </label>
                <input type="text" id="category" name="category" class="form-input" value="{{ old('category') }}"
                       placeholder="Nhập danh mục">
            </div>

            <!-- Tác giả -->
            <div class="form-group">
                <label for="author" class="form-label">
                    <i class="fas fa-user"></i>
                    Tác giả
                </label>
                <input type="text" id="author" name="author" class="form-input" value="{{ old('author') }}"
                       placeholder="Tên tác giả">
            </div>

            <!-- Trạng thái -->
            <div class="form-group">
                <label for="status" class="form-label">
                    <i class="fas fa-toggle-on"></i>
                    Trạng thái
                </label>
                <select id="status" name="status" class="form-select">
                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>

            <!-- Ngày đăng -->
            <div class="form-group">
                <label for="published_at" class="form-label">
                    <i class="fas fa-calendar-alt"></i>
                    Ngày đăng
                </label>
                <input type="date" id="published_at" name="published_at" class="form-input"
                       value="{{ old('published_at') }}">
            </div>

            <!-- Ảnh bài viết -->
            <div class="form-group full-width">
                <label for="image" class="form-label">
                    <i class="fas fa-image"></i>
                    Ảnh bài viết
                </label>
                <div class="file-upload-area">
                    <input type="file" id="image" name="image" class="file-input" accept="image/*">
                    <div class="file-upload-content">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Kéo thả hình ảnh vào đây hoặc <span class="upload-link">chọn file</span></p>
                        <p class="file-info">Hỗ trợ: JPG, PNG, GIF (Tối đa 5MB)</p>
                    </div>
                </div>
                <div id="imagePreview" class="image-preview"></div>
            </div>

            <!-- Nội dung -->
            <div class="form-group full-width">
                <label for="content" class="form-label">
                    <i class="fas fa-align-left"></i>
                    Nội dung bài viết <span class="required">*</span>
                </label>
                <textarea  id="editor1" name="content" class="form-textarea"
                          placeholder="Nhập nội dung bài viết..." rows="8">{{ old('content') }}</textarea>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i>
                Quay lại
            </button>
            <button type="reset" class="btn btn-outline">
                <i class="fas fa-redo"></i>
                Làm mới
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Thêm mới
            </button>
        </div>
    </form>
</div>
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

    <script>
        CKEDITOR.replace('editor1');
    </script>
@endsection
