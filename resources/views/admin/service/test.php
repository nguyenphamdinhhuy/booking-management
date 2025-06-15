@extends('admin.layouts.master')
@section("content")

    <h1 class="page-title">
        <i class="fas fa-concierge-bell"></i>
        Quản lý dịch vụ
    </h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Thêm dịch vụ
    </a>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên dịch vụ</th>
                    <th>Giá</th>
                    <th>Đơn vị</th>
                    <th>Danh mục</th>
                    <th>Trạng thái</th>
                    <th>Ảnh</th>
                    <th>Mô tả</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $sv)
                    <tr>
                        <td>{{ $sv->s_id }}</td>
                        <td>{{ $sv->name }}</td>
                        <td>{{ number_format($sv->price) }} VND</td>
                        <td>{{ $sv->unit }}</td>
                        <td>{{ $sv->category->name ?? 'Không có' }}</td>
                        <td>
                            <span class="status-badge {{ $sv->is_available ? 'status-active' : 'status-inactive' }}">
                                {{ $sv->is_available ? 'Hoạt động' : 'Ngưng' }}
                            </span>
                        </td>
                        <td>
                            @if($sv->image)
                                <img src="{{ asset($sv->image) }}" width="80" height="60">
                            @else
                                Không có
                            @endif
                        </td>
                        <td>{{ Str::limit($sv->description, 50) }}</td>
                        <td>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection