@extends('admin.layouts.master')
@section("content")

<h1 class="page-title">
    <i class="fas fa-bed"></i>
    Thêm phòng mới
</h1>

<!-- Add Room Form -->
<div class="content-section">
    <form id="addRoomForm" class="room-form">
        @csrf
        <div class="form-grid">
            <!-- Room Name -->
            <div class="form-group">
                <label for="name" class="form-label">
                    <i class="fas fa-tag"></i>
                    Tên phòng <span class="required">*</span>
                </label>
                <input type="text" id="name" name="name" class="form-input"
                    placeholder="VD: Phòng Deluxe 101" maxlength="50" required>
            </div>

            <!-- Price per Night -->
            <div class="form-group">
                <label for="price_per_night" class="form-label">
                    <i class="fas fa-dollar-sign"></i>
                    Giá mỗi đêm (VNĐ) <span class="required">*</span>
                </label>
                <input type="number" id="price_per_night" name="price_per_night"
                    class="form-input" placeholder="500000" min="0" step="1000" required>
            </div>

            <!-- Max Guests -->
            <div class="form-group">
                <label for="max_guests" class="form-label">
                    <i class="fas fa-users"></i>
                    Số khách tối đa
                </label>
                <select id="max_guests" name="max_guests" class="form-select">
                    <option value="">Chọn số khách</option>
                    <option value="1">1 khách</option>
                    <option value="2">2 khách</option>
                    <option value="3">3 khách</option>
                    <option value="4">4 khách</option>
                    <option value="5">5 khách</option>
                    <option value="6">6 khách</option>
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
                    <option value="1">1 giường</option>
                    <option value="2">2 giường</option>
                    <option value="3">3 giường</option>
                    <option value="4">4 giường</option>
                </select>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status" class="form-label">
                    <i class="fas fa-toggle-on"></i>
                    Trạng thái
                </label>
                <select id="status" name="status" class="form-select">
                    <option value="1">Hoạt động</option>
                    <option value="0">Không hoạt động</option>
                </select>
            </div>

            <!-- Images -->
            <div class="form-group full-width">
                <label for="images" class="form-label">
                    <i class="fas fa-images"></i>
                    Hình ảnh phòng
                </label>
                <div class="file-upload-area">
                    <input type="file" id="images" name="images" class="file-input"
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
                    placeholder="Mô tả chi tiết về phòng, tiện nghi, dịch vụ..." rows="5"></textarea>
            </div>
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
                Lưu phòng
            </button>
        </div>
    </form>
</div>

@endsection