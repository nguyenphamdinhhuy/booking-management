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

<h1 class="page-title">
    <i class="fas fa-bed"></i>
    Quản lý Phòng
</h1>

<!-- Room Management Table -->
<div class="table-container">
    <!-- Table Header with Actions -->
    <div class="table-header">
        <h2 class="table-title">Danh sách phòng (<span id="roomCount"><?php echo e($rooms->count()); ?></span> phòng)</h2>
        <div class="table-actions">
            <a href="<?php echo e(route('admin.rooms.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Thêm phòng mới
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <form id="filterForm" method="GET" action="<?php echo e(route('admin.rooms.management')); ?>">
        <div class="filter-bar">
            <div class="filter-group">
                <label class="filter-label">Trạng thái</label>
                <select name="status" class="filter-select" onchange="applyFilters()">
                    <option value="">Tất cả</option>
                    <option value="1" <?php echo e(request('status') == '1' ? 'selected' : ''); ?>>Hoạt động</option>
                    <option value="0" <?php echo e(request('status') == '0' ? 'selected' : ''); ?>>Không hoạt động</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Số khách tối đa</label>
                <select name="max_guests" class="filter-select" onchange="applyFilters()">
                    <option value="">Tất cả</option>
                    <option value="1" <?php echo e(request('max_guests') == '1' ? 'selected' : ''); ?>>1 khách</option>
                    <option value="2" <?php echo e(request('max_guests') == '2' ? 'selected' : ''); ?>>2 khách</option>
                    <option value="3" <?php echo e(request('max_guests') == '3' ? 'selected' : ''); ?>>3 khách</option>
                    <option value="4" <?php echo e(request('max_guests') == '4' ? 'selected' : ''); ?>>4+ khách</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Giá phòng</label>
                <select name="price_range" class="filter-select" onchange="applyFilters()">
                    <option value="">Tất cả</option>
                    <option value="0-500000" <?php echo e(request('price_range') == '0-500000' ? 'selected' : ''); ?>>Dưới 500,000 VND</option>
                    <option value="500000-1000000" <?php echo e(request('price_range') == '500000-1000000' ? 'selected' : ''); ?>>500,000 - 1,000,000 VND</option>
                    <option value="1000000-2000000" <?php echo e(request('price_range') == '1000000-2000000' ? 'selected' : ''); ?>>1,000,000 - 2,000,000 VND</option>
                    <option value="2000000+" <?php echo e(request('price_range') == '2000000+' ? 'selected' : ''); ?>>Trên 2,000,000 VND</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Tìm kiếm</label>
                <input type="text" name="search" class="filter-input" placeholder="Tên phòng, mô tả..."
                    value="<?php echo e(request('search')); ?>" onkeyup="searchRooms(this.value)">
            </div>
            <div class="filter-group" style="align-items: end;">
                <button type="button" class="btn btn-secondary" onclick="clearFilters()">
                    <i class="fas fa-times"></i> Xóa bộ lọc
                </button>
            </div>
        </div>
    </form>

    <!-- Loading indicator -->
    <div id="loadingIndicator" style="display: none; text-align: center; padding: 20px;">
        <i class="fas fa-spinner fa-spin"></i> Đang tìm kiếm...
    </div>

    <!-- Data Table -->
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên phòng</th>
                    <th>Hình ảnh</th>
                    <th>Giá/đêm</th>
                    <th>Số khách</th>
                    <th>Số giường</th>
                    <th>Trạng thái</th>
                    <th>Mô tả</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="roomsTableBody">
                <?php $__empty_1 = true; $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($room->r_id); ?></td>
                    <td><strong><?php echo e($room->name); ?></strong></td>
                    <td>
                        <div class="room-images">
                            <?php if($room->images): ?>
                            <img src="<?php echo e(asset($room->images)); ?>" alt="<?php echo e($room->name); ?>"
                                class="room-image" onclick="showImageModal('<?php echo e(asset($room->images)); ?>')"
                                style="width: 60px; height: 45px; object-fit: cover; border-radius: 4px; margin-right: 5px;">
                            <?php else: ?>
                            <span style="color: #999; font-style: italic;">Không có ảnh</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><strong><?php echo e($room->formatted_price); ?></strong></td>
                    <td><?php echo e($room->max_guests ?? 'N/A'); ?></td>
                    <td><?php echo e($room->number_beds ?? 'N/A'); ?></td>
                    <td>
                        <?php if($room->status == 1): ?>
                        <span class="status-badge status-active">Hoạt động</span>
                        <?php else: ?>
                        <span class="status-badge status-inactive">Không hoạt động</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($room->description): ?>
                        <?php echo e(Str::limit($room->description, 50)); ?>

                        <?php else: ?>
                        <span style="color: #999; font-style: italic;">Chưa có mô tả</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($room->formatted_created_at); ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="<?php echo e(route('admin.rooms.view', $room->r_id)); ?>" class="btn btn-primary btn-sm" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?php echo e(route('admin.rooms.edit', $room->r_id)); ?>" class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?php echo e(route('admin.rooms.delete', $room->r_id)); ?>" class="btn btn-danger btn-sm" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa phòng này?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr id="noDataRow">
                    <td colspan="10" style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-bed" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                        <span id="noDataMessage">
                            <?php if(request()->hasAny(['status', 'max_guests', 'price_range', 'search'])): ?>
                            Không tìm thấy phòng nào phù hợp với tiêu chí tìm kiếm.
                            <?php else: ?>
                            Chưa có phòng nào được tạo.
                            <?php endif; ?>
                        </span>
                        <br><br>
                        <?php if(!request()->hasAny(['status', 'max_guests', 'price_range', 'search'])): ?>
                        <a href="<?php echo e(route('admin.rooms.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm phòng đầu tiên
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<div id="imageModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.8);" onclick="closeImageModal()">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); max-width: 90%; max-height: 90%;">
        <img id="modalImage" style="width: 100%; height: auto; border-radius: 8px;">
        <button onclick="closeImageModal()" style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.8); border: none; border-radius: 50%; width: 40px; height: 40px; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
<div id="routeContainer" data-url="<?php echo e(route('admin.rooms.management')); ?>"></div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\booking-management\resources\views/admin/rooms/rooms_management.blade.php ENDPATH**/ ?>