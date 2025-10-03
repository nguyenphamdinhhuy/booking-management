<?php $__env->startSection("content"); ?>

    <h1 class="page-title">
        <i class="fas fa-concierge-bell"></i>
        Thêm dịch vụ mới
    </h1>

    <div class="content-section">
        <form action="<?php echo e(url('/admin/add_service')); ?>" method="POST" enctype="multipart/form-data" class="room-form">
            <?php echo csrf_field(); ?>

            <div class="form-grid">
                <!-- Tên dịch vụ -->
                <div class="form-group">
                    <label for="name" class="form-label">
                        <i class="fas fa-tag"></i> Tên dịch vụ <span class="required">*</span>
                    </label>
                    <input type="text" id="name" name="name" class="form-input" placeholder="VD: Đưa đón sân bay"
                        maxlength="100" value="<?php echo e(old('name')); ?>">
                </div>

                <!-- Giá -->
                <div class="form-group">
                    <label for="price" class="form-label">
                        <i class="fas fa-dollar-sign"></i> Giá (VNĐ) <span class="required">*</span>
                    </label>
                    <input type="number" id="price" name="price" class="form-input" placeholder="VD: 500000" min="0"
                        step="1000" value="<?php echo e(old('price')); ?>">
                </div>

                <!-- Đơn vị tính -->
                <div class="form-group">
                    <label for="unit" class="form-label">
                        <i class="fas fa-balance-scale"></i> Đơn vị tính
                    </label>
                    <input type="text" id="unit" name="unit" class="form-input" placeholder="VD: lượt, giờ, ngày"
                        maxlength="50" value="<?php echo e(old('unit')); ?>">
                </div>

                <!-- Danh mục -->
                <div class="form-group">
                    <label for="category_id" class="form-label">
                        <i class="fas fa-list"></i> Danh mục dịch vụ <span class="required">*</span>
                    </label>
                    <select id="category_id" name="category_id" class="form-select">
                        <option value="">-- Chọn danh mục --</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>" <?php echo e(old('category_id') == $cat->id ? 'selected' : ''); ?>>
                                <?php echo e($cat->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Trạng thái -->
                <div class="form-group">
                    <label for="is_available" class="form-label">
                        <i class="fas fa-toggle-on"></i> Trạng thái
                    </label>
                    <select id="is_available" name="is_available" class="form-select">
                        <option value="1" <?php echo e(old('is_available', 1) == 1 ? 'selected' : ''); ?>>Hoạt động</option>
                        <option value="0" <?php echo e(old('is_available') == 0 ? 'selected' : ''); ?>>Không hoạt động</option>
                    </select>
                </div>

                <!-- Hình ảnh -->
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

                <!-- Mô tả -->
                <div class="form-group full-width">
                    <label for="description" class="form-label">
                        <i class="fas fa-align-left"></i> Mô tả
                    </label>
                    <textarea id="description" name="description" class="form-textarea" rows="5"
                        placeholder="Mô tả chi tiết về dịch vụ..."><?php echo e(old('description')); ?></textarea>
                </div>
                <!-- Số lượng tối đa -->
                <div class="form-group">
                    <label for="max_quantity" class="form-label">
                        <i class="fas fa-sort-numeric-up"></i> Số lượng tối đa
                    </label>
                    <input type="number" id="max_quantity" name="max_quantity" class="form-input" min="1"
                        placeholder="VD: 50" value="<?php echo e(old('max_quantity')); ?>">
                </div>

                <!-- Thời gian phục vụ -->
                <div class="form-group">
                    <label for="service_time" class="form-label">
                        <i class="fas fa-clock"></i> Thời gian phục vụ
                    </label>
                    <input type="text" id="service_time" name="service_time" class="form-input"
                        placeholder="VD: 07:00 - 10:00" maxlength="100" value="<?php echo e(old('service_time')); ?>">
                </div>

                <!-- Địa điểm -->
                <div class="form-group">
                    <label for="location" class="form-label">
                        <i class="fas fa-map-marker-alt"></i> Địa điểm
                    </label>
                    <input type="text" id="location" name="location" class="form-input" placeholder="VD: Tầng 1 - Nhà hàng"
                        maxlength="255" value="<?php echo e(old('location')); ?>">
                </div>

                <!-- Ghi chú -->
                <div class="form-group full-width">
                    <label for="note" class="form-label">
                        <i class="fas fa-sticky-note"></i> Ghi chú thêm
                    </label>
                    <textarea id="note" name="note" class="form-textarea" rows="3"
                        placeholder="Thông tin thêm (nếu có)..."><?php echo e(old('note')); ?></textarea>
                </div>

            </div>

            <!-- Hành động -->
            <div class="form-actions">
                <a href="<?php echo e(route('services.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <button type="reset" class="btn btn-outline">
                    <i class="fas fa-redo"></i> Làm mới
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu dịch vụ
                </button>
            </div>
        </form>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/admin/service/add_service.blade.php ENDPATH**/ ?>