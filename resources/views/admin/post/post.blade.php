@extends('admin.layouts.master')
@section("content")

@if(session('success'))
<div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 20px;">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 20px;">
    {{ session('error') }}
</div>
@endif

<h1 class="page-title">
    <i class="fas fa-newspaper"></i>
    Quản lý Bài viết
</h1>

<div class="table-container">
    <div class="table-header">
        <h2 class="table-title">Danh sách bài viết (<span id="postCount">{{ $posts->total() }}</span> bài viết)</h2>
        <div class="table-actions">
            <a href="{{ route('admin.post.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Thêm bài viết mới
            </a>
        </div>
    </div>

    <form id="filterForm" method="GET" action="{{ route('admin.post.index') }}">
        <div class="filter-bar">
            <div class="filter-group">
                <label class="filter-label">Trạng thái</label>
         <select name="status" class="filter-select" onchange="applyFilters()">
    <!-- <option value="">Tất cả</option> -->
    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hiển thị</option>
    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn</option>
</select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Danh mục</label>
                <input type="text" name="category" class="filter-input" placeholder="Nhập danh mục..."
                    value="{{ request('category') }}">
            </div>
            <div class="filter-group">
                <label class="filter-label">Tìm kiếm</label>
                <input type="text" name="search" class="filter-input" placeholder="Tiêu đề, tác giả..."
                    value="{{ request('search') }}">
            </div>
            <div class="filter-group" style="align-items: end;">
                <button type="button" class="btn btn-secondary" onclick="clearFilters()">
                    <i class="fas fa-times"></i> Xóa bộ lọc
                </button>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tiêu đề</th>
                    <th>Danh mục</th>
                    <th>Tác giả</th>
                    <th>Ảnh</th>
                    <th>Trạng thái</th>
                    <th>Ngày đăng</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="postsTableBody">
                @forelse($posts as $post)
                <tr>
                    <td>{{ $post->p_id }}</td>
                    <td><strong>{{ $post->title }}</strong></td>
                    <td>{{ $post->category }}</td>
                    <td>{{ $post->author }}</td>
                    <td>
                        @if($post->images)
                            <img src="{{ asset('storage/'.$post->images) }}" alt="{{ $post->title }}" 
                                 style="width: 60px; height: 45px; object-fit: cover; border-radius: 4px;">
                        @else
                            <span style="color: #999; font-style: italic;">Không có ảnh</span>
                        @endif
                    </td>
                    <td>
                        @if($post->status == 1)
                            <span class="status-badge status-active">Hiển thị</span>
                        @else
                            <span class="status-badge status-inactive">Ẩn</span>
                        @endif
                    </td>
                    <td>{{ $post->published_at ?? 'Chưa đăng' }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.post.edit', $post->p_id) }}" class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.post.destroy', $post->p_id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-newspaper" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                        <span>
                            @if(request()->hasAny(['status','category','search']))
                                Không tìm thấy bài viết nào phù hợp với tiêu chí tìm kiếm.
                            @else
                                Chưa có bài viết nào được tạo.
                            @endif
                        </span>
                        <br><br>
                        @if(!request()->hasAny(['status','category','search']))
                        <a href="{{ route('admin.post.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm bài viết đầu tiên
                        </a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-container" style="margin-top: 20px; text-align: center;">
        {{ $posts->links() }}
    </div>
</div>

<script>
function applyFilters() {
    document.getElementById('filterForm').submit();
}
function clearFilters() {
    window.location.href = "{{ route('admin.post.index') }}";
}
</script>
@endsection
