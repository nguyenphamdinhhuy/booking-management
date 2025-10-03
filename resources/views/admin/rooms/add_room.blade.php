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

<h1 class="page-title">
    <i class="fas fa-bed"></i>
    Thêm phòng mới
</h1>

<!-- Add Room Form -->
<div class="content-section">
    <form action="{{ route('admin.rooms.store') }}" enctype="multipart/form-data" method="POST" class="room-form" id="addRoomForm">
        @csrf
        <div class="form-grid">
            <!-- Room Name -->
            <div class="form-group">
                <label for="name" class="form-label">
                    <i class="fas fa-tag"></i>
                    Tên phòng <span class="required">*</span>
                </label>
                <input type="text"
                    id="name"
                    name="name"
                    class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                    value="{{ old('name') }}"
                    placeholder="VD: Phòng Deluxe 101"
                    maxlength="50">
                @if($errors->has('name'))
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first('name') }}
                </div>
                @endif
            </div>

            <!-- Room Type Selection -->
            <div class="form-group">
                <label for="rt_id" class="form-label">
                    <i class="fas fa-home"></i>
                    Loại phòng <span class="required">*</span>
                </label>
                <select id="rt_id"
                    name="rt_id"
                    class="form-select {{ $errors->has('rt_id') ? 'error' : '' }}">
                    <option value="">Chọn loại phòng</option>
                    @foreach($roomTypes as $roomType)
                    <option value="{{ $roomType->rt_id }}"
                        {{ old('rt_id') == $roomType->rt_id ? 'selected' : '' }}
                        data-base-price="{{ $roomType->base_price }}"
                        data-max-guests="{{ $roomType->max_guests }}"
                        data-number-beds="{{ $roomType->number_beds }}"
                        data-room-size="{{ $roomType->room_size }}"
                        data-amenities="{{ $roomType->amenities }}">
                        {{ $roomType->type_name }}
                        @if($roomType->room_size)
                        ({{ $roomType->room_size }})
                        @endif
                    </option>
                    @endforeach
                </select>
                @if($errors->has('rt_id'))
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first('rt_id') }}
                </div>
                @endif
            </div>

            <!-- Price per Night -->
            <div class="form-group">
                <label for="price_per_night" class="form-label">
                    <i class="fas fa-dollar-sign"></i>
                    Giá mỗi đêm (VNĐ) <span class="required">*</span>
                </label>
                <input type="number"
                    id="price_per_night"
                    name="price_per_night"
                    class="form-input {{ $errors->has('price_per_night') ? 'error' : '' }}"
                    value="{{ old('price_per_night') }}"
                    placeholder="500000"
                    min="0"
                    step="1000">
                @if($errors->has('price_per_night'))
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first('price_per_night') }}
                </div>
                @endif
                <small class="text-muted">Để trống sẽ sử dụng giá cơ bản từ loại phòng</small>
            </div>

            <!-- Max Guests -->
            <div class="form-group">
                <label for="max_guests" class="form-label">
                    <i class="fas fa-users"></i>
                    Số khách tối đa
                </label>
                <select id="max_guests"
                    name="max_guests"
                    class="form-select {{ $errors->has('max_guests') ? 'error' : '' }}">
                    <option value="">Sử dụng mặc định từ loại phòng</option>
                    <option value="1" {{ old('max_guests') == '1' ? 'selected' : '' }}>1 khách</option>
                    <option value="2" {{ old('max_guests') == '2' ? 'selected' : '' }}>2 khách</option>
                    <option value="3" {{ old('max_guests') == '3' ? 'selected' : '' }}>3 khách</option>
                    <option value="4" {{ old('max_guests') == '4' ? 'selected' : '' }}>4 khách</option>
                    <option value="5" {{ old('max_guests') == '5' ? 'selected' : '' }}>5 khách</option>
                    <option value="6" {{ old('max_guests') == '6' ? 'selected' : '' }}>6 khách</option>
                </select>
                @if($errors->has('max_guests'))
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first('max_guests') }}
                </div>
                @endif
            </div>

            <!-- Number of Beds -->
            <div class="form-group">
                <label for="number_beds" class="form-label">
                    <i class="fas fa-bed"></i>
                    Số giường
                </label>
                <select id="number_beds"
                    name="number_beds"
                    class="form-select {{ $errors->has('number_beds') ? 'error' : '' }}">
                    <option value="">Sử dụng mặc định từ loại phòng</option>
                    <option value="1" {{ old('number_beds') == '1' ? 'selected' : '' }}>1 giường</option>
                    <option value="2" {{ old('number_beds') == '2' ? 'selected' : '' }}>2 giường</option>
                    <option value="3" {{ old('number_beds') == '3' ? 'selected' : '' }}>3 giường</option>
                    <option value="4" {{ old('number_beds') == '4' ? 'selected' : '' }}>4 giường</option>
                </select>
                @if($errors->has('number_beds'))
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first('number_beds') }}
                </div>
                @endif
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status" class="form-label">
                    <i class="fas fa-toggle-on"></i>
                    Trạng thái
                </label>
                <select id="status"
                    name="status"
                    class="form-select {{ $errors->has('status') ? 'error' : '' }}">
                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                </select>
                @if($errors->has('status'))
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first('status') }}
                </div>
                @endif
            </div>

            <!-- Room Type Info Display -->
            <div class="form-group full-width" id="roomTypeInfo" style="display: none;">
                <div class="room-type-info-card">
                    <h4><i class="fas fa-info-circle"></i> Thông tin loại phòng</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <strong>Giá cơ bản:</strong>
                            <span id="infoBasePrice">-</span> VNĐ/đêm
                        </div>
                        <div class="info-item">
                            <strong>Số khách mặc định:</strong>
                            <span id="infoMaxGuests">-</span> khách
                        </div>
                        <div class="info-item">
                            <strong>Số giường mặc định:</strong>
                            <span id="infoNumberBeds">-</span> giường
                        </div>
                        <div class="info-item">
                            <strong>Diện tích:</strong>
                            <span id="infoRoomSize">-</span>
                        </div>
                    </div>
                    <div class="amenities-section">
                        <div id="infoAmenities" class="amenities-list"></div>
                    </div>
                </div>
            </div>

            <!-- Multiple Images Upload -->
            <div class="form-group full-width">
                <label for="images" class="form-label">
                    <i class="fas fa-images"></i>
                    Hình ảnh phòng
                    <span class="text-muted">(Tối đa 10 ảnh)</span>
                </label>
                <div class="file-upload-area {{ $errors->has('images') || $errors->has('images.*') ? 'error' : '' }}"
                    onclick="document.getElementById('images').click()">
                    <input type="file"
                        id="images"
                        name="images[]"
                        class="file-input"
                        accept="image/*"
                        multiple
                        style="display: none;">
                    <div class="file-upload-content">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Kéo thả hình ảnh vào đây hoặc <span class="upload-link">chọn nhiều file</span></p>
                        <p class="file-info">Hỗ trợ: JPG, PNG, GIF (Tối đa 5MB mỗi ảnh, tối đa 10 ảnh)</p>
                    </div>
                </div>
                @if($errors->has('images'))
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first('images') }}
                </div>
                @endif
                @if($errors->has('images.*'))
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    @foreach($errors->get('images.*') as $messages)
                    @foreach($messages as $message)
                    <div>{{ $message }}</div>
                    @endforeach
                    @endforeach
                </div>
                @endif
                <div id="imagePreview" class="image-preview-grid"></div>
            </div>

            <!-- Description -->
            <div class="form-group full-width">
                <label for="description" class="form-label">
                    <i class="fas fa-align-left"></i>
                    Mô tả phòng
                </label>
                <textarea id="description"
                    name="description"
                    class="form-textarea {{ $errors->has('description') ? 'error' : '' }}"
                    placeholder="Mô tả chi tiết về phòng, tiện nghi, dịch vụ..."
                    rows="5">{{ old('description') }}</textarea>
                @if($errors->has('description'))
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first('description') }}
                </div>
                @endif
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                <i class="fas fa-times"></i>
                Hủy bỏ
            </button>
            <button type="reset" class="btn btn-outline" onclick="resetForm()">
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

