@extends('admin.layouts.master')

@section('content')
    <style>
        .form-wrapper {
            max-width: 450px;
            margin: 40px auto;
            padding: 30px;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            font-family: 'Segoe UI', sans-serif;
        }

        .form-wrapper h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #444;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            transition: border 0.3s;
        }

        .form-control:focus {
            border-color: #007bff;
            outline: none;
        }

        .form-error {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
        }

        .btn-submit {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }

        .alert-success {
            text-align: center;
            font-weight: bold;
            border-radius: 10px;
            background-color: #d1e7dd;
            color: #0f5132;
            padding: 10px;
            margin-bottom: 20px;
        }
    </style>

    <div class="form-wrapper">
        <h2>Đăng ký nhân viên</h2>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.staff') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Tên nhân viên</label>
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}">
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email nhân viên</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Mật khẩu</label>
                <input id="password" type="password" class="form-control" name="password">
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password-confirm" class="form-label">Xác nhận mật khẩu</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
            </div>

            <div class="form-group">
                <label for="avatar" class="form-label">Ảnh đại diện (tùy chọn)</label>
                <input type="file" name="avatar" class="form-control" id="avatar" accept="image/*">
            </div>

            <button type="submit" class="btn-submit">Đăng ký nhân viên</button>
        </form>
    </div>
@endsection