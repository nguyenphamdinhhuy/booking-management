
<style>
    .contact-header {
        text-align: center;
        padding: 40px 20px;
    }

    .contact-title {
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .contact-title .highlight {
        color: #007bff;
        /* Màu xanh như từ "Homenest" */
    }

    .breadcrumb {
        color: #888;
        font-size: 14px;
        margin-bottom: 30px;
    }

    .contact-info-boxes {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
    }

    .info-box {
        display: flex;
        align-items: center;
        background: #f5f7fa;
        padding: 20px 30px;
        border-radius: 15px;
        min-width: 230px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        gap: 15px;
    }

    .info-box .icon {
        background: linear-gradient(to right, #007bff, #00c6ff);
        color: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .info-box h4 {
        font-size: 16px;
        margin: 0 0 4px 0;
        font-weight: bold;
    }

    .info-box p {
        margin: 0;
        font-size: 14px;
    }




    .contact-container {
        padding: 40px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .contact-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
    }

    .contact-map {
        flex: 1 1 45%;
        min-width: 300px;
    }

    .contact-form {
        flex: 1 1 45%;
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .form-row {
        display: flex;
        gap: 15px;
    }

    .form-input {
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        flex: 1;
    }

    .full-width {
        width: 100%;
    }

    .form-select {
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .form-textarea {
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        resize: vertical;
    }

    .form-submit-btn {
        padding: 12px;
        background: linear-gradient(to right, #007bff, #00c6ff);
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
    }

    .form-submit-btn:hover {
        opacity: 0.9;
    }

    .contact-success {
        background-color: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        border: 1px solid #c3e6cb;
    }


    /*  */
    /* Nút mở chat nổi */
    .chat-toggle-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background-color: #007bff;
        color: white;
        border-radius: 50%;
        font-size: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 999;
    }

    /* Khung chat nổi */
    .chat-popup {
        position: fixed;
        bottom: 100px;
        right: 30px;
        width: 350px;
        max-height: 500px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        display: none;
        flex-direction: column;
        overflow: hidden;
        z-index: 998;
    }

    .chat-popup-header {
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        font-weight: bold;
    }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 15px;
    }

    .chat-message {
        margin-bottom: 15px;
        max-width: 100%;
        padding: 10px 12px;
        border-radius: 8px;
        font-size: 14px;
        line-height: 1.4;
    }

    .chat-message.user {
        background-color: #f1f1f1;
        text-align: left;
        align-self: flex-start;
    }

    .chat-message.admin {
        background-color: #d4edda;
        text-align: right;
        align-self: flex-end;
    }

    .chat-popup-footer {
        padding: 10px;
        border-top: 1px solid #eee;
    }

    .chat-popup-footer textarea {
        width: 100%;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        resize: none;
        font-size: 14px;
    }

    .chat-popup-footer button {
        margin-top: 5px;
        width: 100%;
        background-color: #007bff;
        color: white;
        padding: 8px;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
    }
</style>
<?php $__env->startSection('content'); ?>
    <div class="contact-container">
        <!-- THÔNG TIN LIÊN HỆ NGẮN GỌN -->
        <div class="contact-header">
            <h2 class="contact-title">
                Liên hệ <span class="highlight">Chúng tôi</span>
            </h2>
            <p class="breadcrumb">Trang Chủ / Contact</p>

            <div class="contact-info-boxes">
                <div class="info-box">
                    <div class="icon"><i class="fa fa-phone"></i></div>
                    <div>
                        <h4>Hotline</h4>
                        <p>0898 – 994 – 298</p>
                    </div>
                </div>
                <div class="info-box">
                    <div class="icon"><i class="fa fa-envelope"></i></div>
                    <div>
                        <h4>Email</h4>
                        <p>info@homenest.media</p>
                    </div>
                </div>
                <div class="info-box">
                    <div class="icon"><i class="fa fa-clock-o"></i></div>
                    <div>
                        <h4>Giờ Làm Việc</h4>
                        <p>Thứ 2 – Thứ 7: 8:30 am – 17:30 pm</p>
                    </div>
                </div>

            </div>
        </div>

        <div class="contact-wrapper">
            <!-- BẢN ĐỒ -->
            <div class="contact-map">
                <img src="<?php echo e(asset('images/lienhe.png')); ?>" alt="Bản đồ liên hệ" width="100%" height="400"
                    style="border:0; object-fit: cover;">
            </div>


            <!-- FORM LIÊN HỆ -->
            <div class="contact-form">
                <?php if(session('success')): ?>
                    <div id="success-alert" class="contact-success">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>


                <form action="<?php echo e(route('contact.store')); ?>" method="POST" class="form-group">
                    <?php echo csrf_field(); ?>
                    <div class="form-row">
                        <input type="text" name="name" placeholder="<?php echo e(Auth::user()->name); ?>" class="form-input" disabled>
                        <input type="text" name="phone" placeholder="Số điện thoại" class="form-input" required>
                    </div>

                    <input type="email" name="email" placeholder="<?php echo e(Auth::user()->email); ?>" class="form-input full-width"
                        disabled>

                    <input type="text" name="subject" placeholder="Chủ đề liên hệ" class="form-input full-width" required>

                    <textarea name="message" rows="5" placeholder="Nội dung" class="form-textarea" required></textarea>

                    <button type="submit" class="form-submit-btn">
                        Gửi ngay
                    </button>
                </form>

            </div>
        </div>

        
        <div class="chat-toggle-btn" onclick="toggleChat()">💬</div>

        
        <div class="chat-popup" id="chatBox">
            <div class="chat-popup-header">
                Trò chuyện với Admin
            </div>
            <div class="chat-messages" id="chatMessages">
                <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="chat-message <?php echo e($msg['type']); ?>">
                        <strong><?php echo e($msg['name']); ?></strong><br>
                        <?php echo e($msg['message']); ?><br>
                        <small><?php echo e(\Carbon\Carbon::parse($msg['created_at'])->format('d/m/Y H:i')); ?></small>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="chat-popup-footer">
                <form action="<?php echo e(route('contact.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <textarea name="message" placeholder="Nhập nội dung..." rows="2" required></textarea>
                    <button type="submit">Gửi</button>
                </form>
            </div>
        </div>

    </div>
    <script>
        // Tự động ẩn thông báo sau 5 giây
        setTimeout(function () {
            const alert = document.getElementById('success-alert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 5000); // 5000 milliseconds = 5 giây
    </script>
    <script>
        function toggleChat() {
            const chat = document.getElementById('chatBox');
            chat.style.display = chat.style.display === 'flex' ? 'none' : 'flex';

            // Scroll xuống dưới cùng
            const messages = document.getElementById('chatMessages');
            setTimeout(() => {
                messages.scrollTop = messages.scrollHeight;
            }, 100);
        }


        // chặn tải Trang
        $(document).ready(function () {
            $('#chatForm').on('submit', function (e) {
                e.preventDefault(); // Ngăn reload trang

                let message = $('#chatMessage').val();
                if (!message.trim()) return;

                $.ajax({
                    url: "<?php echo e(route('contact.store')); ?>",
                    method: "POST",
                    data: {
                        _token: "<?php echo e(csrf_token()); ?>",
                        message: message,
                        subject: "Tin nhắn trò chuyện", // Mặc định để khỏi nhập
                        phone: "N/A" // Dùng giá trị mặc định, không bắt nhập
                    },
                    success: function (response) {
                        // Sau khi gửi thành công, thêm tin nhắn vào khung chat ngay
                        $('#chatMessages').append(`
                                                                        <div class="chat-message user">
                                                                            <strong><?php echo e(Auth::user()->name); ?></strong><br>
                                                                            ${message}<br>
                                                                            <small>${new Date().toLocaleString()}</small>
                                                                        </div>
                                                                    `);

                        $('#chatMessage').val(''); // Xóa nội dung trong textarea
                        $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
                    },
                    error: function () {
                        alert('Có lỗi xảy ra, vui lòng thử lại!');
                    }
                });
            });
        });

    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/user/contact.blade.php ENDPATH**/ ?>