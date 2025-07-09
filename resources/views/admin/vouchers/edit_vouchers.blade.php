@extends('admin.layouts.master')
@section("content")

{{-- Hiển thị thông báo --}}
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

{{-- Hiển thị lỗi validation --}}
@if($errors->any())
<div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 20px;">
    <ul style="margin: 0; padding-left: 20px;">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<h1 class="page-title">
    <i class="fas fa-edit"></i>
    Chỉnh sửa voucher: {{ $voucher->v_code }}
</h1>

<!-- Edit Voucher Form -->
<div class="content-section">
    <form action="{{ route('vouchers.update', $voucher->v_id) }}" method="POST" class="room-form" id="editVoucherForm">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <!-- Voucher Code -->
            <div class="form-group">
                <label for="v_code" class="form-label">
                    <i class="fas fa-tag"></i>
                    Mã Voucher <span class="required">*</span>
                </label>
                <input type="text" id="v_code" name="v_code" class="form-input"
                    value="{{ old('v_code', $voucher->v_code) }}" maxlength="10" required
                    placeholder="Nhập mã voucher (tối đa 10 ký tự)">
                <small class="form-text">Mã voucher sẽ được tự động chuyển thành chữ hoa</small>
            </div>

            <!-- Discount Percent -->
            <div class="form-group">
                <label for="discount_percent" class="form-label">
                    <i class="fas fa-percent"></i>
                    Phần Trăm Giảm Giá <span class="required">*</span>
                </label>
                <input type="number" id="discount_percent" name="discount_percent" class="form-input"
                    value="{{ old('discount_percent', $voucher->discount_percent) }}" min="0" max="100" step="0.01" required
                    placeholder="Nhập phần trăm giảm giá (0-100)">
            </div>

            <!-- Start Date -->
            <div class="form-group">
                <label for="start_date" class="form-label">
                    <i class="fas fa-calendar-alt"></i>
                    Ngày Bắt Đầu <span class="required">*</span>
                </label>
                <input type="datetime-local" id="start_date" name="start_date" class="form-input"
                    value="{{ old('start_date', \Carbon\Carbon::parse($voucher->start_date)->format('Y-m-d\TH:i')) }}" required>
            </div>

            <!-- End Date -->
            <div class="form-group">
                <label for="end_date" class="form-label">
                    <i class="fas fa-calendar-times"></i>
                    Ngày Kết Thúc <span class="required">*</span>
                </label>
                <input type="datetime-local" id="end_date" name="end_date" class="form-input"
                    value="{{ old('end_date', \Carbon\Carbon::parse($voucher->end_date)->format('Y-m-d\TH:i')) }}" required>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status" class="form-label">
                    <i class="fas fa-toggle-on"></i>
                    Trạng Thái <span class="required">*</span>
                </label>
                <select id="status" name="status" class="form-select" required>
                    <option value="">Chọn trạng thái</option>
                    <option value="1" {{ old('status', $voucher->status) == '1' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('status', $voucher->status) == '0' ? 'selected' : '' }}>Không hoạt động</option>
                </select>
            </div>

            <!-- Description -->
            <div class="form-group full-width">
                <label for="description" class="form-label">
                    <i class="fas fa-align-left"></i>
                    Mô Tả
                </label>
                <textarea id="description" name="description" class="form-textarea" rows="4"
                    placeholder="Nhập mô tả cho voucher (tùy chọn)">{{ old('description', $voucher->description) }}</textarea>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <a href="{{ route('vouchers.management') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Hủy bỏ
            </a>
            <button type="reset" class="btn btn-outline">
                <i class="fas fa-redo"></i>
                Làm mới
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Cập Nhật Voucher
            </button>
        </div>
    </form>
</div>

@endsection