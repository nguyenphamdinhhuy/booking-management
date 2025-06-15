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
    <i class="fas fa-bed"></i>
    Thêm phòng mới
</h1>

<!-- Add Room Form -->
<div class="content-section">
    <form action="{{ route('admin.rooms.store') }}" enctype="multipart/form-data" method="POST" class="room-form">
        @csrf
        <div class="form-grid">
            <!-- Room Name -->
            <div class="form-group">
                <label for="name" class="form-label">
                    <i class="fas fa-tag"></i>
                    Tên phòng <span class="required">*</span>
                </label>
                <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}"
                    placeholder="VD: Phòng Deluxe 101" maxlength="50" required>
            </div>

            <!-- Price per Night -->
            <div class="form-group">
                <label for="price_per_night" class="form-label">
                    <i class="fas fa-dollar-sign"></i>
                    Giá mỗi đêm (VNĐ) <span class="required">*</span>
                </label>
                <input type="number" id="price_per_night" name="price_per_night" class="form-input" value="{{ old('price_per_night') }}"
                    placeholder="500000" min="0" step="1000" required>
            </div>

            <!-- Max Guests -->
            <div class="form-group">
                <label for="max_guests" class="form-label">
                    <i class="fas fa-users"></i>
                    Số khách tối đa
                </label>
                <select id="max_guests" name="max_guests" class="form-select">
                    <option value="">Chọn số khách</option>
                    <option value="1" {{ old('max_guests') == '1' ? 'selected' : '' }}>1 khách</option>
                    <option value="2" {{ old('max_guests') == '2' ? 'selected' : '' }}>2 khách</option>
                    <option value="3" {{ old('max_guests') == '3' ? 'selected' : '' }}>3 khách</option>
                    <option value="4" {{ old('max_guests') == '4' ? 'selected' : '' }}>4 khách</option>
                    <option value="5" {{ old('max_guests') == '5' ? 'selected' : '' }}>5 khách</option>
                    <option value="6" {{ old('max_guests') == '6' ? 'selected' : '' }}>6 khách</option>
                </select>
            </div>

            <!-- Number of Beds -->
            <div class="form-group">
                <label for="number_beds" class="form-label">
                    <i class="fas fa-bed"></i>
                    Số giường
                </label>
                <select id="number_beds" name="number_beds" class="form-select">
                    <option value="">Chọn số giường</option>
                    <option value="1" {{ old('number_beds') == '1' ? 'selected' : '' }}>1 giường</option>
                    <option value="2" {{ old('number_beds') == '2' ? 'selected' : '' }}>2 giường</option>
                    <option value="3" {{ old('number_beds') == '3' ? 'selected' : '' }}>3 giường</option>
                    <option value="4" {{ old('number_beds') == '4' ? 'selected' : '' }}>4 giường</option>
                </select>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status" class="form-label">
                    <i class="fas fa-toggle-on"></i>
                    Trạng thái
                </label>
                <select id="status" name="status" class="form-select">
                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                </select>
            </div>

            <!-- Images -->
            <div class="form-group full-width">
                <label for="images" class="form-label">
                    <i class="fas fa-images"></i>
                    Hình ảnh phòng
                </label>
                <div class="file-upload-area">
                    <input type="file" id="images" name="images[]" class="file-input"
                        accept="image/*" multiple>
                    <div class="file-upload-content">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Kéo thả hình ảnh vào đây hoặc <span class="upload-link">chọn file</span></p>
                        <p class="file-info">Hỗ trợ: JPG, PNG, GIF (Tối đa 5MB mỗi file)</p>
                    </div>
                </div>
                <div id="imagePreview" class="image-preview"></div>
            </div>

            <!-- Description -->
            <div class="form-group full-width">
                <label for="description" class="form-label">
                    <i class="fas fa-align-left"></i>
                    Mô tả phòng
                </label>
                <textarea id="description" name="description" class="form-textarea"
                    placeholder="Mô tả chi tiết về phòng, tiện nghi, dịch vụ..." rows="5">{{ old('description') }}</textarea>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                <i class="fas fa-times"></i>
                Hủy bỏ
            </button>
            <button type="reset" class="btn btn-outline">
                <i class="fas fa-redo"></i>
                Làm mới
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Lưu phòng
            </button>
        </div>
    </form>
</div>

@endsection