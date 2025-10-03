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
    Chỉnh sửa phòng: {{ $room->name }}
    @if($room->type_name)
    <span class="room-type-badge">({{ $room->type_name }})</span>
    @endif
</h1>

<!-- Edit Room Form -->
<div class="content-section">
    <form action="{{ route('admin.rooms.update', $room->r_id) }}" enctype="multipart/form-data" method="POST" class="room-form" id="editRoomForm">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <!-- Room Name -->
            <div class="form-group">
                <label for="name" class="form-label">
                    <i class="fas fa-tag"></i>
                    Tên phòng <span class="required">*</span>
                </label>
                <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $room->name) }}"
                    placeholder="VD: Phòng Deluxe 101" maxlength="50">
            </div>

            <!-- Room Type -->
            <div class="form-group">
                <label for="rt_id" class="form-label">
                    <i class="fas fa-home"></i>
                    Loại phòng <span class="required">*</span>
                </label>
                <select id="rt_id" name="rt_id" class="form-select" required>
                    <option value="">Chọn loại phòng</option>
                    @foreach($roomTypes as $type)
                    <option value="{{ $type->rt_id }}"
                        {{ old('rt_id', $room->rt_id) == $type->rt_id ? 'selected' : '' }}
                        data-base-price="{{ $type->base_price }}"
                        data-max-guests="{{ $type->max_guests }}"
                        data-number-beds="{{ $type->number_beds }}">
                        {{ $type->type_name }}
                        @if($type->base_price)
                        - {{ number_format($type->base_price, 0, ',', '.') }} VNĐ
                        @endif
                    </option>
                    @endforeach
                </select>
                <small class="form-help">Chọn loại phòng sẽ tự động điền một số thông tin mặc định</small>
            </div>

            <!-- Price per Night -->
            <div class="form-group">
                <label for="price_per_night" class="form-label">
                    <i class="fas fa-dollar-sign"></i>
                    Giá mỗi đêm (VNĐ) <span class="required">*</span>
                </label>
                <input type="number" id="price_per_night" name="price_per_night" class="form-input" value="{{ old('price_per_night', $room->price_per_night) }}"
                    placeholder="500000" min="0" step="1000">
            </div>

            <!-- Max Guests -->
            <div class="form-group">
                <label for="max_guests" class="form-label">
                    <i class="fas fa-users"></i>
                    Số khách tối đa
                </label>
                <select id="max_guests" name="max_guests" class="form-select">
                    <option value="">Chọn số khách</option>
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ old('max_guests', $room->max_guests) == $i ? 'selected' : '' }}>
                        {{ $i }} khách
                        </option>
                        @endfor
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
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ old('number_beds', $room->number_beds) == $i ? 'selected' : '' }}>
                        {{ $i }} giường
                        </option>
                        @endfor
                </select>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status" class="form-label">
                    <i class="fas fa-toggle-on"></i>
                    Trạng thái hoạt động
                </label>
                <select id="status" name="status" class="form-select" required>
                    <option value="1" {{ old('status', $room->status) == '1' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('status', $room->status) == '0' ? 'selected' : '' }}>Không hoạt động</option>
                </select>
            </div>

            <!-- Available -->
            <div class="form-group">
                <label for="available" class="form-label">
                    <i class="fas fa-check-circle"></i>
                    Tình trạng sẵn sàng
                </label>
                <select id="available" name="available" class="form-select" required>
                    <option value="1" {{ old('available', $room->available ?? 1) == '1' ? 'selected' : '' }}>Sẵn sàng</option>
                    <option value="0" {{ old('available', $room->available ?? 1) == '0' ? 'selected' : '' }}>Không sẵn sàng</option>
                </select>
            </div>

            <!-- Images Management -->
            <div class="form-group full-width">
                <label class="form-label">
                    <i class="fas fa-images"></i>
                    Quản lý hình ảnh phòng
                </label>

                <!-- Current Images -->
                @if($roomImages->count() > 0)
                <div class="current-images-section" style="margin-bottom: 20px;">
                    <h4 style="margin-bottom: 15px; color: #333;">Ảnh hiện tại:</h4>
                    <div class="images-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
                        @foreach($roomImages as $image)
                        <div class="image-item" style="position: relative; border: 2px solid #e0e0e0; border-radius: 8px; overflow: hidden;">
                            <img src="{{ asset($image->image_path) }}" alt="Room Image"
                                style="width: 100%; height: 150px; object-fit: cover;">

                            <!-- Main Image Indicator -->
                            @if($room->images == $image->image_path)
                            <div class="main-image-badge" style="position: absolute; top: 5px; left: 5px; background: #28a745; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: bold;">
                                Ảnh chính
                            </div>
                            @endif

                            <!-- Image Controls -->
                            <div class="image-controls" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); padding: 8px; display: flex; justify-content: space-between; align-items: center;">
                                <!-- Set as Main Image -->
                                @if($room->images != $image->image_path)
                                <button type="button" class="btn-set-main" data-image-id="{{ $image->id }}"
                                    style="background: #007bff; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 11px; cursor: pointer;">
                                    Đặt làm ảnh chính
                                </button>
                                @else
                                <span style="color: #28a745; font-size: 11px; font-weight: bold;">Ảnh chính</span>
                                @endif

                                <!-- Delete Image -->
                                <button type="button" class="btn-delete-image" data-image-id="{{ $image->id }}"
                                    style="background: #dc3545; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 11px; cursor: pointer;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Upload New Images -->
                <div class="upload-new-images">
                    <h4 style="margin-bottom: 15px; color: #333;">Thêm ảnh mới:</h4>
                    <div class="file-upload-area" style="border: 2px dashed #ccc; border-radius: 8px; padding: 20px; text-align: center; background: #f9f9f9;">
                        <input type="file" id="images" name="images[]" class="file-input" accept="image/*" multiple
                            style="display: none;">
                        <div class="file-upload-content" onclick="document.getElementById('images').click()" style="cursor: pointer;">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: #666; margin-bottom: 10px;"></i>
                            <p style="margin: 10px 0; font-size: 16px; color: #666;">Chọn nhiều ảnh để upload</p>
                            <p class="file-info" style="font-size: 14px; color: #999;">Hỗ trợ: JPG, PNG, GIF (Tối đa 5MB mỗi ảnh)</p>
                        </div>
                    </div>
                    <div id="newImagesPreview" class="images-preview" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; margin-top: 15px;"></div>
                </div>

                <!-- Hidden inputs for form data -->
                <input type="hidden" id="deleteImages" name="delete_images" value="">
                <input type="hidden" id="mainImage" name="main_image" value="">
            </div>

            <!-- Description -->
            <div class="form-group full-width">
                <label for="description" class="form-label">
                    <i class="fas fa-align-left"></i>
                    Mô tả phòng
                </label>
                <textarea id="description" name="description" class="form-textarea"
                    placeholder="Mô tả chi tiết về phòng, tiện nghi, dịch vụ..." rows="5">{{ old('description', $room->description) }}</textarea>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <a href="{{ route('admin.rooms.management') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Hủy bỏ
            </a>
            <button type="reset" class="btn btn-outline">
                <i class="fas fa-redo"></i>
                Làm mới
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Cập nhật phòng
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let imagesToDelete = [];
        let newMainImageId = null;

        // Handle room type selection
        const rtSelect = document.getElementById('rt_id');
        const priceInput = document.getElementById('price_per_night');
        const maxGuestsSelect = document.getElementById('max_guests');
        const numberBedsSelect = document.getElementById('number_beds');

        rtSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];

            if (selectedOption.value) {
                // Auto-fill price if not already set or if current price matches old room type base price
                const basePrice = selectedOption.getAttribute('data-base-price');
                if (basePrice && (!priceInput.value || confirm('Bạn có muốn sử dụng giá cơ bản của loại phòng này?'))) {
                    priceInput.value = basePrice;
                }

                // Auto-fill max guests if not already set
                const maxGuests = selectedOption.getAttribute('data-max-guests');
                if (maxGuests && !maxGuestsSelect.value) {
                    maxGuestsSelect.value = maxGuests;
                }

                // Auto-fill number of beds if not already set
                const numberBeds = selectedOption.getAttribute('data-number-beds');
                if (numberBeds && !numberBedsSelect.value) {
                    numberBedsSelect.value = numberBeds;
                }
            }
        });

        // Handle delete image
        document.querySelectorAll('.btn-delete-image').forEach(button => {
            button.addEventListener('click', function() {
                const imageId = this.getAttribute('data-image-id');
                const imageItem = this.closest('.image-item');

                if (confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
                    imagesToDelete.push(imageId);
                    imageItem.style.display = 'none';

                    // Update hidden input
                    document.getElementById('deleteImages').value = imagesToDelete.join(',');
                }
            });
        });

        // Handle set main image
        document.querySelectorAll('.btn-set-main').forEach(button => {
            button.addEventListener('click', function() {
                const imageId = this.getAttribute('data-image-id');

                // Remove all main image badges
                document.querySelectorAll('.main-image-badge').forEach(badge => {
                    badge.remove();
                });

                // Remove all "Ảnh chính" text
                document.querySelectorAll('.image-controls span').forEach(span => {
                    if (span.textContent.includes('Ảnh chính')) {
                        span.remove();
                    }
                });

                // Add main image badge to selected image
                const imageItem = this.closest('.image-item');
                const badge = document.createElement('div');
                badge.className = 'main-image-badge';
                badge.style.cssText = 'position: absolute; top: 5px; left: 5px; background: #28a745; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: bold;';
                badge.textContent = 'Ảnh chính';
                imageItem.appendChild(badge);

                // Replace button with text
                this.outerHTML = '<span style="color: #28a745; font-size: 11px; font-weight: bold;">Ảnh chính</span>';

                // Update all other buttons
                document.querySelectorAll('.btn-set-main').forEach(otherBtn => {
                    otherBtn.style.display = 'inline-block';
                });

                // Update hidden input
                newMainImageId = imageId;
                document.getElementById('mainImage').value = imageId;
            });
        });

        // Handle new images preview
        document.getElementById('images').addEventListener('change', function() {
            const files = this.files;
            const preview = document.getElementById('newImagesPreview');
            preview.innerHTML = '';

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.style.cssText = 'position: relative; border: 2px solid #e0e0e0; border-radius: 8px; overflow: hidden;';
                    div.innerHTML = `
                    <img src="${e.target.result}" style="width: 100%; height: 120px; object-fit: cover;">
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); color: white; padding: 5px; font-size: 12px; text-align: center;">
                        ${file.name}
                    </div>
                `;
                    preview.appendChild(div);
                };

                reader.readAsDataURL(file);
            }
        });

        // Form reset
        document.querySelector('button[type="reset"]').addEventListener('click', function() {
            imagesToDelete = [];
            newMainImageId = null;
            document.getElementById('deleteImages').value = '';
            document.getElementById('mainImage').value = '';
            document.getElementById('newImagesPreview').innerHTML = '';

            // Restore all hidden images
            document.querySelectorAll('.image-item').forEach(item => {
                item.style.display = 'block';
            });
        });
    });
</script>

<style>
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
    }

    .required {
        color: #dc3545;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #007bff;
    }

    .form-help {
        display: block;
        margin-top: 5px;
        font-size: 12px;
        color: #6c757d;
        font-style: italic;
    }

    .room-type-badge {
        font-size: 0.8em;
        color: #6c757d;
        font-weight: normal;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-primary {
        background: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background: #0056b3;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-outline {
        background: transparent;
        color: #007bff;
        border: 2px solid #007bff;
    }

    .btn-outline:hover {
        background: #007bff;
        color: white;
    }

    .page-title {
        color: #333;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .content-section {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            justify-content: center;
        }
    }
</style>
@endsection