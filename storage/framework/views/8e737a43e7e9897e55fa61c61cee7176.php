<?php $__env->startSection("content"); ?>
    
    <?php if(session('success')): ?>
        <div class="alert alert-success"
            style="background: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 20px;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger"
            style="background: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 20px;">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>
    <h1 class="page-title d-flex align-items-center justify-between">
        <span>
            <i class="fas fa-concierge-bell"></i>
            Quản lý loại dịch vụ
        </span>
    </h1>


    <!-- Room Management Table -->
    <div class="table-container">
        <!-- Table Header with Actions -->
        <div class="table-header">
            <h2 class="table-title">Danh sách dịch vụ</h2>
            <div class="table-actions">

                <a class="btn btn-primary" onclick="openAddRoomModal()" href="<?php echo e(route('service-categories.create')); ?>"
                    class="btn btn-primary">
                    <i class="fas fa-plus"></i>Thêm danh mục</a>
            </div>
        </div>

        <!-- Filter Bar -->
        <form method="GET" action="<?php echo e(route('service-categories.index')); ?>">
            <div class="filter-bar">
                <div class="filter-group">
                    <label class="filter-lable">Trạng thái</label>
                    <select name="is_available" class="filter-select" onchange="this.form.submit()" id="">
                        <option value="" <?php echo e(request('is_available') === null || request('is_available') === '' ? 'selected' : ''); ?>>Tất cả</option>
                        <option value="1" <?php echo e(request('is_available') === '1' ? 'selected' : ''); ?>>Hoạt động</option>
                        <option value="0" <?php echo e(request('is_available') === '0' ? 'selected' : ''); ?>>Ngưng</option>

                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Loại Dịnh vụ</label>
                    <select name="id" class="filter-select" onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        <?php $__currentLoopData = $service_Category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>" <?php echo e(request('id') == $cat->id ? 'selected' : ''); ?>>
                                <?php echo e($cat->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-lable" for="">Tìm kiếm</label>
                    <input type="text" name="keyword" value="<?php echo e(request('keyword')); ?>" class="filter-input"
                        placeholder="Ten loai dich vụ...">
                </div>
                <div class="filter-group" style="align-items: end;">
                    <button type="button" class="btn btn-secondary" onclick="clear_Filters()">
                        <i class="fas fa-times"></i> Xóa bộ lọc
                    </button>
                </div>
            </div>
        </form>





        <!-- Data Table -->
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Loại dịch vụ</th>
                        <th>Hình ảnh</th>
                        <th>Mô tả</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody id="roomsTableBody">
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($cat->id); ?></td>
                            <td><?php echo e($cat->name); ?></td>
                            <td>
                                <?php if($cat->image): ?>
                                    <img src="<?php echo e(asset($cat->image)); ?>" with="80" height="60" alt="">
                                <?php else: ?>
                                    Khong anh
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($cat->description); ?></td>
                            <td>
                                <span class="status-badge <?php echo e($cat->is_available ? 'status-active' : 'status-inactive'); ?>">
                                    <?php echo e($cat->is_available ? '✅' : '❌'); ?>

                                </span>
                            </td>
                            <td><?php echo e($cat->created_at ? $cat->created_at->format('d/m/Y') : ''); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?php echo e(route('service-categories.edit', $cat->id)); ?>" class="btn btn-warning btn-sm"
                                        title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('service-categories.destroy', $cat->id)); ?>" method="POST"
                                        style="display:inline;">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button onclick="return confirm('Bạn có chắc chắn muốn xoá?')" type="submit"
                                            class="btn btn-danger btn-sm" title="Xoá">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            <?php echo e($categories->links('pagination::bootstrap-4')); ?>

        </div>
        <script>
            function clear_Filters() {
                // Điều hướng về trang index mặc định, không có query string
                window.location.href = "<?php echo e(route('service-categories.index')); ?>";
            }
        </script>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/admin/service/service_categories.blade.php ENDPATH**/ ?>