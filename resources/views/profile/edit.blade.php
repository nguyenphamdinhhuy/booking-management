@extends($layout)

@section('content')
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

                @if (session('status') === 'profile-updated')
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Thông tin đã được cập nhật thành công!
                    </div>
                @endif

                <form method="post" action="{{ $isAdmin ? route('admin.profile.update') : route('profile.update') }}"
                    class="profile-form" enctype="multipart/form-data">
                    @csrf
                    @method($isAdmin ? 'put' : 'patch')

                    <div class="avatar-section">
                        <div class="avatar-preview">
                            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'http://localhost:8000/storage/avatars/default-avatar.png' }}"" alt="Avatar" id="avatarPreview" class="avatar-image">
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
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                class="form-input @error('name') is-invalid @enderror" required>
                            @error('name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                class="form-input @error('email') is-invalid @enderror" required>
                            @error('email')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                                class="form-input @error('phone') is-invalid @enderror">
                            @error('phone')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="birth_date">Ngày sinh</label>
                            <input type="date" id="birth_date" name="birth_date"
                                value="{{ old('birth_date', $user->birth_date ?? '') }}"
                                class="form-input @error('birth_date') is-invalid @enderror">
                            @error('birth_date')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Địa chỉ</label>
                        <textarea id="address" name="address" rows="3"
                            class="form-input @error('address') is-invalid @enderror">{{ old('address', $user->address ?? '') }}</textarea>
                        @error('address')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Cập nhật thông tin
                        </button>
                        <a href="{{ $isAdmin ? route('admin.password') : route('profile.password') }}"
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

                    <form method="post" action="{{ route('profile.destroy') }}" class="delete-form">
                        @csrf
                        @method('delete')

                        <div class="form-group">
                            <label for="password">Mật khẩu</label>
                            <input type="password" id="password" name="password"
                                class="form-input @error('password', 'userDeletion') is-invalid @enderror"
                                placeholder="Nhập mật khẩu để xác nhận">
                            @error('password', 'userDeletion')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
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
@endsection