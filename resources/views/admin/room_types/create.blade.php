@extends('admin.layouts.master')

@section('content')

{{-- Alerts --}}
@if(session('success'))
<div class="alert alert-success" style="background:#d4edda;color:#155724;padding:10px;border:1px solid #c3e6cb;border-radius:4px;margin-bottom:20px;">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger" style="background:#f8d7da;color:#721c24;padding:10px;border:1px solid #f5c6cb;border-radius:4px;margin-bottom:20px;">
    {{ session('error') }}
</div>
@endif

<h1 class="page-title">
    <i class="fas fa-bed"></i>
    Thêm loại phòng mới
</h1>

<div class="content-section">
    <form action="{{ route('admin.roomType.store') }}"
        enctype="multipart/form-data"
        method="POST"
        class="room-form"
        id="addRoomTypeForm">
        @csrf

        <div class="form-grid">
            {{-- Tên loại phòng --}}
            <div class="form-group">
                <label for="type_name" class="form-label">
                    <i class="fas fa-tag"></i>
                    Tên loại phòng <span class="required">*</span>
                </label>
                <input type="text"
                    id="type_name"
                    name="type_name"
                    class="form-input {{ $errors->has('type_name') ? 'error' : '' }}"
                    value="{{ old('type_name') }}"
                    placeholder="VD: Phòng Deluxe, Suite Executive..."
                    maxlength="100">
                @if($errors->has('type_name'))
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first('type_name') }}
                </div>
                @endif
            </div>

            {{-- Giá cơ bản --}}
            <div class="form-group">
                <label for="base_price" class="form-label">
                    <i class="fas fa-dollar-sign"></i>
                    Giá cơ bản mỗi đêm (VNĐ) <span class="required">*</span>
                </label>
                <input type="number"
                    id="base_price"
                    name="base_price"
                    class="form-input {{ $errors->has('base_price') ? 'error' : '' }}"
                    value="{{ old('base_price') }}"
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

            {{-- Số khách --}}
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
                        <option value="{{ $i }}" {{ old('max_guests') == $i ? 'selected' : '' }}>
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

            {{-- Số giường --}}
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
                        <option value="{{ $i }}" {{ old('number_beds') == $i ? 'selected' : '' }}>
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

            {{-- Diện tích --}}
            <div class="form-group">
                <label for="room_size" class="form-label">
                    <i class="fas fa-expand-arrows-alt"></i>
                    Diện tích phòng
                </label>
                <input type="text"
                    id="room_size"
                    name="room_size"
                    class="form-input {{ $errors->has('room_size') ? 'error' : '' }}"
                    value="{{ old('room_size') }}"
                    placeholder="VD: 30m², 45 m2..."
                    maxlength="50">
                @if($errors->has('room_size'))
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first('room_size') }}
                </div>
                @endif
            </div>

            {{-- Trạng thái --}}
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

            {{-- Amenity Picker động --}}
            <div class="form-group full-width">
                <label class="form-label">
                    <i class="fas fa-concierge-bell"></i>
                    Tiện nghi phòng
                </label>

                <div id="amenityPicker"
                    class="amenity-picker"
                    data-selected='@json(old("amenities", []))'>

                    <div class="custom-amenity-section" style="margin-top:0;padding:0;border:none;">
                        <div class="custom-amenity-input" style="margin-bottom:12px;">
                            <input type="text" id="amenityInput"
                                placeholder="Nhập tiện nghi mới... (VD: Máy lọc không khí)"
                                maxlength="100">
                            <button type="button" class="btn-add-amenity" id="amenityAddBtn" title="Thêm tiện nghi">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div id="amenityError" class="error-message" style="display:none;">
                            <i class="fas fa-exclamation-circle"></i>
                            <span></span>
                        </div>
                    </div>

                    <div id="amenityList" class="amenities-grid" style="min-height:40px;">
                        {{-- checkboxes render bằng JS --}}
                    </div>
                </div>

                @if($errors->has('amenities'))
                <div class="error-message" style="margin-top:8px;">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first('amenities') }}
                </div>
                @endif
            </div>

            {{-- Upload nhiều ảnh --}}
            <div class="form-group full-width">
                <label for="images" class="form-label">
                    <i class="fas fa-images"></i>
                    Hình ảnh loại phòng
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
                        style="display:none;">
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

            {{-- Mô tả --}}
            <div class="form-group full-width">
                <label for="description" class="form-label">
                    <i class="fas fa-align-left"></i>
                    Mô tả loại phòng
                </label>
                <textarea id="description"
                    name="description"
                    class="form-textarea {{ $errors->has('description') ? 'error' : '' }}"
                    placeholder="Mô tả chi tiết về loại phòng, đặc điểm nổi bật, không gian..."
                    rows="5">{{ old('description') }}</textarea>
                @if($errors->has('description'))
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first('description') }}
                </div>
                @endif
            </div>
        </div>

        {{-- Actions --}}
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
                Lưu loại phòng
            </button>
        </div>
    </form>
