<?php $__env->startSection('content'); ?>
<div class="profile-container">
    <div class="profile-header">
        <div class="profile-header-content">
            <h1>Cài đặt tài khoản</h1>
            <p>Tùy chỉnh cài đặt tài khoản và thông báo theo ý muốn của bạn</p>
        </div>
    </div>

    <div class="profile-content">
        <div class="profile-card">
            <div class="card-header">
                <h2><i class="fas fa-cog"></i> Cài đặt chung</h2>
            </div>

            <?php if(session('status') === 'settings-updated'): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    Cài đặt đã được cập nhật thành công!
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo e($isAdmin ? route('admin.settings.update') : route('profile.settings.update')); ?>" class="profile-form">
                <?php echo csrf_field(); ?>
                <?php echo method_field('put'); ?>

                <?php if($isAdmin): ?>
                <div class="settings-section">
                    <h3><i class="fas fa-cogs"></i> Cài đặt hệ thống (Admin)</h3>
                    <div class="settings-group">
                        <div class="setting-item">
                            <div class="setting-info">
                                <label for="site_name">Tên website</label>
                                <p>Tên hiển thị của website</p>
                            </div>
                            <div class="setting-control">
                                <input type="text" id="site_name" name="site_name" 
                                       value="<?php echo e(old('site_name', $user->site_name ?? 'Booking Management')); ?>" 
                                       class="form-input">
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="setting-info">
                                <label for="site_description">Mô tả website</label>
                                <p>Mô tả ngắn gọn về website</p>
                            </div>
                            <div class="setting-control">
                                <textarea id="site_description" name="site_description" rows="3" 
                                          class="form-input"><?php echo e(old('site_description', $user->site_description ?? '')); ?></textarea>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="setting-info">
                                <label for="contact_email">Email liên hệ</label>
                                <p>Email chính để liên hệ</p>
                            </div>
                            <div class="setting-control">
                                <input type="email" id="contact_email" name="contact_email" 
                                       value="<?php echo e(old('contact_email', $user->contact_email ?? '')); ?>" 
                                       class="form-input">
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="setting-info">
                                <label for="contact_phone">Số điện thoại liên hệ</label>
                                <p>Số điện thoại chính để liên hệ</p>
                            </div>
                            <div class="setting-control">
                                <input type="tel" id="contact_phone" name="contact_phone" 
                                       value="<?php echo e(old('contact_phone', $user->contact_phone ?? '')); ?>" 
                                       class="form-input">
                            </div>
                        </div>
                        <div class="setting-item">
    <div class="setting-info">
        <label for="enable_email_verification">Bắt buộc xác thực email khi đăng ký</label>
        <p>Khi bật, người dùng cần xác minh email mới có thể đăng nhập</p>
    </div>
    <div class="setting-control">
        <label class="switch">
            <input type="checkbox" id="enable_email_verification" name="enable_email_verification"
                <?php echo e(setting('enable_email_verification') ? 'checked' : ''); ?>>
            <span class="slider"></span>
        </label>
    </div>
</div>


                        <div class="setting-item">
                            <div class="setting-info">
                                <label for="maintenance_mode">Chế độ bảo trì</label>
                                <p>Bật chế độ bảo trì để người dùng không thể truy cập</p>
                            </div>
                            <div class="setting-control">
                                <label class="switch">
                                    <input type="checkbox" id="maintenance_mode" name="maintenance_mode" 
                                           <?php echo e(($user->maintenance_mode ?? false) ? 'checked' : ''); ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="setting-info">
                                <label for="registration_enabled">Cho phép đăng ký</label>
                                <p>Cho phép người dùng mới đăng ký tài khoản</p>
                            </div>
                            <div class="setting-control">
                                <label class="switch">
                                    <input type="checkbox" id="registration_enabled" name="registration_enabled" 
                                           <?php echo e(($user->registration_enabled ?? true) ? 'checked' : ''); ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        
                    </div>
                </div>
                <?php endif; ?>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu cài đặt
                    </button>
                    <a href="<?php echo e($isAdmin ? route('admin.profile') : route('profile.edit')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.profile-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.profile-header {
    background: linear-gradient(135deg, #003580 0%, #004a9e 100%);
    color: white;
    padding: 40px 20px;
    border-radius: 12px;
    margin-bottom: 30px;
    text-align: center;
}

.profile-header h1 {
    font-size: 2rem;
    margin-bottom: 10px;
    font-weight: 600;
}

.profile-header p {
    font-size: 1rem;
    opacity: 0.9;
}

.profile-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.card-header {
    background: #f8f9fa;
    padding: 20px;
    border-bottom: 1px solid #e9ecef;
}

.card-header h2 {
    margin: 0;
    font-size: 1.25rem;
    color: #333;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-header i {
    color: #003580;
}

.profile-form {
    padding: 30px;
}

.alert {
    padding: 15px 20px;
    margin: 20px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.settings-section {
    margin-bottom: 40px;
}

.settings-section h3 {
    font-size: 1.1rem;
    color: #333;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e9ecef;
}

.settings-section i {
    color: #003580;
}

.settings-group {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.setting-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.setting-info {
    flex: 1;
}

.setting-info label {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}

.setting-info p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
}

.setting-control {
    min-width: 120px;
    text-align: right;
}

/* Switch toggle */
.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #003580;
}

input:checked + .slider:before {
    transform: translateX(26px);
}

/* Select dropdown */
.form-select {
    padding: 8px 12px;
    border: 2px solid #e9ecef;
    border-radius: 6px;
    font-size: 0.9rem;
    background: white;
    min-width: 150px;
}

.form-select:focus {
    outline: none;
    border-color: #003580;
    box-shadow: 0 0 0 3px rgba(0, 53, 128, 0.1);
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}

.btn-primary {
    background: #003580;
    color: white;
}

.btn-primary:hover {
    background: #002855;
    transform: translateY(-1px);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-1px);
}

.btn-outline {
    background: transparent;
    color: #003580;
    border: 1px solid #003580;
}

.btn-outline:hover {
    background: #003580;
    color: white;
}

@media (max-width: 768px) {
    .profile-container {
        padding: 15px;
    }
    
    .profile-header {
        padding: 30px 15px;
    }
    
    .profile-header h1 {
        font-size: 1.5rem;
    }
    
    .profile-form {
        padding: 20px;
    }
    
    .setting-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .setting-control {
        text-align: left;
        width: 100%;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .settings-section h3 {
        font-size: 1rem;
    }
    
    .setting-item {
        padding: 15px;
    }
    
    .form-select {
        width: 100%;
    }
}
</style>

<script>
function enable2FA() {
    alert('Tính năng xác thực 2 yếu tố sẽ được phát triển trong phiên bản tiếp theo.');
}

function manageSessions() {
    alert('Tính năng quản lý phiên đăng nhập sẽ được phát triển trong phiên bản tiếp theo.');
}

document.addEventListener('DOMContentLoaded', function() {
    // Xử lý toggle switches
    const switches = document.querySelectorAll('.switch input[type="checkbox"]');
    
    switches.forEach(switchInput => {
        switchInput.addEventListener('change', function() {
            // Có thể thêm logic xử lý real-time ở đây
            console.log('Setting changed:', this.name, this.checked);
        });
    });
});
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\booking-management\resources\views/profile/settings.blade.php ENDPATH**/ ?>