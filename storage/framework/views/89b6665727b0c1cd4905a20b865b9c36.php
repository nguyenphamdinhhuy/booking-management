
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
            <i class="fas fa-trash"></i>
            Quản lý dịch vụ đã xóa
        </span>


    </h1>


    <!-- Room Management Table -->
    <div class="table-container">
        <!-- Table Header with Actions -->
        <div class="table-header">
            <h2 class="table-title">Danh sách dịch vụ đã xóa</h2>
            <div class="table-actions">
                <a onclick="return confirm('Bạn có chắc chắn muốn xoá?')" href="<?php echo e(route('service.delete_all')); ?>"
                    class="btn btn-danger">
                    <i class="fas fa-trash"></i> Xóa tất cả
                </a>
                <a onclick="return confirm('Bạn muốn khôi phục tấy cả?')" href=" <?php echo e(route('service.restore_all')); ?>"
                    class="btn btn-primary">
                    <i class="fas fa-undo"></i> Khôi phục tất cả
                </a>
            </div>
        </div>

        <!-- Filter Bar -->
        <form method="GET" action="<?php echo e(route('service.trashCan')); ?>">
            <div class="filter-bar">



                <div class="filter-group">
                    <label class="filter-label">Ngày xóa (từ)</label>
                    <input type="date" name="deleted_from" id="deleted_from" value="<?php echo e(request('deleted_from')); ?>"
                        class="filter-input" onchange="document.getElementById('deleted_to').min = this.value">
                </div>

                <div class="filter-group">
                    <label class="filter-label">Ngày xóa (đến)</label>
                    <input type="date" name="deleted_to" id="deleted_to" value="<?php echo e(request('deleted_to')); ?>"
                        class="filter-input" onchange="this.form.submit()"
                        onchange="document.getElementById('deleted_from').max = this.value">
                </div>


                <div class="filter-group">
                    <label class="filter-label">Danh mục</label>
                    <select name="category_id" class="filter-select" onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category_id') == $cat->id ? 'selected' : ''); ?>>
                                <?php echo e($cat->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Giá phòng</label>
                    <select name="price_range" class="filter-select" onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        <option value="0-500000" <?php echo e(request('price_range') == '0-500000' ? 'selected' : ''); ?>>Dưới 500,000 VND
                        </option>
                        <option value="500000-1000000" <?php echo e(request('price_range') == '500000-1000000' ? 'selected' : ''); ?>>
                            500,000 - 1,000,000 VND</option>
                        <option value="1000000-2000000" <?php echo e(request('price_range') == '1000000-2000000' ? 'selected' : ''); ?>>
                            1,000,000 - 2,000,000 VND</option>
                        <option value="2000000+" <?php echo e(request('price_range') == '2000000+' ? 'selected' : ''); ?>>Trên 2,000,000
                            VND</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Tìm kiếm</label>
                    <input type="text" name="keyword" value="<?php echo e(request('keyword')); ?>" class="filter-input"
                        placeholder="Tên dịch vụ...">
                </div>

                <div class="filter-group">
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
                        <th>Tên dịch vụ</th>
                        <th>Ảnh</th>
                        <th>Giá</th>
                        <th>Đơn vị</th>
                        <th>Danh mục</th>
                        <th>Ngày xóa</th>

                        <th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody id="roomsTableBody">
                    <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($sv->s_id); ?></td>
                            <td><?php echo e($sv->name); ?></td>
                            <td>
                                <?php if($sv->image): ?>
                                    <img src="<?php echo e(asset($sv->image)); ?>" width="80" height="60">
                                <?php else: ?>
                                    Không có
                                <?php endif; ?>
                            </td>
                            <td><?php echo e(number_format($sv->price)); ?> VND</td>
                            <td><?php echo e($sv->unit); ?></td>
                            <td><?php echo e($sv->category->name ?? 'Không có'); ?></td>
                            <td>
                                <?php echo e($sv->deleted_at); ?>

                            </td>

                            <td><?php echo e(Str::limit($sv->description, 50)); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a onclick="return confirm('Bạn có chắc muốn khôi phục san phẩm')"
                                        href="<?php echo e(route('service.restore', $sv->s_id)); ?>" class="btn btn-warning btn-sm"
                                        title="Khôi phục">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                    <form action="<?php echo e(route('service.deleted', $sv->s_id)); ?>" method="POST"
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
            <?php echo e($services->links('pagination::bootstrap-4')); ?>

        </div>

        <script>
            function clear_Filters() {
                // Điều hướng về trang index mặc định, không có query string
                window.location.href = "<?php echo e(route('service.trashCan')); ?>";
            }
        </script>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/admin/service/trashCan_service.blade.php ENDPATH**/ ?>