@extends($layout)

@section('content')
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-header-content">
                <h1>Đổi mật khẩu</h1>
                <p>Bảo mật tài khoản của bạn bằng cách thay đổi mật khẩu thường xuyên</p>
            </div>
        </div>

        <div class="profile-content">
            <div class="profile-card">
                <div class="card-header">
                    <h2><i class="fas fa-lock"></i> Thay đổi mật khẩu</h2>
                </div>

                @if (session('status') === 'password-updated')
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Mật khẩu đã được cập nhật thành công!
                    </div>
                @endif

                <form method="post"
                    action="{{ $isAdmin ? route('admin.password.update') : route('profile.password.update') }}"
                    class="profile-form">
                    @csrf
                    @method('put')

                    <div class="form-group">
                        <label for="current_password">Mật khẩu hiện tại</label>
                        <input type="password" id="current_password" name="current_password"
                            class="form-input @error('current_password', 'updatePassword') is-invalid @enderror" required>
                        @error('current_password', 'updatePassword')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Mật khẩu mới</label>
                        <input type="password" id="password" name="password"
                            class="form-input @error('password', 'updatePassword') is-invalid @enderror" required>
                        @error('password', 'updatePassword')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Xác nhận mật khẩu mới</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
                            required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Cập nhật mật khẩu
                        </button>
                        <a href="{{ $isAdmin ? route('admin.profile') : route('profile.edit') }}" class="btn btn-secondary">
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

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #003580;
            box-shadow: 0 0 0 3px rgba(0, 53, 128, 0.1);
        }

        .form-input.is-invalid {
            border-color: #dc3545;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 5px;
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

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
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

            .form-actions {
                flex-direction: column;
            }

            .btn {
                justify-content: center;
            }
        }
    </style>
@endsection