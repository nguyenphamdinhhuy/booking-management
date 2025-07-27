<?php $__env->startSection("content"); ?>

    <h1 class="page-title">
        <i class="fas fa-edit"></i>
        Chỉnh sửa dịch vụ
    </h1>

    <div class="content-section">
        <form action="<?php echo e(route('service.update', $service->s_id)); ?>" method="POST" enctype="multipart/form-data"
            class="room-form">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="form-grid">
                <!-- Tên dịch vụ -->
                <div class="form-group">
                    <label for="name" class="form-label">
                        <i class="fas fa-tag"></i> Tên dịch vụ
                    </label>
                    <input type="text" name="name" id="name" class="form-input" value="<?php echo e(old('name', $service->name)); ?>"
                        required>
                </div>

                <!-- Giá -->
                <div class="form-group">
                    <label for="price" class="form-label">
                        <i class="fas fa-dollar-sign"></i> Giá (VNĐ)
                    </label>
                    <input type="number" name="price" id="price" class="form-input"
                        value="<?php echo e(old('price', $service->price)); ?>" min="0" step="1000" required>
                </div>

                <!-- Đơn vị -->
                <div class="form-group">
                    <label for="unit" class="form-label">
                        <i class="fas fa-balance-scale"></i> Đơn vị tính
                    </label>
                    <input type="text" name="unit" id="unit" class="form-input" value="<?php echo e(old('unit', $service->unit)); ?>"
                        required>
                </div>

                <!-- Danh mục -->
                <div class="form-group">
                    <label for="category_id" class="form-label">
                        <i class="fas fa-list"></i> Danh mục
                    </label>
                    <select id="category_id" name="category_id" class="form-select" required>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>" <?php echo e($service->category_id == $cat->id ? 'selected' : ''); ?>>
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
                        <option value="1" <?php echo e($service->is_available ? 'selected' : ''); ?>>Hoạt động</option>
                        <option value="0" <?php echo e(!$service->is_available ? 'selected' : ''); ?>>Không hoạt động</option>
                    </select>
                </div>

                <!-- Hình ảnh -->
                <div class="form-group full-width">
                    <label for="image" class="form-label">
                        <i class="fas fa-image"></i> Hình ảnh hiện tại
                    </label>
                    <?php if($service->image): ?>
                        <div><img src="<?php echo e(asset($service->image)); ?>" width="120" height="80"></div>
                    <?php else: ?>
                        <div>Không có ảnh</div>
                    <?php endif; ?>
                    <input type="file" name="image" id="image" class="form-input mt-2">
                </div>

                <!-- Mô tả -->
                <div class="form-group full-width">
                    <label for="description" class="form-label">
                        <i class="fas fa-align-left"></i> Mô tả
                    </label>
                    <textarea name="description" id="description" class="form-textarea"
                        rows="5"><?php echo e(old('description', $service->description)); ?></textarea>
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
                    <i class="fas fa-save"></i> Cập nhật
                </button>
            </div>
        </form>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app\resources\views/admin/service/edit_service.blade.php ENDPATH**/ ?>