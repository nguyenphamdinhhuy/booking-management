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
    <i class="fas fa-edit"></i>
    Chỉnh sửa bài viết: {{ $post->title }}
</h1>

<!-- Edit Post Form -->
<div class="content-section">
    <form action="{{ route('admin.post.update', $post->p_id) }}" enctype="multipart/form-data" method="POST" class="room-form" id="editPostForm">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <!-- Tiêu đề -->
            <div class="form-group full-width">
                <label for="title" class="form-label">
                    <i class="fas fa-heading"></i>
                    Tiêu đề bài viết <span class="required">*</span>
                </label>
                <input type="text" id="title" name="title" class="form-input" 
                       value="{{ old('title', $post->title) }}" 
                       placeholder="Nhập tiêu đề bài viết" maxlength="255">
            </div>

            <!-- Danh mục -->
            <div class="form-group">
                <label for="category" class="form-label">
                    <i class="fas fa-folder-open"></i>
                    Danh mục
                </label>
                <input type="text" id="category" name="category" class="form-input" 
                       value="{{ old('category', $post->category) }}" 
                       placeholder="Nhập danh mục">
            </div>

            <!-- Tác giả -->
            <div class="form-group">
                <label for="author" class="form-label">
                    <i class="fas fa-user"></i>
                    Tác giả
                </label>
                <input type="text" id="author" name="author" class="form-input" 
                       value="{{ old('author', $post->author) }}" 
                       placeholder="Tên tác giả">
            </div>

            <!-- Trạng thái -->
            <div class="form-group">
                <label for="status" class="form-label">
                    <i class="fas fa-toggle-on"></i>
                    Trạng thái
                </label>
                <select id="status" name="status" class="form-select">
                    <option value="1" {{ old('status', $post->status) == '1' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ old('status', $post->status) == '0' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>

            <!-- Ngày đăng -->
            <div class="form-group">
                <label for="published_at" class="form-label">
                    <i class="fas fa-calendar-alt"></i>
                    Ngày đăng
                </label>
                <input type="date" id="published_at" name="published_at" class="form-input" 
                       value="{{ old('published_at', $post->published_at) }}">
            </div>

            <!-- Ảnh hiện tại & Ảnh mới -->
            <div class="form-group full-width">
                <label for="image" class="form-label">
                    <i class="fas fa-image"></i>
                    Ảnh bài viết
                </label>

                {{-- Ảnh hiện tại --}}
                @if($post->images)
                <div class="current-image" style="margin-bottom: 15px;">
                    <p style="margin-bottom: 10px; font-weight: 500;">Ảnh hiện tại:</p>
                    <img src="{{ asset('storage/'.$post->images) }}" alt="{{ $post->title }}"
                         style="width: 200px; height: 150px; object-fit: cover; border-radius: 8px; border: 2px solid #e0e0e0;">
                </div>
                @endif

                {{-- Ảnh mới --}}
                <div class="file-upload-area">
                    <input type="file" id="image" name="image" class="file-input" accept="image/*">
                    <div class="file-upload-content">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>{{ $post->images ? 'Chọn ảnh mới để thay thế' : 'Kéo thả hình ảnh vào đây hoặc chọn file' }}</p>
                        <p class="file-info">Hỗ trợ: JPG, PNG, GIF (Tối đa 5MB)</p>
                    </div>
                </div>
                <div id="imagePreview" class="image-preview"></div>
            </div>

            <!-- Nội dung -->
            <div class="form-group full-width">
                <label for="content" class="form-label">
                    <i class="fas fa-align-left"></i>
                    Nội dung bài viết
                </label>
                <textarea id="editor1" name="content" class="form-textarea"
                          placeholder="Nhập nội dung bài viết..." rows="8">{{ old('content', $post->content) }}</textarea>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <a href="{{ route('admin.post.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Hủy bỏ
            </a>
            <button type="reset" class="btn btn-outline">
                <i class="fas fa-redo"></i>
                Làm mới
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Cập nhật bài viết
            </button>
        </div>
    </form>
</div>
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

    <script>
        CKEDITOR.replace('editor1');
    </script>

@endsection
