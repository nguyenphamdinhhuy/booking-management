<?php $__env->startSection('content'); ?>
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-header-content">
                <h1>Thông tin cá nhân</h1>
                <p>Cập nhật thông tin tài khoản và thông tin cá nhân của bạn</p>
            </div>
        </div>

        <div class="profile-content">
            <div class="profile-card">
                <div class="card-header">
                    <h2><i class="fas fa-user"></i> Thông tin cơ bản</h2>
                </div>

                <?php if(session('status') === 'profile-updated'): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Thông tin đã được cập nhật thành công!
                    </div>
                <?php endif; ?>

                <form method="post" action="<?php echo e($isAdmin ? route('admin.profile.update') : route('profile.update')); ?>"
                    class="profile-form" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field($isAdmin ? 'put' : 'patch'); ?>

                    <div class="avatar-section">
                        <div class="avatar-preview">
                            <img src="<?php echo e(Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'http://localhost:8000/storage/avatars/default-avatar.png'); ?>"" alt="Avatar" id="avatarPreview" class="avatar-image">
                            <div class="avatar-overlay">
                                <i class="fas fa-camera"></i>
                            </div>
                        </div>
                        <div class="avatar-upload">
                            <label for="avatar" class="btn btn-outline">
                                <i class="fas fa-upload"></i> Thay đổi ảnh
                            </label>
                            <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;">
                            <p class="upload-hint">JPG, PNG hoặc GIF. Tối đa 2MB.</p>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Họ và tên</label>
                            <input type="text" id="name" name="name" value="<?php echo e(old('name', $user->name)); ?>"
                                class="form-input <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="error-message"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo e(old('email', $user->email)); ?>"
                                class="form-input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="error-message"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo e(old('phone', $user->phone ?? '')); ?>"
                                class="form-input <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="error-message"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label for="birth_date">Ngày sinh</label>
                            <input type="date" id="birth_date" name="birth_date"
                                value="<?php echo e(old('birth_date', $user->birth_date ?? '')); ?>"
                                class="form-input <?php $__errorArgs = ['birth_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['birth_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="error-message"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Địa chỉ</label>
                        <textarea id="address" name="address" rows="3"
                            class="form-input <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('address', $user->address ?? '')); ?></textarea>
                        <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="error-message"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Cập nhật thông tin
                        </button>
                        <a href="<?php echo e($isAdmin ? route('admin.password') : route('profile.password')); ?>"
                            class="btn btn-outline">
                            <i class="fas fa-lock"></i> Đổi mật khẩu
                        </a>
                    </div>
                </form>
            </div>

            <div class="profile-card">
                <div class="card-header">
                    <h2><i class="fas fa-trash-alt"></i> Xóa tài khoản</h2>
                </div>
                <div class="profile-form">
                    <p class="danger-text">
                        <i class="fas fa-exclamation-triangle"></i>
                        Một khi tài khoản của bạn bị xóa, tất cả tài nguyên và dữ liệu của nó sẽ bị xóa vĩnh viễn.
                        Vui lòng nhập mật khẩu của bạn để xác nhận rằng bạn muốn xóa vĩnh viễn tài khoản của mình.
                    </p>

                    <form method="post" action="<?php echo e(route('profile.destroy')); ?>" class="delete-form">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('delete'); ?>

                        <div class="form-group">
                            <label for="password">Mật khẩu</label>
                            <input type="password" id="password" name="password"
                                class="form-input <?php $__errorArgs = ['password', 'userDeletion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Nhập mật khẩu để xác nhận">
                            <?php $__errorArgs = ['password', 'userDeletion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="error-message"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản? Hành động này không thể hoàn tác.')">
                            <i class="fas fa-trash"></i> Xóa tài khoản
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>





    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Xử lý upload avatar
            const avatarInput = document.getElementById('avatar');
            const avatarPreview = document.getElementById('avatarPreview');
            const avatarSection = document.querySelector('.avatar-preview');

            avatarInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        avatarPreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });

            avatarSection.addEventListener('click', function () {
                avatarInput.click();
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\booking-management\resources\views/profile/edit.blade.php ENDPATH**/ ?>