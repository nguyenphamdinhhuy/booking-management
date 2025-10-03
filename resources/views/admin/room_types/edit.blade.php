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
    <i class="fas fa-edit"></i>
    Chỉnh sửa loại phòng: {{ $roomType->type_name }}
</h1>

<!-- Edit Room Type Form -->
<div class="content-section">
    <form action="{{ route('admin.roomType.update', $roomType->rt_id) }}" enctype="multipart/form-data" method="POST" class="room-form" id="editRoomTypeForm">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <!-- Room Type Name -->
            <div class="form-group">
                <label for="type_name" class="form-label">
                    <i class="fas fa-tag"></i>
                    Tên loại phòng <span class="required">*</span>
                </label>
                <input type="text"
                    id="type_name"
                    name="type_name"
                    class="form-input {{ $errors->has('type_name') ? 'error' : '' }}"
                    value="{{ old('type_name', $roomType->type_name) }}"
                    placeholder="VD: Phòng Deluxe, Suite Executive..."
                    maxlength="100">
                @if($errors->has('type_name'))
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first('type_name') }}
                </div>
                @endif
            </div>

            <!-- Base Price -->
            <div class="form-group">
                <label for="base_price" class="form-label">
                    <i class="fas fa-dollar-sign"></i>
                    Giá cơ bản mỗi đêm (VNĐ) <span class="required">*</span>
                </label>
                <input type="number"
                    id="base_price"
                    name="base_price"
                    class="form-input {{ $errors->has('base_price') ? 'error' : '' }}"
                    value="{{ old('base_price', $roomType->base_price) }}"
                    placeholder="500000"
                    min="0"
                    step="1000">
                @if($errors->has('base_price'))
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first('base_price') }}
                </div>
                @endif
            </div>

            <!-- Max Guests -->
            <div class="form-group">
                <label for="max_guests" class="form-label">
                    <i class="fas fa-users"></i>
                    Số khách tối đa <span class="required">*</span>
                </label>
                <select id="max_guests"
                    name="max_guests"
                    class="form-select {{ $errors->has('max_guests') ? 'error' : '' }}">
                    <option value="">Chọn số khách</option>
                    @for($i = 1; $i <= 20; $i++)
                        <option value="{{ $i }}" {{ old('max_guests', $roomType->max_guests) == $i ? 'selected' : '' }}>
                        {{ $i }} khách
                        </option>
                        @endfor
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
                    Số giường <span class="required">*</span>
                </label>
                <select id="number_beds"
                    name="number_beds"
                    class="form-select {{ $errors->has('number_beds') ? 'error' : '' }}">
                    <option value="">Chọn số giường</option>
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ old('number_beds', $roomType->number_beds) == $i ? 'selected' : '' }}>
                        {{ $i }} giường
                        </option>
                        @endfor
                </select>
                @if($errors->has('number_beds'))
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first('number_beds') }}
                </div>
                @endif
            </div>

            <!-- Room Size -->
            <div class="form-group">
                <label for="room_size" class="form-label">
                    <i class="fas fa-expand-arrows-alt"></i>
                    Diện tích phòng
                </label>
                <input type="text"
                    id="room_size"
                    name="room_size"
                    class="form-input {{ $errors->has('room_size') ? 'error' : '' }}"
                    value="{{ old('room_size', $roomType->room_size) }}"
                    placeholder="VD: 30m², 45 m2..."
                    maxlength="50">
                @if($errors->has('room_size'))
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first('room_size') }}
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
                    <option value="1" {{ old('status', $roomType->status) == '1' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('status', $roomType->status) == '0' ? 'selected' : '' }}>Không hoạt động</option>
                </select>
                @if($errors->has('status'))
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first('status') }}
                </div>
                @endif
            </div>

            <!-- Amenities -->
            {{-- Amenities (nạp động từ AmenityController) --}}
            @php
            // Lấy danh sách tiện nghi đang có của loại phòng (array)
            $currentAmenities = is_array($roomType->amenities)
            ? $roomType->amenities
            : (json_decode($roomType->amenities, true) ?: []);
            @endphp

            <div class="form-group full-width">
                <label class="form-label">
                    <i class="fas fa-concierge-bell"></i>
                    Tiện nghi phòng
                </label>

                <div id="amenityPicker"
                    class="amenity-picker"
                    data-selected='@json(old("amenities", $currentAmenities))'>

                    {{-- Ô nhập & nút thêm tiện nghi mới --}}
                    <div class="custom-amenity-input" style="margin-bottom:12px; display:flex; gap:10px;">
                        <input type="text" id="amenityInput"
                            placeholder="Nhập tiện nghi mới... (VD: Máy lọc không khí)"
                            maxlength="100"
                            style="flex:1;height:44px;padding:0 12px;border:1.5px solid #d0d5dd;border-radius:10px;">
                        <button type="button" class="btn-add-amenity" id="amenityAddBtn" title="Thêm tiện nghi"
                            style="width:44px;height:44px;border:none;border-radius:10px;background:#0d6efd;color:#fff;display:inline-flex;align-items:center;justify-content:center;">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>

                    <div id="amenityError" class="error-message" style="display:none;">
                        <i class="fas fa-exclamation-circle"></i>
                        <span></span>
                    </div>

                    {{-- Danh sách checkbox tiện nghi nạp bằng JS --}}
                    <div id="amenityList" class="amenities-grid" style="min-height:40px;"></div>
                </div>

                @if($errors->has('amenities'))
                <div class="error-message" style="margin-top:8px;">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first('amenities') }}
                </div>
                @endif
            </div>


            <!-- Current Images Display -->
            @if($roomType->images)
            <div class="form-group full-width">
                <label class="form-label">
                    <i class="fas fa-images"></i>
                    Hình ảnh hiện tại
                </label>
                <div class="current-images-grid">
                    @php
                    $currentImages = json_decode($roomType->images, true) ?: [];
                    @endphp
                    @foreach($currentImages as $index => $image)
                    <div class="current-image-item">
                        <img src="{{ asset($image) }}" alt="Room image {{ $index + 1 }}">
                        <div class="image-name">{{ basename($image) }}</div>
                    </div>
                    @endforeach
                </div>
                <div class="image-note">
                    <i class="fas fa-info-circle"></i>
                    <span>Nếu bạn tải lên ảnh mới, tất cả ảnh cũ sẽ được thay thế.</span>
                </div>
            </div>
            @endif

            <!-- Multiple Images Upload -->
            <div class="form-group full-width">
                <label for="images" class="form-label">
                    <i class="fas fa-cloud-upload-alt"></i>
                    {{ $roomType->images ? 'Thay thế hình ảnh' : 'Thêm hình ảnh loại phòng' }}
                    <span class="text-muted">(Tối đa 10 ảnh - Tùy chọn)</span>
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
                        @if($roomType->images)
                        <p class="file-warning"><i class="fas fa-exclamation-triangle"></i> Lưu ý: Chọn ảnh mới sẽ thay thế toàn bộ ảnh hiện tại</p>
                        @endif
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
                    Mô tả loại phòng
                </label>
                <textarea id="description"
                    name="description"
                    class="form-textarea {{ $errors->has('description') ? 'error' : '' }}"
                    placeholder="Mô tả chi tiết về loại phòng, đặc điểm nổi bật, không gian..."
                    rows="5">{{ old('description', $roomType->description) }}</textarea>
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
            <a href="{{ route('admin.roomType.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Quay lại danh sách
            </a>
            <button type="button" class="btn btn-outline" onclick="resetForm()">
                <i class="fas fa-undo"></i>
                Khôi phục
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Cập nhật loại phòng
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

    /* Current Images Styles */
    .current-images-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e1e5e9;
    }

    .current-image-item {
        position: relative;
        border: 2px solid #28a745;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
    }

    .current-image-item img {
        width: 100%;
        height: 120px;
        object-fit: cover;
    }

    .current-image-item .image-name {
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

    .image-note {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px;
        background: #e3f2fd;
        border: 1px solid #bbdefb;
        border-radius: 6px;
        font-size: 0.9rem;
        color: #1565c0;
        margin-bottom: 15px;
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
        border: 2px solid #007bff;
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

    .file-warning {
        color: #ff9800 !important;
        font-weight: 500;
        margin-top: 5px !important;
    }

    .text-muted {
        color: #6c757d;
        font-size: 0.9em;
    }

    /* Amenities Styles */
    .amenities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 10px;
        margin-bottom: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e1e5e9;
    }

    .amenity-item {
        margin: 0;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        cursor: pointer;
        font-size: 0.9rem;
        color: #495057;
        transition: color 0.3s;
        position: relative;
        padding-left: 25px;
    }

    .checkbox-label:hover {
        color: #007bff;
    }

    .checkbox-label input[type="checkbox"] {
        position: absolute;
        left: 0;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .checkmark {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        height: 18px;
        width: 18px;
        background-color: #fff;
        border: 2px solid #dee2e6;
        border-radius: 3px;
        transition: all 0.3s;
    }

    .checkbox-label:hover input~.checkmark {
        border-color: #007bff;
    }

    .checkbox-label input:checked~.checkmark {
        background-color: #007bff;
        border-color: #007bff;
    }

    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    .checkbox-label input:checked~.checkmark:after {
        display: block;
    }

    .checkbox-label .checkmark:after {
        left: 5px;
        top: 2px;
        width: 4px;
        height: 8px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    /* Form Grid Styles */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        font-weight: 600;
        color: #495057;
        font-size: 0.95rem;
    }

    .form-label .required {
        color: #dc3545;
    }

    .form-input,
    .form-select,
    .form-textarea {
        padding: 10px 15px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 0.95rem;
        transition: all 0.3s;
        background: #fff;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        padding: 20px 0;
        border-top: 1px solid #e1e5e9;
        margin-top: 30px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-size: 0.95rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-primary {
        background: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background: #0056b3;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #545b62;
    }

    .btn-outline {
        background: transparent;
        color: #6c757d;
        border: 1px solid #dee2e6;
    }

    .btn-outline:hover {
        background: #f8f9fa;
        border-color: #adb5bd;
    }

    /* Content Section */
    .content-section {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 30px;
    }

    .page-title {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #495057;
        margin-bottom: 30px;
        font-size: 1.8rem;
        font-weight: 600;
    }

    /* Alert Styles */
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
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

    /* Responsive */
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .amenities-grid {
            grid-template-columns: 1fr;
        }

        .current-images-grid {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        }

        .form-actions {
            flex-direction: column;
            gap: 10px;
        }

        .btn {
            justify-content: center;
        }

        .content-section {
            padding: 20px;
        }
    }
</style>

<script>
    let selectedFiles = [];
    const MAX_FILES = 10;
    const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB

    // Store original form values for reset functionality
    const originalFormData = {
        type_name: "{{ $roomType->type_name }}",
        base_price: "{{ $roomType->base_price }}",
        max_guests: "{{ $roomType->max_guests }}",
        number_beds: "{{ $roomType->number_beds }}",
        room_size: "{{ $roomType->room_size ?? '' }}",
        status: "{{ $roomType->status }}",
        description: "{{ $roomType->description ?? '' }}",
        amenities: @json($currentAmenities)
    };

    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('images');
        const uploadArea = document.querySelector('.file-upload-area');
        const previewContainer = document.getElementById('imagePreview');

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

        // Reset form to original values
        window.resetForm = function() {
            // Reset file uploads
            selectedFiles = [];
            previewContainer.innerHTML = '';
            fileInput.value = '';

            // Reset form fields to original values
            document.getElementById('type_name').value = originalFormData.type_name;
            document.getElementById('base_price').value = originalFormData.base_price;
            document.getElementById('max_guests').value = originalFormData.max_guests;
            document.getElementById('number_beds').value = originalFormData.number_beds;
            document.getElementById('room_size').value = originalFormData.room_size;
            document.getElementById('status').value = originalFormData.status;
            document.getElementById('description').value = originalFormData.description;

            // Reset amenities checkboxes
            const checkboxes = document.querySelectorAll('input[name="amenities[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = originalFormData.amenities.includes(checkbox.value);
            });

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

            // Show confirmation
            showResetConfirmation();
        };

        function showResetConfirmation() {
            // Create and show a temporary success message
            const existingAlert = document.querySelector('.reset-confirmation');
            if (existingAlert) {
                existingAlert.remove();
            }

            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success reset-confirmation';
            alertDiv.innerHTML = `
                <i class="fas fa-check-circle"></i>
                Form đã được khôi phục về trạng thái ban đầu!
            `;

            const pageTitle = document.querySelector('.page-title');
            pageTitle.insertAdjacentElement('afterend', alertDiv);

            // Auto remove after 3 seconds
            setTimeout(() => {
                if (alertDiv) {
                    alertDiv.remove();
                }
            }, 3000);
        }

        // Form submission confirmation
        document.getElementById('editRoomTypeForm').addEventListener('submit', function(e) {
            const hasNewImages = selectedFiles.length > 0;
            const hasCurrentImages = document.querySelector('.current-images-grid') !== null;

            if (hasNewImages && hasCurrentImages) {
                if (!confirm('Bạn có chắc chắn muốn thay thế toàn bộ ảnh hiện tại bằng ảnh mới không?')) {
                    e.preventDefault();
                    return false;
                }
            }
        });

        // Auto-save draft functionality (optional)
        const draftSaveTimer = setInterval(() => {
            saveDraft();
        }, 30000); // Save every 30 seconds

        function saveDraft() {
            const formData = {
                type_name: document.getElementById('type_name').value,
                base_price: document.getElementById('base_price').value,
                max_guests: document.getElementById('max_guests').value,
                number_beds: document.getElementById('number_beds').value,
                room_size: document.getElementById('room_size').value,
                status: document.getElementById('status').value,
                description: document.getElementById('description').value,
                amenities: Array.from(document.querySelectorAll('input[name="amenities[]"]:checked')).map(cb => cb.value),
                timestamp: Date.now()
            };

            // Check if there are changes
            const hasChanges = JSON.stringify(formData) !== JSON.stringify({
                ...originalFormData,
                timestamp: formData.timestamp
            });

            if (hasChanges) {
                localStorage.setItem('roomTypeEditDraft_{{ $roomType->rt_id }}', JSON.stringify(formData));
            }
        }

        // Load draft on page load
        function loadDraft() {
            const draftKey = 'roomTypeEditDraft_{{ $roomType->rt_id }}';
            const draft = localStorage.getItem(draftKey);

            if (draft) {
                const draftData = JSON.parse(draft);
                const draftAge = Date.now() - draftData.timestamp;
                const maxAge = 24 * 60 * 60 * 1000; // 24 hours

                if (draftAge < maxAge) {
                    if (confirm('Có bản nháp được lưu tự động. Bạn có muốn khôi phục không?')) {
                        // Apply draft data to form
                        Object.keys(draftData).forEach(key => {
                            if (key === 'amenities') {
                                const checkboxes = document.querySelectorAll('input[name="amenities[]"]');
                                checkboxes.forEach(checkbox => {
                                    checkbox.checked = draftData.amenities.includes(checkbox.value);
                                });
                            } else if (key !== 'timestamp') {
                                const element = document.getElementById(key);
                                if (element) {
                                    element.value = draftData[key];
                                }
                            }
                        });
                    } else {
                        localStorage.removeItem(draftKey);
                    }
                } else {
                    localStorage.removeItem(draftKey);
                }
            }
        }

        // Load draft when page loads
        loadDraft();

        // Clear draft when form is successfully submitted
        window.addEventListener('beforeunload', function() {
            // Only clear draft if form was submitted successfully
            if (document.querySelector('.alert-success')) {
                localStorage.removeItem('roomTypeEditDraft_{{ $roomType->rt_id }}');
            }
        });
    });

    // Price formatting
    document.getElementById('base_price').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value) {
            // Format number with thousand separators for display
            const displayValue = parseInt(value).toLocaleString('vi-VN');
            // Update display in a separate element if needed
        }
    });

    // Character count for description
    const descriptionTextarea = document.getElementById('description');
    const maxLength = 1000; // Set your desired max length

    function updateCharCount() {
        const currentLength = descriptionTextarea.value.length;
        let charCountElement = document.getElementById('description-char-count');

        if (!charCountElement) {
            charCountElement = document.createElement('div');
            charCountElement.id = 'description-char-count';
            charCountElement.className = 'char-count';
            descriptionTextarea.parentNode.appendChild(charCountElement);
        }

        charCountElement.textContent = `${currentLength}/${maxLength} ký tự`;
        charCountElement.style.color = currentLength > maxLength * 0.8 ? '#dc3545' : '#6c757d';
    }

    if (descriptionTextarea) {
        descriptionTextarea.addEventListener('input', updateCharCount);
        updateCharCount(); // Initialize count
    }


    (function() {
        const CSRF = (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) || '{{ csrf_token() }}';
        let pickerEl, listEl, inputEl, addBtn, errEl, errText;
        let selected = [];

        document.addEventListener('DOMContentLoaded', () => {
            pickerEl = document.getElementById('amenityPicker');
            if (!pickerEl) return;

            listEl = document.getElementById('amenityList');
            inputEl = document.getElementById('amenityInput');
            addBtn = document.getElementById('amenityAddBtn');
            errEl = document.getElementById('amenityError');
            errText = errEl?.querySelector('span');

            try {
                selected = JSON.parse(pickerEl.dataset.selected || '[]');
            } catch (e) {
                selected = [];
            }

            if (addBtn) addBtn.addEventListener('click', onAddAmenity);

            loadAmenities();

            // Khi bạn bấm nút "Khôi phục" của form, reset cả amenity
            if (typeof window.resetForm === 'function') {
                const oldReset = window.resetForm;
                window.resetForm = function() {
                    oldReset();
                    // Reset selected về ban đầu từ originalFormData.amenities
                    try {
                        selected = (window.originalFormData?.amenities) || [];
                    } catch (e) {
                        selected = [];
                    }
                    loadAmenities();
                };
            }
        });

        function showAmenityError(msg) {
            if (!errEl) return;
            errText.textContent = msg;
            errEl.style.display = 'flex';
        }

        function hideAmenityError() {
            if (!errEl) return;
            errEl.style.display = 'none';
            errText.textContent = '';
        }

        function checkboxTemplate(name, checked) {
            const safe = String(name);
            const id = 'am_' + btoa(unescape(encodeURIComponent(safe))).replace(/=+/g, '');
            return `
            <div class="amenity-item">
                <label class="checkbox-label" for="${id}">
                    <input type="checkbox" id="${id}" class="amenity-checkbox"
                           name="amenities[]"
                           value="${safe.replace(/"/g,'&quot;')}"
                           ${checked ? 'checked' : ''}>
                    <span class="checkmark"></span>
                    <span class="amenity-text">${safe}</span>
                </label>
                <button type="button" class="amenity-delete" data-delete="${safe.replace(/"/g,'&quot;')}" title="Xoá khỏi danh sách chuẩn">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        }

        function renderAmenities(items) {
            listEl.innerHTML = '';
            if (!items || !items.length) {
                listEl.innerHTML = `<div style="color:#6c757d;">Chưa có tiện nghi nào.</div>`;
                return;
            }
            const lowerSelected = selected.map(s => String(s).toLowerCase());
            listEl.innerHTML = items
                .map(n => checkboxTemplate(n, lowerSelected.includes(String(n).toLowerCase())))
                .join('');
            bindDeleteButtons();
        }

        function bindDeleteButtons() {
            listEl.querySelectorAll('button.amenity-delete').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const name = btn.getAttribute('data-delete');
                    if (!confirm(`Xoá "${name}" khỏi danh sách chuẩn?`)) return;
                    hideAmenityError();
                    try {
                        const res = await fetch(`{{ route('admin.amenities.delete') }}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': CSRF,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                name
                            })
                        });
                        const data = await res.json();
                        if (!res.ok || data.ok !== true) {
                            showAmenityError(data.message || 'Không xoá được tiện nghi.');
                            return;
                        }
                        renderAmenities(data.amenities || []);
                    } catch (e) {
                        showAmenityError('Lỗi mạng khi xoá tiện nghi.');
                    }
                });
            });
        }

        async function loadAmenities() {
            hideAmenityError();
            try {
                const res = await fetch(`{{ route('admin.amenities.index') }}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                if (data.ok !== true) {
                    showAmenityError('Không tải được danh sách tiện nghi.');
                    return;
                }
                renderAmenities(data.amenities || []);
            } catch (e) {
                showAmenityError('Lỗi mạng khi tải tiện nghi.');
            }
        }

        async function onAddAmenity() {
            const name = (inputEl.value || '').trim();
            if (!name) {
                showAmenityError('Tên tiện nghi không được để trống');
                return;
            }
            hideAmenityError();
            try {
                const res = await fetch(`{{ route('admin.amenities.store') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name
                    })
                });
                const data = await res.json();
                if (!res.ok || data.ok !== true) {
                    showAmenityError(data.message || 'Không thêm được tiện nghi.');
                    return;
                }
                inputEl.value = '';
                renderAmenities(data.amenities || []);
                // tự tick tiện nghi vừa thêm
                selected.push(name);
                // tick lại checkbox vừa render
                const esc = window.CSS && CSS.escape ? CSS.escape(name) : name.replace(/"/g, '\\"');
                const chk = listEl.querySelector(`input[type="checkbox"][value="${esc}"]`);
                if (chk) chk.checked = true;
            } catch (e) {
                showAmenityError('Lỗi mạng khi thêm tiện nghi.');
            }
        }

        // expose nếu cần dùng lại
        window.loadAmenities = loadAmenities;
    })();
