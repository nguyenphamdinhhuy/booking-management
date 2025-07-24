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
<h1 class="page-title d-flex align-items-center justify-between">
    <span>
        <i class="fas fa-concierge-bell"></i>
        Quản lý dịch vụ
    </span>


</h1>

<!-- Room Management Table -->
<div class="table-container">
    <!-- Table Header with Actions -->
    <div class="table-header">
        <h2 class="table-title">Danh sách dịch vụ</h2>
        <div class="table-actions">

            <a class="btn btn-primary" href="<?php echo e(route('service.create')); ?>"
                class="btn btn-primary">
                <i class="fas fa-plus"></i>Thêm dịch vụ</a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="filter-group">
            <label class="filter-label">Trạng thái</label>
            <select class="filter-select" onchange="filterRooms()">
                <option value="">Tất cả</option>
                <option value="1">Hoạt động</option>
                <option value="0">Không hoạt động</option>
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label">Danh mục</label>
            <form method="GET" action="<?php echo e(route('services.index')); ?>">
                <select name="category_id" class="filter-select" onchange="this.form.submit()">
                    <option value="">Tất cả</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category_id') == $cat->id ? 'selected' : ''); ?>>
                        <?php echo e($cat->name); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </form>
        </div>

        <div class="filter-group">
            <label class="filter-label">Tìm kiếm</label>
            <input type="text" class="filter-input" placeholder="Tên dịch vụ..." onkeyup="searchRooms(this.value)">
        </div>
    </div>

    <!-- Data Table -->
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên dịch vụ</th>
                    <th>Giá</th>
                    <th>Đơn vị</th>
                    <th>Danh mục</th>
                    <th>Trạng thái</th>
                    <th>Ảnh</th>
                    <th>Mô tả</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody id="roomsTableBody">
                <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($sv->s_id); ?></td>
                    <td><?php echo e($sv->name); ?></td>
                    <td><?php echo e(number_format($sv->price)); ?> VND</td>
                    <td><?php echo e($sv->unit); ?></td>
                    <td><?php echo e($sv->category->name ?? 'Không có'); ?></td>
                    <td>
                        <span class="status-badge <?php echo e($sv->is_available ? 'status-active' : 'status-inactive'); ?>">
                            <?php echo e($sv->is_available ? 'Hoạt động' : 'Ngưng'); ?>

                        </span>
                    </td>
                    <td>
                        <?php if($sv->image): ?>
                        <img src="<?php echo e(asset($sv->image)); ?>" width="80" height="60">
                        <?php else: ?>
                        Không có
                        <?php endif; ?>
                    </td>
                    <td><?php echo e(Str::limit($sv->description, 50)); ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="<?php echo e(route('service.edit', $sv->s_id)); ?>" class="btn btn-warning btn-sm" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo e(route('service.destroy', $sv->s_id)); ?>" method="POST"
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
</div>
<div id="routeContainer" data-url="<?php echo e(route('admin.rooms.management')); ?>"></div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\booking-management\resources\views/admin/service/service.blade.php ENDPATH**/ ?>