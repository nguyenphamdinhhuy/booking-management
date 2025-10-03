
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
    Thùng rác - Loại Phòng
</h1>

<!-- Room Types Trash Table -->
<div class="table-container">
    <!-- Table Header with Actions -->
    <div class="table-header">
        <h2 class="table-title">Danh sách loại phòng đã xóa (<span id="roomTypeCount"><?php echo e($roomTypes->count()); ?></span> loại phòng)</h2>
        <div class="table-actions">
            <a href="<?php echo e(route('admin.roomType.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Quay lại danh sách
            </a>
            <?php if($roomTypes->count() > 0): ?>
            <a href="<?php echo e(route('admin.roomType.restoreAll')); ?>"
                class="btn btn-success"
                onclick="return confirm('Bạn có chắc chắn muốn khôi phục tất cả loại phòng?')">
                <i class="fas fa-undo"></i>
                Khôi phục tất cả
            </a>
            <?php if(Auth::check() && Auth::user()->role === 'admin'): ?>
            <a href="<?php echo e(route('admin.roomType.forceDeleteAll')); ?>"
                class="btn btn-danger"
                onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn tất cả loại phòng?\nThao tác này không thể hoàn tác!')">
                <i class="fas fa-trash"></i>
                Xóa vĩnh viễn tất cả
            </a>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Filter Bar -->
    <form id="filterForm" method="GET" action="<?php echo e(route('admin.roomType.trash')); ?>">
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
                <label class="filter-label">Khoảng giá</label>
                <select name="price_range" class="filter-select" onchange="applyFilters()">
                    <option value="">Tất cả</option>
                    <option value="0-500000" <?php echo e(request('price_range') == '0-500000' ? 'selected' : ''); ?>>Dưới 500,000 VND</option>
                    <option value="500000-1000000" <?php echo e(request('price_range') == '500000-1000000' ? 'selected' : ''); ?>>500,000 - 1,000,000 VND</option>
                    <option value="1000000-2000000" <?php echo e(request('price_range') == '1000000-2000000' ? 'selected' : ''); ?>>1,000,000 - 2,000,000 VND</option>
                    <option value="2000000+" <?php echo e(request('price_range') == '2000000+' ? 'selected' : ''); ?>>Trên 2,000,000 VND</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Số giường</label>
                <select name="number_beds" class="filter-select" onchange="applyFilters()">
                    <option value="">Tất cả</option>
                    <option value="1" <?php echo e(request('number_beds') == '1' ? 'selected' : ''); ?>>1 giường</option>
                    <option value="2" <?php echo e(request('number_beds') == '2' ? 'selected' : ''); ?>>2 giường</option>
                    <option value="3+" <?php echo e(request('number_beds') == '3+' ? 'selected' : ''); ?>>3+ giường</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Tìm kiếm</label>
                <input type="text" name="search" class="filter-input" placeholder="Tên loại phòng, mô tả..."
                    value="<?php echo e(request('search')); ?>" onkeyup="searchRoomTypes(this.value)">
            </div>
            <div class="filter-group" style="align-items: end;">

                <a class="btn btn-secondary" href="<?php echo e(route('admin.roomType.trash')); ?>">Xóa bộ lọc</a>
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
                    <th>Tên loại phòng</th>
                    <th>Hình ảnh</th>
                    <th>Giá cơ bản/đêm</th>
                    <th>Số khách</th>
                    <th>Số giường</th>
                    <th>Diện tích</th>
                    <th>Tiện ích</th>
                    <th>Trạng thái</th>
                    <th>Ngày xóa</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="roomTypesTableBody">
                <?php $__empty_1 = true; $__currentLoopData = $roomTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roomType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($roomType->rt_id); ?></td>
                    <td><strong><?php echo e($roomType->type_name); ?></strong></td>
                    <td>
                        <div class="room-images">
                            <?php if($roomType->images): ?>
                            <?php
                            $images = json_decode($roomType->images, true);
                            $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;
                            ?>
                            <?php if($firstImage): ?>
                            <img src="<?php echo e(asset($firstImage)); ?>" alt="<?php echo e($roomType->type_name); ?>"
                                class="room-image" onclick="showImageModal('<?php echo e(asset($firstImage)); ?>')"
                                style="width: 60px; height: 45px; object-fit: cover; border-radius: 4px; margin-right: 5px;">
                            <?php if(count($images) > 1): ?>
                            <span class="image-count" style="font-size: 12px; color: #666;">+<?php echo e(count($images) - 1); ?></span>
                            <?php endif; ?>
                            <?php else: ?>
                            <span style="color: #999; font-style: italic;">Không có ảnh</span>
                            <?php endif; ?>
                            <?php else: ?>
                            <span style="color: #999; font-style: italic;">Không có ảnh</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><strong><?php echo e(number_format($roomType->base_price, 0, ',', '.')); ?> VND</strong></td>
                    <td><?php echo e($roomType->max_guests ?? 'N/A'); ?> khách</td>
                    <td><?php echo e($roomType->number_beds ?? 'N/A'); ?> giường</td>
                    <td><?php echo e($roomType->room_size ?? 'N/A'); ?></td>
                    <td>
                        <?php if($roomType->amenities): ?>
                        <?php
                        $amenities = json_decode($roomType->amenities, true);
                        ?>
                        <?php if(is_array($amenities) && count($amenities) > 0): ?>
                        <div class="amenities-list" style="max-width: 150px;">
                            <?php $__currentLoopData = array_slice($amenities, 0, 2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="amenity-tag" style="display: inline-block; background: #e9ecef; padding: 2px 6px; border-radius: 3px; margin: 1px; font-size: 11px;"><?php echo e($amenity); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if(count($amenities) > 2): ?>
                            <span style="font-size: 12px; color: #666;">+<?php echo e(count($amenities) - 2); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php else: ?>
                        <span style="color: #999; font-style: italic;">Không có</span>
                        <?php endif; ?>
                        <?php else: ?>
                        <span style="color: #999; font-style: italic;">Không có</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($roomType->status == 1): ?>
                        <span class="status-badge status-active">Hoạt động</span>
                        <?php else: ?>
                        <span class="status-badge status-inactive">Không hoạt động</span>
                        <?php endif; ?>
                    </td>

                    <td><?php echo e($roomType->updated_at->format('d/m/Y H:i')); ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="<?php echo e(route('admin.roomType.restore', $roomType->rt_id)); ?>"
                                class="btn btn-success btn-sm"
                                title="Khôi phục"
                                onclick="return confirm('Bạn có chắc chắn muốn khôi phục loại phòng này?')">
                                <i class="fas fa-undo"></i>
                            </a>
                            <?php if(Auth::check() && Auth::user()->role === 'admin'): ?>
                            <a href="<?php echo e(route('admin.roomType.forceDelete', $roomType->rt_id)); ?>"
                                class="btn btn-danger btn-sm"
                                title="Xóa vĩnh viễn"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn loại phòng này?\nThao tác này không thể hoàn tác!')">
                                <i class="fas fa-trash"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr id="noDataRow">
                    <td colspan="11" style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-trash-alt" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                        <span id="noDataMessage">
                            <?php if(request()->hasAny(['status', 'max_guests', 'price_range', 'number_beds', 'search'])): ?>
                            Không tìm thấy loại phòng nào phù hợp với tiêu chí tìm kiếm trong thùng rác.
                            <?php else: ?>
                            Thùng rác trống.
                            <?php endif; ?>
                        </span>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if($roomTypes->hasPages()): ?>
    <div class="pagination-container" style="margin-top: 20px;">
        <?php echo e($roomTypes->appends(request()->query())->links()); ?>

    </div>
    <?php endif; ?>
</div>


<div id="imageModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.8);" onclick="closeImageModal()">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); max-width: 90%; max-height: 90%;">
        <img id="modalImage" style="width: 100%; height: auto; border-radius: 8px;">
        <button onclick="closeImageModal()" style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.8); border: none; border-radius: 50%; width: 40px; height: 40px; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<script>
    // Hàm áp dụng bộ lọc
    function applyFilters() {
        document.getElementById('filterForm').submit();
    }

    // Hàm xóa bộ lọc
    function clearFilters() {
        window.location.href = "<?php echo e(route('admin.roomType.trash')); ?>";
    }

    // Hàm tìm kiếm
    let searchTimeout;

    function searchRoomTypes(query) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const form = document.getElementById('filterForm');
            const searchInput = form.querySelector('input[name="search"]');
            searchInput.value = query;
            form.submit();
        }, 500);
    }

    // Hiển thị modal ảnh
    function showImageModal(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageSrc;
        modal.style.display = 'block';
    }

    // Đóng modal ảnh
    function closeImageModal() {
        document.getElementById('imageModal').style.display = 'none';
    }

    // Đóng modal khi nhấn ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });

    // Cập nhật số lượng loại phòng
    document.addEventListener('DOMContentLoaded', function() {
        const roomTypeCount = document.querySelectorAll('#roomTypesTableBody tr:not(#noDataRow)').length;
        document.getElementById('roomTypeCount').textContent = roomTypeCount;
    });
