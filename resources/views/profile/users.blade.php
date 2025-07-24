@extends('admin.layouts.master')

@section('title', 'User Management')

@section('content')
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-header-content">
                <h1><i class="fas fa-users"></i> User Management</h1>
                <p>Quản lý tất cả người dùng trong hệ thống</p>
            </div>
        </div>
        
        <div class="profile-content">
            <div class="profile-card">
                <div class="card-header">
                    <h2><i class="fas fa-list"></i> Danh sách Người dùng</h2>
                </div>

                @if(session('status'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="users-content">
                    @if($users->count() > 0)
                        <div class="users-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Avatar</th>
                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>Vai trò</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <img src="{{ $user->avatar ?? asset('images/default-avatar.png') }}" alt="Avatar"
                                                    class="user-avatar">
                                            </td>
                                            <td>
                                                <div class="user-info">
                                                    <strong>{{ $user->name }}</strong>
                                                    @if($user->id === auth()->id())
                                                        <span class="badge badge-primary">Bạn</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'staff' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $user->status === 'active' ? 'success' : ($user->status === 'inactive' ? 'secondary' : 'danger') }}">
                                                    {{ ucfirst($user->status ?? 'active') }}
                                                </span>
                                            </td>
                                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="action-buttons">
                                                    @if($user->id !== auth()->id())
                                                        <form method="post" action="{{ route('admin.users.status', $user) }}"
                                                            class="inline-form">
                                                            @csrf
                                                            @method('put')
                                                            <select name="status" onchange="this.form.submit()" class="status-select">
                                                                <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>
                                                                    Active
                                                                </option>
                                                                <option value="locked" {{ $user->status === 'locked' ? 'selected' : '' }}>
                                                                    Locked
                                                                </option>
                                                            </select>
                                                        </form>
                                                    @else
                                                        <span class="text-muted">Không thể thao tác</span>
                                                    @endif
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="pagination-wrapper">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Không có người dùng nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .profile-container {
            max-width: 1400px;
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .profile-header p {
            font-size: 1rem;
            opacity: 0.9;
        }

        .profile-content {
            display: flex;
            flex-direction: column;
            gap: 30px;
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

        .users-content {
            padding: 20px;
        }

        .users-table {
            overflow-x: auto;
        }

        .users-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .users-table th,
        .users-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        .users-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        .users-table tr:hover {
            background: #f8f9fa;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-primary {
            background: #007bff;
            color: white;
        }

        .badge-danger {
            background: #dc3545;
            color: white;
        }

        .badge-warning {
            background: #ffc107;
            color: #212529;
        }

        .badge-info {
            background: #17a2b8;
            color: white;
        }

        .badge-success {
            background: #28a745;
            color: white;
        }

        .badge-secondary {
            background: #6c757d;
            color: white;
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .inline-form {
            display: inline;
        }

        .status-select {
            padding: 4px 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
            background: white;
        }

        .delete-form {
            margin: 0;
        }

        .btn {
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 11px;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
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

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .pagination-wrapper {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .text-muted {
            color: #6c757d;
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .users-table {
                font-size: 14px;
            }

            .users-table th,
            .users-table td {
                padding: 8px;
            }

            .user-avatar {
                width: 30px;
                height: 30px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 4px;
            }

            .status-select {
                font-size: 11px;
                padding: 2px 4px;
            }
        }
    </style>
@endsection