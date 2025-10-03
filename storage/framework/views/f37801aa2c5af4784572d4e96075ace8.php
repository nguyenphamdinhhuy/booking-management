
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
    <i class="fas fa-newspaper"></i>
    Quản lý Bài viết
</h1>

<div class="table-container">
    <div class="table-header">
        <h2 class="table-title">Danh sách bài viết (<span id="postCount"><?php echo e($posts->total()); ?></span> bài viết)</h2>
        <div class="table-actions">
            <a href="<?php echo e(route('admin.post.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Thêm bài viết mới
            </a>
        </div>
    </div>

    <form id="filterForm" method="GET" action="<?php echo e(route('admin.post.index')); ?>">
        <div class="filter-bar">
            <div class="filter-group">
                <label class="filter-label">Trạng thái</label>
         <select name="status" class="filter-select" onchange="applyFilters()">
    <!-- <option value="">Tất cả</option> -->
    <option value="1" <?php echo e(request('status') === '1' ? 'selected' : ''); ?>>Hiển thị</option>
    <option value="0" <?php echo e(request('status') === '0' ? 'selected' : ''); ?>>Ẩn</option>
</select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Danh mục</label>
                <input type="text" name="category" class="filter-input" placeholder="Nhập danh mục..."
                    value="<?php echo e(request('category')); ?>">
            </div>
            <div class="filter-group">
                <label class="filter-label">Tìm kiếm</label>
                <input type="text" name="search" class="filter-input" placeholder="Tiêu đề, tác giả..."
                    value="<?php echo e(request('search')); ?>">
            </div>
            <div class="filter-group" style="align-items: end;">
                <button type="button" class="btn btn-secondary" onclick="clearFilters()">
                    <i class="fas fa-times"></i> Xóa bộ lọc
                </button>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tiêu đề</th>
                    <th>Danh mục</th>
                    <th>Tác giả</th>
                    <th>Ảnh</th>
                    <th>Trạng thái</th>
                    <th>Ngày đăng</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="postsTableBody">
                <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($post->p_id); ?></td>
                    <td><strong><?php echo e($post->title); ?></strong></td>
                    <td><?php echo e($post->category); ?></td>
                    <td><?php echo e($post->author); ?></td>
                    <td>
                        <?php if($post->images): ?>
                            <img src="<?php echo e(asset('storage/'.$post->images)); ?>" alt="<?php echo e($post->title); ?>" 
                                 style="width: 60px; height: 45px; object-fit: cover; border-radius: 4px;">
                        <?php else: ?>
                            <span style="color: #999; font-style: italic;">Không có ảnh</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($post->status == 1): ?>
                            <span class="status-badge status-active">Hiển thị</span>
                        <?php else: ?>
                            <span class="status-badge status-inactive">Ẩn</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($post->published_at ?? 'Chưa đăng'); ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="<?php echo e(route('admin.post.edit', $post->p_id)); ?>" class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo e(route('admin.post.destroy', $post->p_id)); ?>" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-newspaper" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                        <span>
                            <?php if(request()->hasAny(['status','category','search'])): ?>
                                Không tìm thấy bài viết nào phù hợp với tiêu chí tìm kiếm.
                            <?php else: ?>
                                Chưa có bài viết nào được tạo.
                            <?php endif; ?>
                        </span>
                        <br><br>
                        <?php if(!request()->hasAny(['status','category','search'])): ?>
                        <a href="<?php echo e(route('admin.post.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm bài viết đầu tiên
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="pagination-container" style="margin-top: 20px; text-align: center;">
        <?php echo e($posts->links()); ?>

    </div>
</div>

<script>
function applyFilters() {
    document.getElementById('filterForm').submit();
}
function clearFilters() {
    window.location.href = "<?php echo e(route('admin.post.index')); ?>";
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/admin/post/post.blade.php ENDPATH**/ ?>