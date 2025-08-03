
<?php $__env->startSection("content"); ?>


<?php if(session('success')): ?>
<div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 20px;">
    <?php echo e(session('success')); ?>

</div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 20px;">
    <?php echo e(session('error')); ?>

</div>
<?php endif; ?>


<?php if($errors->any()): ?>
<div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 20px;">
    <ul style="margin: 0; padding-left: 20px;">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li><?php echo e($error); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>
<?php endif; ?>

<h1 class="page-title">
    <i class="fas fa-bed"></i>
    Thêm phòng mới
</h1>

<!-- Add Room Form -->
<div class="content-section">
    <form action="<?php echo e(route('admin.rooms.store')); ?>" enctype="multipart/form-data" method="POST" class="room-form" id="addRoomForm">
        <?php echo csrf_field(); ?>
        <div class="form-grid">
            <!-- Room Name -->
            <div class="form-group">
                <label for="name" class="form-label">
                    <i class="fas fa-tag"></i>
                    Tên phòng <span class="required">*</span>
                </label>
                <input type="text" id="name" name="name" class="form-input" value="<?php echo e(old('name')); ?>"
                    placeholder="VD: Phòng Deluxe 101" maxlength="50">
            </div>

            <!-- Price per Night -->
            <div class="form-group">
                <label for="price_per_night" class="form-label">
                    <i class="fas fa-dollar-sign"></i>
                    Giá mỗi đêm (VNĐ) <span class="required">*</span>
                </label>
                <input type="number" id="price_per_night" name="price_per_night" class="form-input" value="<?php echo e(old('price_per_night')); ?>"
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
                    <option value="1" <?php echo e(old('max_guests') == '1' ? 'selected' : ''); ?>>1 khách</option>
                    <option value="2" <?php echo e(old('max_guests') == '2' ? 'selected' : ''); ?>>2 khách</option>
                    <option value="3" <?php echo e(old('max_guests') == '3' ? 'selected' : ''); ?>>3 khách</option>
                    <option value="4" <?php echo e(old('max_guests') == '4' ? 'selected' : ''); ?>>4 khách</option>
                    <option value="5" <?php echo e(old('max_guests') == '5' ? 'selected' : ''); ?>>5 khách</option>
                    <option value="6" <?php echo e(old('max_guests') == '6' ? 'selected' : ''); ?>>6 khách</option>
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
                    <option value="1" <?php echo e(old('number_beds') == '1' ? 'selected' : ''); ?>>1 giường</option>
                    <option value="2" <?php echo e(old('number_beds') == '2' ? 'selected' : ''); ?>>2 giường</option>
                    <option value="3" <?php echo e(old('number_beds') == '3' ? 'selected' : ''); ?>>3 giường</option>
                    <option value="4" <?php echo e(old('number_beds') == '4' ? 'selected' : ''); ?>>4 giường</option>
                </select>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status" class="form-label">
                    <i class="fas fa-toggle-on"></i>
                    Trạng thái
                </label>
                <select id="status" name="status" class="form-select">
                    <option value="1" <?php echo e(old('status', '1') == '1' ? 'selected' : ''); ?>>Hoạt động</option>
                    <option value="0" <?php echo e(old('status') == '0' ? 'selected' : ''); ?>>Không hoạt động</option>
                </select>
            </div>

            <!-- Image - SỬA THÀNH 1 ẢNH DUY NHẤT -->
            <div class="form-group full-width">
                <label for="image" class="form-label">
                    <i class="fas fa-image"></i>
                    Hình ảnh phòng
                </label>
                <div class="file-upload-area">
                    <!-- KHÔNG CÓN MULTIPLE VÀ ĐỔI NAME THÀNH image -->
                    <input type="file" id="image" name="image" class="file-input" accept="image/*">
                    <div class="file-upload-content">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Kéo thả hình ảnh vào đây hoặc <span class="upload-link">chọn file</span></p>
                        <p class="file-info">Hỗ trợ: JPG, PNG, GIF (Tối đa 5MB)</p>
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
                    placeholder="Mô tả chi tiết về phòng, tiện nghi, dịch vụ..." rows="5"><?php echo e(old('description')); ?></textarea>
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app\resources\views/admin/rooms/add_room.blade.php ENDPATH**/ ?>