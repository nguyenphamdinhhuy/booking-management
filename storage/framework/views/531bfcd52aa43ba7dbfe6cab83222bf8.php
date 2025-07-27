
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
    <i class="fas fa-plus-circle"></i>
    Thêm Voucher Mới
</h1>

<!-- Create Voucher Form -->
<div class="content-section">
    <form action="<?php echo e(route('vouchers.store')); ?>" method="POST" class="room-form" id="createVoucherForm">
        <?php echo csrf_field(); ?>
        <div class="form-grid">
            <!-- Voucher Code -->
            <div class="form-group">
                <label for="v_code" class="form-label">
                    <i class="fas fa-tag"></i>
                    Mã Voucher <span class="required">*</span>
                </label>
                <input type="text" id="v_code" name="v_code" class="form-input"
                    value="<?php echo e(old('v_code')); ?>" maxlength="10" required
                    placeholder="Nhập mã voucher (tối đa 10 ký tự)">
                <small class="form-text">Mã voucher sẽ được tự động chuyển thành chữ hoa</small>
            </div>

            <!-- Discount Percent -->
            <div class="form-group">
                <label for="discount_percent" class="form-label">
                    <i class="fas fa-percent"></i>
                    Phần Trăm Giảm Giá <span class="required">*</span>
                </label>
                <input type="number" id="discount_percent" name="discount_percent" class="form-input"
                    value="<?php echo e(old('discount_percent')); ?>" min="0" max="100" step="0.01" required
                    placeholder="Nhập phần trăm giảm giá (0-100)">
            </div>

            <!-- Start Date -->
            <div class="form-group">
                <label for="start_date" class="form-label">
                    <i class="fas fa-calendar-alt"></i>
                    Ngày Bắt Đầu <span class="required">*</span>
                </label>
                <input type="datetime-local" id="start_date" name="start_date" class="form-input"
                    value="<?php echo e(old('start_date')); ?>" required>
            </div>

            <!-- End Date -->
            <div class="form-group">
                <label for="end_date" class="form-label">
                    <i class="fas fa-calendar-times"></i>
                    Ngày Kết Thúc <span class="required">*</span>
                </label>
                <input type="datetime-local" id="end_date" name="end_date" class="form-input"
                    value="<?php echo e(old('end_date')); ?>" required>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status" class="form-label">
                    <i class="fas fa-toggle-on"></i>
                    Trạng Thái <span class="required">*</span>
                </label>
                <select id="status" name="status" class="form-select" required>
                    <option value="">Chọn trạng thái</option>
                    <option value="1" <?php echo e(old('status') == '1' ? 'selected' : ''); ?>>Hoạt động</option>
                    <option value="0" <?php echo e(old('status') == '0' ? 'selected' : ''); ?>>Không hoạt động</option>
                </select>
            </div>

            <!-- Description -->
            <div class="form-group full-width">
                <label for="description" class="form-label">
                    <i class="fas fa-align-left"></i>
                    Mô Tả
                </label>
                <textarea id="description" name="description" class="form-textarea" rows="4"
                    placeholder="Nhập mô tả cho voucher (tùy chọn)"><?php echo e(old('description')); ?></textarea>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <a href="<?php echo e(route('vouchers.management')); ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Hủy bỏ
            </a>
            <button type="reset" class="btn btn-outline">
                <i class="fas fa-redo"></i>
                Làm mới
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Thêm Voucher
            </button>
        </div>
    </form>
</div>

<!-- JavaScript for form validation and enhancement -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('createVoucherForm');
        const vCodeInput = document.getElementById('v_code');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const discountInput = document.getElementById('discount_percent');

        // Auto uppercase voucher code
        vCodeInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        // Set minimum date to today
        const today = new Date();
        const todayString = today.toISOString().slice(0, 16);
        startDateInput.setAttribute('min', todayString);

        // Update end date minimum when start date changes
        startDateInput.addEventListener('change', function() {
            const startDate = new Date(this.value);
            const minEndDate = new Date(startDate);
            minEndDate.setHours(startDate.getHours() + 1); // Minimum 1 hour difference
            endDateInput.setAttribute('min', minEndDate.toISOString().slice(0, 16));

            // Clear end date if it's before start date
            if (endDateInput.value && new Date(endDateInput.value) <= startDate) {
                endDateInput.value = '';
            }
        });

        // Validate discount percentage
        discountInput.addEventListener('input', function() {
            const value = parseFloat(this.value);
            if (value < 0) {
                this.value = 0;
            } else if (value > 100) {
                this.value = 100;
            }
        });

        // Form submission validation
        form.addEventListener('submit', function(e) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            if (endDate <= startDate) {
                e.preventDefault();
                alert('Ngày kết thúc phải sau ngày bắt đầu!');
                return false;
            }

            const discount = parseFloat(discountInput.value);
            if (discount <= 0 || discount > 100) {
                e.preventDefault();
                alert('Phần trăm giảm giá phải từ 0.01 đến 100!');
                return false;
            }

            return true;
        });
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app\resources\views/admin/vouchers/add_vouchers.blade.php ENDPATH**/ ?>