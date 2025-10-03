
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
    <i class="fas fa-trash-alt"></i>
    Thùng Rác - Quản lý Phòng
</h1>

<!-- Room Trash Table -->
<div class="table-container">
    <!-- Table Header with Actions -->
    <div class="table-header">
        <h2 class="table-title">Danh sách phòng đã xóa (<span id="roomCount"><?php echo e($rooms->count()); ?></span> phòng)</h2>
        <div class="table-actions">
            <a href="<?php echo e(route('admin.rooms.management')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Quay lại quản lý
            </a>
            <?php if($rooms->count() > 0): ?>
            <a href="<?php echo e(route('admin.rooms.restoreAll')); ?>" class="btn btn-success" 
               onclick="return confirm('Bạn có chắc chắn muốn khôi phục tất cả phòng?')">
                <i class="fas fa-undo"></i>
                Khôi phục tất cả
            </a>
            <a href="<?php echo e(route('admin.rooms.forceDeleteAll')); ?>" class="btn btn-danger" 
               onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn tất cả phòng? Hành động này không thể hoàn tác!')">
                <i class="fas fa-trash"></i>
                Xóa vĩnh viễn tất cả
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Filter Bar -->
    <form id="filterForm" method="GET" action="<?php echo e(route('admin.rooms.trash')); ?>">
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
                <label class="filter-label">Loại phòng</label>
                <select name="room_type" class="filter-select" onchange="applyFilters()">
                    <option value="">Tất cả loại phòng</option>
                    <?php $__currentLoopData = $roomTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roomType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($roomType->rt_id); ?>" <?php echo e(request('room_type') == $roomType->rt_id ? 'selected' : ''); ?>>
                        <?php echo e($roomType->type_name); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                <input type="text" name="search" class="filter-input" placeholder="Tên phòng, mô tả, loại phòng..."
                    value="<?php echo e(request('search')); ?>" onkeyup="searchRooms(this.value)">
            </div>
            <div class="filter-group" style="align-items: end;">
                <a href="<?php echo e(route('admin.rooms.trash')); ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Xóa bộ lọc
                </a>
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
                    <th>Loại phòng</th>
                    <th>Hình ảnh</th>
                    <th>Giá/đêm</th>
                    <th>Số khách</th>
                    <th>Số giường</th>
                    <th>Trạng thái</th>
                    <th>Ngày xóa</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="roomsTableBody">
                <?php $__empty_1 = true; $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($room->r_id); ?></td>
                    <td>
                        <strong><?php echo e($room->name); ?></strong>
                        <div style="color: #dc3545; font-size: 12px; margin-top: 2px;">
                            <i class="fas fa-trash-alt"></i> Đã xóa
                        </div>
                    </td>
                    <td>
                        <div class="room-type-info">
                            <strong><?php echo e($room->room_type_display); ?></strong>
                        </div>
                    </td>
                    <td>
                        <div class="room-images">
                            <?php if($room->images): ?>
                            <img src="<?php echo e(asset($room->images)); ?>" alt="<?php echo e($room->name); ?>"
                                class="room-image" onclick="showImageModal('<?php echo e(asset($room->images)); ?>')"
                                style="width: 60px; height: 45px; object-fit: cover; border-radius: 4px; margin-right: 5px; cursor: pointer; opacity: 0.7;">
                            <?php elseif(isset($room->image_list) && count($room->image_list) > 0): ?>
                            <img src="<?php echo e(asset($room->image_list[0])); ?>" alt="<?php echo e($room->name); ?>"
                                class="room-image" onclick="showImageModal('<?php echo e(asset($room->image_list[0])); ?>')"
                                style="width: 60px; height: 45px; object-fit: cover; border-radius: 4px; margin-right: 5px; cursor: pointer; opacity: 0.7;">
                            <?php if(isset($room->image_count) && $room->image_count > 1): ?>
                            <span class="image-count" style="font-size: 10px; color: #666;">+<?php echo e($room->image_count - 1); ?></span>
                            <?php endif; ?>
                            <?php else: ?>
                            <span style="color: #999; font-style: italic;">Không có ảnh</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div class="price-info">
                            <strong style="color: #27ae60;"><?php echo e($room->formatted_price); ?></strong>
                        </div>
                    </td>
                    <td>
                        <span class="guest-info">
                            <i class="fas fa-user" style="color: #666; margin-right: 3px;"></i>
                            <?php echo e($room->max_guests ?? 'N/A'); ?>

                        </span>
                    </td>
                    <td>
                        <span class="bed-info">
                            <i class="fas fa-bed" style="color: #666; margin-right: 3px;"></i>
                            <?php echo e($room->number_beds ?? 'N/A'); ?>

                        </span>
                    </td>
                    <td>
                        <?php if($room->status == 1): ?>
                        <span style="margin-bottom: 10px;" class="status-badge status-active">Hoạt động</span>
                        <?php else: ?>
                        <span class="status-badge status-inactive">Không hoạt động</span>
                        <?php endif; ?>
                        <?php if(isset($room->available)): ?>
                        <br>
                        <?php if($room->available == 1): ?>
                        <small style="background: green; color:#fff;" class="status-badge status-active">Có sẵn</small>
                        <?php else: ?>
                        <small style="background: red; color:#fff;" class="status-badge status-inactive">Đã đặt</small>
                        <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($room->formatted_deleted_at ?? 'N/A'); ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="<?php echo e(route('admin.rooms.restore', $room->r_id)); ?>" class="btn btn-success btn-sm" title="Khôi phục phòng"
                               onclick="return confirm('Bạn có chắc chắn muốn khôi phục phòng này?')">
                                <i class="fas fa-undo"></i>
                            </a>
                            <a href="<?php echo e(route('admin.rooms.forceDelete', $room->r_id)); ?>" class="btn btn-danger btn-sm" title="Xóa vĩnh viễn"
                               onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn phòng này? Hành động này không thể hoàn tác!')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr id="noDataRow">
                    <td colspan="10" style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-trash-alt" style="font-size: 48px; margin-bottom: 10px; display: block; opacity: 0.5;"></i>
                        <span id="noDataMessage">
                            <?php if(request()->hasAny(['status', 'room_type', 'max_guests', 'price_range', 'search'])): ?>
                            Không tìm thấy phòng nào trong thùng rác phù hợp với tiêu chí tìm kiếm.
                            <?php else: ?>
                            Thùng rác trống. Không có phòng nào bị xóa.
                            <?php endif; ?>
                        </span>
                        <br><br>
                        <a href="<?php echo e(route('admin.rooms.management')); ?>" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Quay lại quản lý phòng
                        </a>
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