</div>

<style>
    #amenityInput {
        width: 100%;
    }

    .custom-amenity-input {
        display: flex;
        gap: 10px;
    }

    .amenity-inline {
        display: flex;
        align-items: center;
        gap: 10px;
        max-width: 100%;
    }

    /* Input */
    #amenityInput {
        flex: 1;
        height: 44px;
        padding: 0 12px;
        border: 1.5px solid #d0d5dd;
        /* xám nhạt */
        border-radius: 10px;
        background: #fff;
        color: #1f2937;
        font-size: 14px;
        transition: border-color .2s, box-shadow .2s, background-color .2s;
    }

    #amenityInput::placeholder {
        color: #98a2b3;
    }

    #amenityInput:focus {
        outline: none;
        border-color: #0d6efd;
        /* xanh primary */
        box-shadow: 0 0 0 3px rgba(13, 110, 253, .15);
        background: #fff;
    }

    /* Nút thêm (+) */
    .btn-add-amenity {
        width: 44px;
        height: 44px;
        border: none;
        border-radius: 10px;
        background: #0d6efd;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform .15s ease, background-color .2s ease, box-shadow .2s ease;
        box-shadow: 0 2px 6px rgba(13, 110, 253, .25);
    }

    .btn-add-amenity:hover {
        background: #0b5ed7;
    }

    .btn-add-amenity:active {
        transform: scale(.98);
    }

    .btn-add-amenity:disabled {
        background: #cbd5e1;
        box-shadow: none;
        cursor: not-allowed;
        color: #fff;
    }

    .btn-add-amenity i {
        pointer-events: none;
        font-size: 16px;
    }

    /* Hint & error */
    .amenity-hint {
        display: block;
        margin-top: 6px;
        color: #6b7280;
        font-size: 12px;
    }

    .amenity-error {
        display: block;
        margin-top: 6px;
        color: #dc3545;
        font-size: 12px;
    }

    /* Mobile */
    @media (max-width: 480px) {
        .amenity-inline {
            max-width: 100%;
            gap: 8px;
        }

        #amenityInput {
            height: 42px;
        }

        .btn-add-amenity {
            width: 42px;
            height: 42px;
        }
    }

    /* (Tuỳ chọn) Hỗ trợ dark mode nếu site dùng dark */
    @media (prefers-color-scheme: dark) {
        #amenityInput {}

        #amenityInput::placeholder {
            color: #6b7280;
        }
    }

    /* Errors */
    .error-message {
        color: #dc3545;
        font-size: .875rem;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 5px
    }

    .error-message i {
        font-size: .8rem
    }

    .form-input.error,
    .form-select.error,
    .form-textarea.error {
        border-color: #dc3545;
        box-shadow: 0 0 0 .2rem rgba(220, 53, 69, .25)
    }

    .file-upload-area.error {
        border-color: #dc3545;
        background: #f8d7da
    }

    /* Preview grid */
    .image-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 15px
    }

    .image-preview-item {
        position: relative;
        border: 2px solid #e1e5e9;
        border-radius: 8px;
        overflow: hidden;
        background: #f8f9fa
    }

    .image-preview-item img {
        width: 100%;
        height: 120px;
        object-fit: cover
    }

    .image-preview-item .remove-image {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(220, 53, 69, .9);
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        transition: .3s
    }

    .image-preview-item .remove-image:hover {
        background: rgba(220, 53, 69, 1)
    }

    .image-preview-item .image-name {
        padding: 8px;
        font-size: 12px;
        color: #6c757d;
        background: #fff;
        border-top: 1px solid #e1e5e9;
        text-align: center;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap
    }

    /* Upload area */
    .file-upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 40px 20px;
        text-align: center;
        background: #f8f9fa;
        cursor: pointer;
        transition: .3s
    }

    .file-upload-area:hover {
        border-color: #007bff;
        background: #e3f2fd
    }

    .file-upload-area.dragover {
        border-color: #007bff;
        background: #e3f2fd;
        transform: scale(1.02)
    }

    .text-muted {
        color: #6c757d;
        font-size: .9em
    }

    /* Lưới tiện nghi: ô đều, căng đầy hàng, đẹp trên mọi màn hình */
    .amenities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 12px;
        padding: 15px;
        background: #f8f9fa;
        border: 1px solid #e1e5e9;
        border-radius: 8px;
    }

    /* Mỗi tiện nghi là 1 hàng flex, căn giữa theo trục dọc */
    .amenity-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 8px;
    }

    /* Nhãn checkbox chiếm hết chiều ngang, text gọn & ellipsis nếu dài */
    .amenity-item .checkbox-label {
        flex: 1;
        min-width: 0;
        /* cho phép ellipsis hoạt động */
        padding-left: 28px;
        /* chừa chỗ cho .checkmark */
        position: relative;
        display: flex;
        align-items: center;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #495057;
    }

    /* Ô vuông của checkbox canh giữa theo chiều dọc */
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

    /* Nút xoá: nằm sát nhãn, đẩy về mép phải, đồng mức với chữ */
    .amenities-grid .amenity-item>button[data-delete] {
        margin-left: auto;
        /* đẩy sát bên phải của ô */
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
        /* gọn nút */
    }

    .amenities-grid .amenity-item>button[data-delete]:hover {
        background: #f8f9fa;
        color: #dc3545;
        border-color: #f1aeb5;
    }

    .amenities-grid .amenity-item>button[data-delete]:active {
        transform: scale(.98);
    }

    /* Mobile fix: cột nhỏ hơn cho màn hình hẹp */
    @media (max-width: 576px) {
        .amenities-grid {
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        }
    }

    /* Form layout */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 30px
    }

    .form-group {
        display: flex;
        flex-direction: column
    }

    .form-group.full-width {
        grid-column: 1/-1
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        font-weight: 600;
        color: #495057;
        font-size: .95rem
    }

    .form-label .required {
        color: #dc3545
    }

    .form-input,
    .form-select,
    .form-textarea {
        padding: 10px 15px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: .95rem;
        transition: .3s;
        background: #fff
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 .2rem rgba(0, 123, 255, .25)
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px
    }

    .content-section {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .1);
        padding: 30px;
        margin-bottom: 30px
    }

    .page-title {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #495057;
        margin-bottom: 30px;
        font-size: 1.8rem;
        font-weight: 600
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        padding: 20px 0;
        border-top: 1px solid #e1e5e9;
        margin-top: 30px
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-size: .95rem;
        font-weight: 500;
        cursor: pointer;
        transition: .3s;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none
    }

    .btn-primary {
        background: #007bff;
        color: #fff
    }

    .btn-primary:hover {
        background: #0056b3;
        transform: translateY(-1px)
    }

    .btn-secondary {
        background: #6c757d;
        color: #fff
    }

    .btn-secondary:hover {
        background: #545b62
    }

    .btn-outline {
        background: transparent;
        color: #6c757d;
        border: 1px solid #dee2e6
    }

    .btn-outline:hover {
        background: #f8f9fa;
        border-color: #adb5bd
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px)
        }

        to {
            opacity: 1;
            transform: translateY(0)
        }
    }

    .error-message {
        animation: fadeIn .3s ease-in-out
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
            gap: 15px
        }

        .amenities-grid {
            grid-template-columns: 1fr
        }

        .form-actions {
            flex-direction: column;
            gap: 10px
        }

        .btn {
            justify-content: center
        }

        .content-section {
            padding: 20px
        }
    }
