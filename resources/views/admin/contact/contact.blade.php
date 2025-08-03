@extends('admin.layouts.master')
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
@section('content')
    <div class="container py-4">
        <h2 class="mb-4">Danh sách liên hệ</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Người gửi</th>
                        <th>SĐT</th>
                        <th>Chủ đề</th>
                        <th>Nội dung</th>
                        <th>Ngày gửi</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $contact->user->name ?? 'Không xác định' }}</td>
                            <td>{{ $contact->phone }}</td>
                            <td>{{ $contact->subject }}</td>
                            <td>{{ Str::limit($contact->message, 50) }}</td>
                            <td>{{ \Carbon\Carbon::parse($contact->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($contact->is_read)
                                    <span style="color: green;">Đã xem</span>
                                @else
                                    <span style="color: red;">Chưa xem</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('admin.contacts.show', $contact->id) }}"
                                    class="btn btn-sm btn-primary">Xem</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Không có liên hệ nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection