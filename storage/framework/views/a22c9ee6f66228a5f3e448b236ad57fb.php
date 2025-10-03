
<style>
    /* Định dạng khung container */
    .container {
        background-color: #fff;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Tiêu đề */
    h2 {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    /* Bảng */
    .table {
        border-collapse: collapse;
        width: 100%;
        font-size: 14px;
    }

    .table thead th {
        background-color: #343a40;
        color: #fff;
        padding: 12px;
        text-align: center;
        vertical-align: middle;
    }

    .table tbody td {
        padding: 10px;
        vertical-align: middle;
        text-align: center;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Nút "Xem" */
    .btn-primary {
        background-color: #007bff;
        border: none;
        padding: 6px 12px;
        font-size: 13px;
        border-radius: 20px;
        transition: 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        color: #fff;
    }

    /* Thông báo */
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        padding: 10px 15px;
        border-left: 4px solid #28a745;
        border-radius: 4px;
        margin-bottom: 15px;
    }
</style>
<?php $__env->startSection('content'); ?>
    
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
            Quản lý liên hệ
        </span>
    </h1>


    <div class="table-container">
        <!-- Table Header with Actions -->
        <div class="table-header">
            <h2 class="table-title">Danh sách liên hệ</h2>
        </div>
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <!-- Data Table -->
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Người gửi</th>
                        <th>SĐT</th>
                        <th>Chủ đề</th>
                        <th>Nội dung</th>
                        <th>Ngày gửi</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody id="roomsTableBody">
                    <?php $__empty_1 = true; $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($contact->user->name ?? 'Không xác định'); ?></td>
                            <td><?php echo e($contact->phone); ?></td>
                            <td><?php echo e($contact->subject); ?></td>
                            <td><?php echo e(Str::limit($contact->message, 50)); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($contact->created_at)->format('d/m/Y H:i')); ?></td>
                            <td>
                                <?php if($contact->is_read): ?>
                                    <span style="color: green;">Đã xem</span>
                                <?php else: ?>
                                    <span style="color: red;">Chưa xem</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('admin.contacts.show', $contact->id)); ?>"
                                    class="btn btn-sm btn-primary">Xem</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">Không có liên hệ nào.</td>
                        </tr>
                    <?php endif; ?>

            </table>
        </div>

    </div>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/admin/contact/contact.blade.php ENDPATH**/ ?>