</style>

<script>
    // ========= Upload nhiều ảnh + preview =========
    let selectedFiles = [];
    const MAX_FILES = 10;
    const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB

    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('images');
        const uploadArea = document.querySelector('.file-upload-area');
        const previewContainer = document.getElementById('imagePreview');

        fileInput.addEventListener('change', function(e) {
            handleFiles(e.target.files);
        });

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
            if (errorMessage) errorMessage.style.display = 'none';
        }

        function handleFiles(files) {
            const fileArray = Array.from(files);

            if (selectedFiles.length + fileArray.length > MAX_FILES) {
                showFileError(`Chỉ được chọn tối đa ${MAX_FILES} ảnh. Hiện tại bạn đã chọn ${selectedFiles.length} ảnh.`);
                return;
            }

            fileArray.forEach(file => {
                if (!file.type.startsWith('image/')) {
                    showFileError(`File "${file.name}" không phải là hình ảnh.`);
                    return;
                }
                if (file.size > MAX_FILE_SIZE) {
                    showFileError(`File "${file.name}" quá lớn. Tối đa 5MB.`);
                    return;
                }
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
            uploadArea.classList.add('error');
            const existingError = uploadArea.parentNode.querySelector('.dynamic-error-message');
            if (existingError) existingError.remove();
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message dynamic-error-message';
            errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
            uploadArea.parentNode.insertBefore(errorDiv, document.getElementById('imagePreview'));
        }

        function clearFileError() {
            uploadArea.classList.remove('error');
            const dynamicError = uploadArea.parentNode.querySelector('.dynamic-error-message');
            if (dynamicError) dynamicError.remove();
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

        window.removeImage = function(index) {
            selectedFiles.splice(index, 1);
            updatePreview();
            updateFileInput();
            clearFileError();
        };

        window.resetForm = function() {
            selectedFiles = [];
            previewContainer.innerHTML = '';
            document.getElementById('addRoomTypeForm').reset(); // <-- fixed id

            document.querySelectorAll('.error').forEach(el => el.classList.remove('error'));
            document.querySelectorAll('.error-message').forEach(msg => {
                if (msg.classList.contains('dynamic-error-message')) {
                    msg.remove();
                } else {
                    msg.style.display = 'none';
                }
            });

            // reload amenities sau reset
            if (typeof loadAmenities === 'function') loadAmenities();
        };
    });

    // ========= Amenity Picker (fetch từ AmenityController) =========
    (function() {
        const CSRF = (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) || '{{ csrf_token() }}';
        let pickerEl, listEl, inputEl, addBtn, errEl, errText, selected = [];

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

            addBtn.addEventListener('click', onAddAmenity);
            loadAmenities();
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
                <div class="amenity-item" style="align-items: center;">
                    <label class="checkbox-label" for="${id}">
                        <input type="checkbox" id="${id}" class="amenity-checkbox"
                               name="amenities[]"
                               value="${safe.replace(/"/g,'&quot;')}"
                               ${checked ? 'checked' : ''}>
                        <span class="checkmark"></span>
                        ${safe}
                    </label>
                    <button type="button" class="btn btn-outline" data-delete="${safe.replace(/"/g,'&quot;')}"
                            style="margin-left:auto;padding:6px 10px;" title="Xoá khỏi danh sách chuẩn">
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
            listEl.innerHTML = items.map(n => checkboxTemplate(n, lowerSelected.includes(String(n).toLowerCase()))).join('');
            bindDeleteButtons();
        }

        function bindDeleteButtons() {
            listEl.querySelectorAll('button[data-delete]').forEach(btn => {
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
                const esc = window.CSS && CSS.escape ? CSS.escape(name) : name.replace(/"/g, '\\"');
                const chk = listEl.querySelector(`input[type="checkbox"][value="${esc}"]`);
                if (chk) chk.checked = true;
            } catch (e) {
                showAmenityError('Lỗi mạng khi thêm tiện nghi.');
            }
        }

        // để có thể gọi lại sau reset
        window.loadAmenities = loadAmenities;
    })();
</script>

@endsection