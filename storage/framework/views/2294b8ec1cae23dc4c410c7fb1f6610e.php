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
                <label class="filter-label">Loại dịch vụ</label>
                <select class="filter-select" onchange="filterRooms()">
                    <option value="">Tất cả</option>
                    <option value="0-500000">Đưa đón</option>
                    <option value="500000-1000000">Đặt đồ ăn</option>
                    <option value="1000000-2000000">Tậm gym</option>
                    <option value="2000000+">Ca hát</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Tìm kiếm</label>
                <input type="text" class="filter-input" placeholder="Tên phòng..." onkeyup="searchRooms(this.value)">
            </div>
        </div>

        <!-- Data Table -->
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Loại dịch vụ</th>
                        <th>Mô tả</th>
                        <th>Hình ảnh</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody id="roomsTableBody">
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($cat->id); ?></td>
                            <td><?php echo e($cat->name); ?></td>
                            <td><?php echo e($cat->description); ?></td>
                            <td>
                                <?php if($cat->image): ?>
                                    <img src="<?php echo e(asset($cat->image)); ?>" with="80" height="80" alt="">
                                <?php else: ?>
                                    Khong anh
                                <?php endif; ?>
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
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app\resources\views/admin/service/service_categories.blade.php ENDPATH**/ ?>