

<style>
    .chat-box {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        max-width: 100%;
        margin: auto;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    .user-header {
        border-bottom: 2px solid #ccc;
        padding-bottom: 15px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-header .avatar {
        width: 40px;
        height: 40px;
        background: #ddd;
        border-radius: 50%;
    }

    .message-row {
        display: flex;
        margin-bottom: 20px;
        align-items: flex-start;
    }

    .message-row.left {
        flex-direction: row;
    }

    .message-row.right {
        flex-direction: row-reverse;
        text-align: right;
    }

    .avatar {
        width: 40px;
        height: 40px;
        background: #bbb;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .message-content {
        background: #f1f1f1;
        padding: 10px 15px;
        border-radius: 10px;
        max-width: 70%;
    }

    .message-row.right .message-content {
        background: #d4edda;
    }

    .message-author {
        font-weight: bold;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .reply-form {
        margin-top: 30px;
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }

    .reply-form textarea {
        flex: 1;
        resize: none;
        min-height: 80px;
        border-radius: 6px;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
    }

    .reply-form button {
        height: 40px;
        padding: 0 16px;
        font-weight: bold;
    }

    #message-container {
        max-height: 500px;
        /* Giới hạn chiều cao vùng tin nhắn */
        overflow-y: auto;
        /* Kích hoạt thanh cuộn dọc */
        padding-right: 5px;
        /* Thêm khoảng trống để không che chữ */
        scrollbar-width: thin;
        /* Thanh cuộn mảnh trên Firefox */
    }

    /* Ẩn thanh cuộn xấu xí trên Chrome */
    #message-container::-webkit-scrollbar {
        width: 6px;
    }

    #message-container::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }

    #message-container::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
</style>

<?php $__env->startSection('content'); ?>
    <div class="chat-box">
        
        <div class="user-header">
            <div class="avatar"></div>
            <div><?php echo e($contact->user->name); ?></div>
        </div>

        
        <div id="message-container">
            <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="message-row <?php echo e($item['type'] === 'admin' ? 'right' : 'left'); ?>">
                    <div class="avatar"></div>
                    <div class="message-content">
                        <div class="message-author"><?php echo e($item['name']); ?></div>
                        <div><?php echo e($item['message']); ?></div>
                        <div class="text-muted" style="font-size: 12px;"><?php echo e($item['created_at']); ?></div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <form action="<?php echo e(route('admin.contacts.reply', $contact->id)); ?>" method="POST" class="reply-form">
            <?php echo csrf_field(); ?>
            <textarea name="reply" placeholder="Nhập phản hồi..." required></textarea>
            <button type="submit" class="btn btn-primary">Gửi phản hồi</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script>
        // Scroll xuống cuối khi trang load xong
        window.addEventListener('load', function () {
            const container = document.getElementById('message-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/admin/contact/show.blade.php ENDPATH**/ ?>