</script>

<style>
    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-active {
        background-color: #d4edda;
        color: #155724;
    }

    .status-inactive {
        background-color: #f8d7da;
        color: #721c24;
    }

    .room-image {
        cursor: pointer;
        transition: transform 0.2s;
    }

    .room-image:hover {
        transform: scale(1.05);
    }

    .amenity-tag {
        white-space: nowrap;
    }

    .amenities-list {
        overflow: hidden;
    }

    .action-buttons {
        display: flex;
        gap: 5px;
    }

    .action-buttons .btn {
        padding: 5px 8px;
        font-size: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .filter-bar {
        display: flex;
        gap: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 20px;
        align-items: end;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .filter-label {
        font-size: 14px;
        font-weight: 500;
        color: #495057;
    }

    .filter-select,
    .filter-input {
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 14px;
    }

    .filter-select:focus,
    .filter-input:focus {
        outline: none;
        border-color: #007bff;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .table-title {
        margin: 0;
        color: #495057;
    }

    .table-actions {
        display: flex;
        gap: 10px;
    }

    .page-title {
        color: #495057;
        margin-bottom: 30px;
    }

    .page-title i {
        margin-right: 10px;
    }

    .btn {
        display: inline-block;
        padding: 8px 16px;
        text-decoration: none;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: background-color 0.2s;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-success {
        background-color: #28a745;
        color: white;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .btn-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .btn-warning:hover {
        background-color: #e0a800;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #545b62;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .data-table th,
    .data-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #dee2e6;
    }

    .data-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
    }

    .data-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table-responsive {
        overflow-x: auto;
    }

    @media (max-width: 768px) {
        .filter-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-group {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }

        .table-header {
            flex-direction: column;
            gap: 15px;
            align-items: stretch;
        }

        .table-actions {
            flex-direction: column;
        }
    }
</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/admin/room_types/trash.blade.php ENDPATH**/ ?>