</script>

<style>
    /* Lưới tiện nghi: ô đều, responsive */
    .amenities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 12px;
        padding: 15px;
        background: #f8f9fa;
        border: 1px solid #e1e5e9;
        border-radius: 8px;
    }

    /* Một item tiện nghi là 1 hàng flex, thẳng hàng giữa */
    .amenity-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 8px;
    }

    /* Nhãn checkbox chiếm hết chiều ngang, text ellipsis nếu dài */
    .amenity-item .checkbox-label {
        flex: 1;
        min-width: 0;
        padding-left: 28px;
        position: relative;
        display: flex;
        align-items: center;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #495057;
    }

    .amenity-text {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Checkmark */
    .checkbox-label .checkmark {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        height: 18px;
        width: 18px;
        background: #fff;
        border: 2px solid #dee2e6;
        border-radius: 3px;
        transition: border-color .2s, background-color .2s;
    }

    .checkbox-label:hover .checkmark {
        border-color: #007bff;
    }

    .checkbox-label input:checked~.checkmark {
        background: #007bff;
        border-color: #007bff;
    }

    .checkbox-label .checkmark:after {
        content: "";
        position: absolute;
        left: 5px;
        top: 2px;
        width: 4px;
        height: 8px;
        border: solid #fff;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
        display: none;
    }

    .checkbox-label input:checked~.checkmark:after {
        display: block;
    }

    /* Nút xoá: nằm sát bên phải, đồng mức với chữ */
    .amenity-delete {
        margin-left: auto;
        width: 32px;
        height: 32px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        background: #fff;
        color: #6c757d;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color .2s, color .2s, border-color .2s, transform .1s;
        padding: 0;
    }

    .amenity-delete:hover {
        background: #f8f9fa;
        color: #dc3545;
        border-color: #f1aeb5;
    }

    .amenity-delete:active {
        transform: scale(.98);
    }

    @media (max-width: 576px) {
        .amenities-grid {
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        }
    }

    /* Additional styles for edit-specific features */
    .char-count {
        font-size: 0.8rem;
        text-align: right;
        margin-top: 5px;
        transition: color 0.3s;
    }

    .reset-confirmation {
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Draft indicator */
    .draft-indicator {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: rgba(0, 123, 255, 0.9);
        color: white;
        padding: 8px 12px;
        border-radius: 4px;
        font-size: 0.8rem;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .draft-indicator.show {
        opacity: 1;
    }
</style>

@endsection