<style>
    /* Error Styles */
    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .error-message i {
        font-size: 0.8rem;
    }

    .form-input.error,
    .form-select.error,
    .form-textarea.error {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .file-upload-area.error {
        border-color: #dc3545;
        background-color: #f8d7da;
    }

    .form-input.error:focus,
    .form-select.error:focus,
    .form-textarea.error:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    /* Success Styles (for valid fields) */
    .form-input.success,
    .form-select.success,
    .form-textarea.success {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    /* Room Type Info Card Styles */
    .room-type-info-card {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-top: 10px;
    }

    .room-type-info-card h4 {
        color: #495057;
        margin-bottom: 15px;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }

    .info-item {
        background: white;
        padding: 10px 15px;
        border-radius: 5px;
        border: 1px solid #e9ecef;
    }

    .info-item strong {
        color: #495057;
        font-weight: 600;
    }

    .amenities-section {
        background: white;
        padding: 15px;
        border-radius: 5px;
        border: 1px solid #e9ecef;
    }

    .amenities-list {
        display: none;
        margin-top: 8px;
        color: #6c757d;
        line-height: 1.5;
    }

    /* Image Preview Styles */
    .image-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .image-preview-item {
        position: relative;
        border: 2px solid #e1e5e9;
        border-radius: 8px;
        overflow: hidden;
        background: #f8f9fa;
    }

    .image-preview-item img {
        width: 100%;
        height: 120px;
        object-fit: cover;
    }

    .image-preview-item .remove-image {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        transition: background-color 0.3s;
    }

    .image-preview-item .remove-image:hover {
        background: rgba(220, 53, 69, 1);
    }

    .image-preview-item .image-name {
        padding: 8px;
        font-size: 12px;
        color: #6c757d;
        background: white;
        border-top: 1px solid #e1e5e9;
        text-align: center;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .file-upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 40px 20px;
        text-align: center;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .file-upload-area:hover {
        border-color: #007bff;
        background: #e3f2fd;
    }

    .file-upload-area.dragover {
        border-color: #007bff;
        background: #e3f2fd;
        transform: scale(1.02);
    }

    .text-muted {
        color: #6c757d;
        font-size: 0.9em;
    }

    /* Animation for error messages */
    .error-message {
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Animation for room type info */
    .room-type-info-card {
        animation: slideIn 0.3s ease-in-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    let selectedFiles = [];
    const MAX_FILES = 10;
    const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB

    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('images');
        const uploadArea = document.querySelector('.file-upload-area');
        const previewContainer = document.getElementById('imagePreview');
        const roomTypeSelect = document.getElementById('rt_id');
        const priceInput = document.getElementById('price_per_night');

        // Handle room type selection
        roomTypeSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const roomTypeInfo = document.getElementById('roomTypeInfo');

            if (this.value) {
                // Show room type info
                const basePrice = selectedOption.getAttribute('data-base-price');
                const maxGuests = selectedOption.getAttribute('data-max-guests');
                const numberBeds = selectedOption.getAttribute('data-number-beds');
                const roomSize = selectedOption.getAttribute('data-room-size');
                const amenities = selectedOption.getAttribute('data-amenities');

                document.getElementById('infoBasePrice').textContent =
                    basePrice ? new Intl.NumberFormat('vi-VN').format(basePrice) : '-';
                document.getElementById('infoMaxGuests').textContent = maxGuests || '-';
                document.getElementById('infoNumberBeds').textContent = numberBeds || '-';
                document.getElementById('infoRoomSize').textContent = roomSize || '-';
                document.getElementById('infoAmenities').textContent = amenities || 'Không có thông tin';

                // Auto-fill price if empty
                if (!priceInput.value && basePrice) {
                    priceInput.value = basePrice;
                }

                roomTypeInfo.style.display = 'block';
            } else {
                roomTypeInfo.style.display = 'none';
            }
        });

        // Handle file input change
        fileInput.addEventListener('change', function(e) {
            handleFiles(e.target.files);
        });

        // Handle drag and drop
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            handleFiles(e.dataTransfer.files);
        });

        // Real-time validation
        const inputs = document.querySelectorAll('.form-input, .form-select, .form-textarea');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                clearFieldError(this);
            });

            input.addEventListener('change', function() {
                clearFieldError(this);
            });
        });

        function clearFieldError(field) {
            // Remove error class and hide error message when user starts typing
            field.classList.remove('error');
            const errorMessage = field.parentNode.querySelector('.error-message');
            if (errorMessage) {
                errorMessage.style.display = 'none';
            }
        }

        function handleFiles(files) {
            const fileArray = Array.from(files);

            // Check total files limit
            if (selectedFiles.length + fileArray.length > MAX_FILES) {
                showFileError(`Chỉ được chọn tối đa ${MAX_FILES} ảnh. Hiện tại bạn đã chọn ${selectedFiles.length} ảnh.`);
                return;
            }

            fileArray.forEach(file => {
                // Check file type
                if (!file.type.startsWith('image/')) {
                    showFileError(`File "${file.name}" không phải là hình ảnh.`);
                    return;
                }

                // Check file size
                if (file.size > MAX_FILE_SIZE) {
                    showFileError(`File "${file.name}" có kích thước quá lớn. Tối đa 5MB.`);
                    return;
                }

                // Check if file already selected
                if (selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                    showFileError(`File "${file.name}" đã được chọn.`);
                    return;
                }

                selectedFiles.push(file);
            });

            updatePreview();
            updateFileInput();
            clearFileError();
        }

        function showFileError(message) {
            const uploadArea = document.querySelector('.file-upload-area');
            uploadArea.classList.add('error');

            // Remove existing error message
            const existingError = uploadArea.parentNode.querySelector('.dynamic-error-message');
            if (existingError) {
                existingError.remove();
            }

            // Add new error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message dynamic-error-message';
            errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
            uploadArea.parentNode.insertBefore(errorDiv, document.getElementById('imagePreview'));
        }

        function clearFileError() {
            const uploadArea = document.querySelector('.file-upload-area');
            uploadArea.classList.remove('error');

            const dynamicError = uploadArea.parentNode.querySelector('.dynamic-error-message');
            if (dynamicError) {
                dynamicError.remove();
            }
        }

        function updatePreview() {
            previewContainer.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'image-preview-item';
                    div.innerHTML = `
                    <img src="${e.target.result}" alt="${file.name}">
                    <button type="button" class="remove-image" onclick="removeImage(${index})" title="Xóa ảnh">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="image-name">${file.name}</div>
                `;
                    previewContainer.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        function updateFileInput() {
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            fileInput.files = dt.files;
        }

        // Make removeImage function global
        window.removeImage = function(index) {
            selectedFiles.splice(index, 1);
            updatePreview();
            updateFileInput();
            clearFileError();
        };

        window.resetForm = function() {
            selectedFiles = [];
            previewContainer.innerHTML = '';
            document.getElementById('addRoomForm').reset();

            // Hide room type info
            document.getElementById('roomTypeInfo').style.display = 'none';

            // Clear all error states
            const errorInputs = document.querySelectorAll('.error');
            errorInputs.forEach(input => {
                input.classList.remove('error');
            });

            const errorMessages = document.querySelectorAll('.error-message');
            errorMessages.forEach(msg => {
                if (msg.classList.contains('dynamic-error-message')) {
                    msg.remove();
                } else {
                    msg.style.display = 'none';
                }
            });
        };

        // Trigger room type info display if there's already a selected value (for validation errors)
        if (roomTypeSelect.value) {
            roomTypeSelect.dispatchEvent(new Event('change'));
        }
    });
</script>

@endsection