<div id="confirmDeleteAllModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);" onclick="closeConfirmDeleteAllModal()">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 8px; max-width: 500px; width: 90%;" onclick="event.stopPropagation()">
        <h3 style="margin-bottom: 15px; color: #dc3545;">
            <i class="fas fa-exclamation-triangle"></i> Xác nhận xóa vĩnh viễn
        </h3>
        <p style="margin-bottom: 20px; color: #666;">
            Bạn có chắc chắn muốn xóa vĩnh viễn tất cả <?php echo e($rooms->count()); ?> phòng trong thùng rác?<br>
            <strong style="color: #dc3545;">Hành động này không thể hoàn tác!</strong>
        </p>
        <div style="text-align: right;">
            <button onclick="closeConfirmDeleteAllModal()" class="btn btn-secondary" style="margin-right: 10px;">
                Hủy
            </button>
            <a href="<?php echo e(route('admin.rooms.forceDeleteAll')); ?>" class="btn btn-danger">
                <i class="fas fa-trash"></i> Xóa vĩnh viễn tất cả
            </a>
        </div>
        <button onclick="closeConfirmDeleteAllModal()" style="position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 20px; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<div id="routeContainer" data-url="<?php echo e(route('admin.rooms.trash')); ?>"></div>

<script>
    // Function to show image modal
    function showImageModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModal').style.display = 'block';
    }

    // Function to close image modal
    function closeImageModal() {
        document.getElementById('imageModal').style.display = 'none';
    }

    // Function to show confirm delete all modal
    function showConfirmDeleteAllModal() {
        document.getElementById('confirmDeleteAllModal').style.display = 'block';
    }

    // Function to close confirm delete all modal
    function closeConfirmDeleteAllModal() {
        document.getElementById('confirmDeleteAllModal').style.display = 'none';
    }

    // Other functions
    function applyFilters() {
        document.getElementById('filterForm').submit();
    }

    function searchRooms(query) {
        if (query.length >= 2 || query.length === 0) {
            setTimeout(function() {
                document.getElementById('filterForm').submit();
            }, 500);
        }
    }

    // Auto hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 300);
            }, 5000);
        });
    });
</script>

<style>
    .room-type-info {
        min-width: 150px;
    }

    .discount-badge {
        font-weight: bold;
        text-transform: uppercase;
    }

    .price-info {
        min-width: 100px;
    }

    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .status-active {
        background-color: #d4edda;
        color: #155724;
    }

    .status-inactive {
        background-color: #f8d7da;
        color: #721c24;
    }

    .guest-info,
    .bed-info {
        white-space: nowrap;
    }

    .action-buttons {
        white-space: nowrap;
    }

    .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
        margin-right: 2px;
    }

    .table-responsive {
        overflow-x: hidden;
    }

    .data-table {
        min-width: 1200px;
    }

    /* Trash-specific styles */
    .data-table tbody tr {
        background-color: #f8f9fa;
        opacity: 0.8;
    }

    .data-table tbody tr:hover {
        background-color: #e9ecef;
        opacity: 1;
    }

    /* Alert animation */
    .alert {
        transition: opacity 0.3s ease;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .table-actions {
            flex-direction: column;
            gap: 10px;
        }
        
        .filter-bar {
            flex-direction: column;
        }
        
        .filter-group {
            margin-bottom: 10px;
        }
    }
</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/admin/rooms/trash.blade.php ENDPATH**/ ?>